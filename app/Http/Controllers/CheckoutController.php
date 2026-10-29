<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\Events\OrderCreated;
use App\Exceptions\CartException;
use App\Models\City;
use App\Models\Order;
use App\Models\PackagingType;
use App\Services\Payments\PaymentGatewayManager;
use App\Support\Phone;
use App\Support\SessionCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        private SessionCart $cart,
        private CreateOrderAction $createOrder,
        private PaymentGatewayManager $gateways,
    ) {}

    public function create(): View|RedirectResponse
    {
        $quote = $this->cart->quote();

        if ($quote->lines === []) {
            return redirect()->route('cart.show')->with('error', __('store.cart.empty'));
        }

        session()->forget('checkout.inflight');
        session(['checkout.nonce' => session('checkout.nonce', bin2hex(random_bytes(16)))]);

        return view('store.checkout', [
            'quote' => $quote,
            'cities' => City::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'gateways' => $this->gateways->enabled(),
            'orderPackaging' => PackagingType::query()->where('scope', 'order')->where('is_active', true)->get(),
            'nonce' => session('checkout.nonce'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->cart->quote()->lines === []) {
            $reference = (string) session('checkout.reference');

            return $reference !== ''
                ? redirect()->route('checkout.thanks', ['reference' => $reference])
                : redirect()->route('cart.show')->with('error', __('store.cart.empty'));
        }

        $nonce = (string) $request->input('nonce');
        $sessionNonce = (string) session('checkout.nonce');

        if (session('checkout.inflight')) {
            return redirect()->route('checkout.create')->with('error', __('store.checkout.duplicate'));
        }

        if ($sessionNonce !== '' && ($nonce === '' || ! hash_equals($sessionNonce, $nonce))) {
            $this->releaseCheckout();

            return redirect()->route('checkout.create')->withInput()->with('error', __('store.checkout.expired'));
        }

        $enabled = array_map(fn ($gateway) => $gateway->id(), $this->gateways->enabled());

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:190'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:500'],
            'payment_gateway' => ['required', 'string', 'in:'.implode(',', $enabled)],
            'order_packaging_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        session(['checkout.inflight' => true]);

        try {
            $quote = $this->cart->quote((int) $data['city_id'], $data['order_packaging_id'] ?? null);
            $lines = array_map(fn ($line) => $line->raw + ['key' => $line->key], $quote->lines);

            $order = $this->createOrder->execute(new CreateOrderPayload(
                userId: $request->user()?->id,
                customerName: $data['customer_name'],
                customerEmail: $data['customer_email'] ?? $request->user()?->email,
                customerPhone: Phone::normalize($data['customer_phone']),
                cityId: (int) $data['city_id'],
                address: $data['address'],
                paymentGateway: $data['payment_gateway'],
                lines: $lines,
                couponCode: $this->cart->couponCode(),
                orderPackagingId: $data['order_packaging_id'] ?? null,
                notes: $data['notes'] ?? null,
                locale: app()->getLocale(),
            ));
        } catch (CartException $exception) {
            $this->releaseCheckout();

            return redirect()->route('checkout.create')->withInput()->with('error', __($exception->errorKey));
        } catch (Throwable $exception) {
            $this->releaseCheckout();
            report($exception);

            return redirect()->route('checkout.create')->withInput()->with('error', __('store.checkout.failed'));
        }

        $this->cart->clear();
        session()->forget('checkout.inflight');
        session()->forget('checkout.nonce');
        session(['checkout.reference' => $order->reference]);
        event(new OrderCreated($order));

        if ($order->payment_gateway === 'paymob_card') {
            $initiation = $this->gateways->get('paymob_card')->initiate($order);

            if ($initiation->redirectUrl) {
                return redirect()->away($initiation->redirectUrl);
            }

            return redirect()->route('checkout.thanks', ['reference' => $order->reference])
                ->with('payment_error', $initiation->message ?? __('store.payment.gateway_failed'));
        }

        return redirect()->route('checkout.thanks', ['reference' => $order->reference]);
    }

    private function releaseCheckout(): void
    {
        session()->forget('checkout.inflight');
        session(['checkout.nonce' => bin2hex(random_bytes(16))]);
    }

    public function thanks(Request $request): View
    {
        $reference = (string) $request->query('reference', session('checkout.reference'));
        $order = $reference !== '' ? Order::query()->where('reference', $reference)->first() : null;

        return view('store.thanks', ['order' => $order]);
    }

    public function retry(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($request, $order);

        abort_unless($order->payment_gateway === 'paymob_card', 404);
        abort_unless(in_array($order->payment_status, ['pending', 'failed'], true), 422);

        $initiation = $this->gateways->get('paymob_card')->initiate($order);

        if ($initiation->redirectUrl) {
            return redirect()->away($initiation->redirectUrl);
        }

        return redirect()->route('checkout.thanks', ['reference' => $order->reference])
            ->with('payment_error', $initiation->message);
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        if ($request->user() && $request->user()->id === $order->user_id) {
            return;
        }

        if (session('checkout.reference') === $order->reference) {
            return;
        }

        abort(403);
    }
}
