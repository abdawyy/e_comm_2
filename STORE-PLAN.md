# Atlas Market plan

The store can already browse, add a product, check out with cash, and run the admin catalog. The gaps are in the cart, the order desk, and what happens after the site is on a real server.

## Flows

The path that works is home or shop, product page, add to cart, checkout, cash on delivery, then the thanks page and the customer panel at `/my`.

These steps are incomplete:

1. **Cart.** Remove and Apply coupon post a normal form, but the server always answers with JSON. The customer sees a raw response instead of an updated cart. Quantity cannot be changed in the cart even though `PATCH /cart/items/{key}` exists.
2. **Totals.** The cart total has no delivery fee. The fee appears only after a city is chosen at checkout. A packaging fee is added into the total with no line of its own.
3. **After payment.** Card payment is coded, but Visa stays off until Paymob keys and a public webhook are set. The thanks and track pages print raw statuses such as `pending`.
4. **Account.** Store login goes to a Filament panel at `/my`. Guests who open `/my` get Filament’s login, not the store login.
5. **Admin orders.** Staff can change a status, but the order screen does not show lines, the coupon, packaging, the invoice, or a cancel that puts stock back. Cancelling from the customer side does restore stock.

## UI and UX

Home, shop, and the product page are the finished screens. Category, cart, checkout, track order, forgot-password, and bundles are plainer.

- Checkout, cart coupon, and track order use placeholders instead of labels.
- Out-of-stock products can still be added from a card. The product page blocks that.
- Category and bundles have no real empty state.
- Shop unit filters stay in English when the site is Arabic.
- Search stops at six results and does not offer “see all in the shop.”
- The signup popup and an unused Alpine timer both exist, so the offer can behave differently than expected.

## Bugs

- Cart remove and coupon always return JSON (`CartController`).
- A success flash is started on a nested Alpine component that does not have the toast function, so the message may never appear.
- Invoice download tries to match an email with the phone checker, so that path does not work. A guest who knows the order id and phone can still download the invoice.
- Status-change emails are queued. With `QUEUE_CONNECTION=database` and no worker, they sit in the jobs table.
- Mail is set to `log`, so invoices and admin notices stay in the log file.
- If the server `.env` omits `DB_CONNECTION`, Laravel falls back to SQLite. On eg-ss.com the SQLite file is owned by `root`, while the site runs as the `lsphp` user, so the database is read-only.
- Paymob treats a missing API key as “Visa off,” even though that key is not used in the payment request.
- New admin users can get a role and still be locked out, because `is_admin` is only set on edit.

## Enhancements

- Admin order page with lines, totals, invoice, and payment attempts.
- Admin cancel that restores stock.
- Low-stock notice.
- Product import (the importer exists and is not connected).
- One login for the shop and `/my`.
- Arabic labels for payment and order statuses.
- Real SMTP and a queue worker, or send status mail immediately.

## Execution plan

### 1. Make checkout trustworthy

Fix cart remove, coupon, and quantity so they return to the cart page. Show delivery on the cart as “chosen at checkout,” and show packaging as its own line. Add labels on checkout and track. Translate payment statuses.

This is the conversion work and should be done first.

### 2. Make the admin able to run an order

Show lines, address, coupon, packaging, and the invoice on the order screen. Cancelling restores stock. Add the invoice link. Set `is_admin` when a super admin creates a staff user.

### 3. Finish the storefront screens

Category and bundles empty states, stock state on cards, shop units in Arabic, search “view all,” and one signup-popup timer. Keep the store look on account pages where it is cheap to do.

### 4. Put the server on a real database and mail

Keep production on MySQL or PostgreSQL, not a SQLite file inside `public_html`. Set `DB_PASSWORD`, run migrations, and import `database/phpmyadmin-import.sql` if the current catalog should be loaded. Set SMTP. Either run `php artisan queue:work` or stop queueing status emails. Add Paymob keys only when the webhook URL is public.

### 5. Tighten access

Invoice download by signed link or logged-in owner. Stop exposing payment status by order reference alone. Add tests for cart page actions, admin cancel, and the invoice phone path.
