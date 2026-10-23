<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvoiceDownloadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_unauthorized_user_cannot_download_an_invoice(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $owner->id,
            'customer_email' => 'owner@example.test',
            'customer_phone' => '01099999999',
        ]);

        $this->actingAs($other)->get(route('invoices.download', $order))->assertForbidden();
        $this->actingAs($owner)->get(route('invoices.download', $order))->assertOk();
    }
}
