# Atlas Market

Bilingual coffee store (English and Arabic, RTL), prices in EGP, Egypt delivery cities, cash on delivery and Paymob Visa. Staff work in `/admin`. Customers work in `/my`.

Money is stored with BCMath at 4 decimal places and shown as 2 decimals plus EGP. A Visa order is marked paid only after the Paymob webhook signature is checked. The store never keeps a card number. Theme colors come from the database as CSS variables.

## Shop

| Path | What it does |
| --- | --- |
| `/` | Home: hero, banners, categories, featured products, SEO from the database |
| `/shop` | Catalog, search, category and unit filters, stock state |
| `/categories/{category}` | Category page, empty state when nothing is listed |
| `/products/{product}` | Product, unit, services, packaging, gallery |
| `/bundles` and `/bundles/{bundle}` | Bundles, cover and gallery, subscription note |
| `/cart` | Lines with product photos, quantity, remove, coupon, totals |
| `/checkout` | Name, email, phone, city, address, packaging, payment. Shipping updates when the city changes |
| `/checkout/thanks` | Confirmation. Card return does not mark the order paid |
| `/track` | Order tracking by reference |
| `/login`, `/register`, forgot and reset password, phone OTP | Customer auth |
| `/my` | Customer panel: orders, invoice, profile |
| `/staff-guide.pdf` | Staff guide PDF. Requires a logged-in user who can view the guide |
| `/invoices/{order}` | Invoice PDF for a signed link, the owner, or an admin who can view the order |

Footer social icons are edited in admin. Only `http` and `https` links are shown. The footer stays at the bottom of short pages. Dark mode uses light text on the dark surfaces. A design asset with the key wallpaper is the site background.

Cart quantity is shown without trailing zeros (`1`, not `1.0000`). Coupon apply, quantity update, and remove return to the cart page. Delivery on the cart is added after a city is chosen. Packaging is its own line.

Checkout saves the order before any email. If the mailbox fails, the order still exists and `mail_status` shows the failure. Staff can generate the PDF again and send it to the customer or to the team. `php artisan orders:notify {order}` sends from the command line.

## Admin

Login at `/admin`. Roles: `super_admin`, `orders_manager`, `catalog_manager`, `content_manager`, `design_manager`, `analytics_viewer`, `finance_viewer`. A staff user needs both a role and admin access.

| Area | Path | What it does |
| --- | --- | --- |
| Dashboard | `/admin` | Period cards, revenue chart, status, payment, top products, cities, product views. Latest orders is a bar chart of totals |
| Sales | `/admin/sales` | Revenue, products, cities, payment methods, promo codes used. Cancelled orders are excluded from revenue |
| Customers and traffic | `/admin/traffic` | Visits, pages, hour of day, weekday, place, device |
| Customer analysis | `/admin/customer-analysis` | Per customer: visit time, purchase time, promo code, place, device, orders |
| Staff guide | `/admin/guide` | This handbook on screen, plus Download PDF |
| Orders | `/admin/orders` | Lines, coupon, packaging, shipping, mail status, invoice. Create a manual order for a customer, including internal products. Cancel restores stock once |
| Users | `/admin/users` | Phone is required. Name, email, password, and address are optional. Orders of that customer are listed |
| Products | `/admin/products` | `is_internal`: inactive in the shop, available in admin pickers, bundles, and manual orders |
| Bundles | `/admin/bundles` | Cover upload and gallery |
| Categories, services, packaging, cities, coupons | catalog group | Shop structure and offers |
| Banners, content strings, SEO | content group | Home and metadata |
| Design assets, colors | design group | Logo, dark logo, icon, home picture, share image, empty cart, empty shop, sign-in picture, footer mark, wallpaper |
| Social links | design / content | Footer icons |
| Signup coupon | `/admin` signup offer page | Popup delay and the coupon that is emailed |
| Payment attempts | payments resource | Paymob records. No card PAN |

## What was added

- Coffee catalog art under `storage/app/public` (products, banners, category, design). Git keeps those folders.
- Cart line photos. Checkout shipping fee updates live from the selected city.
- Footer social links, editable in admin, http and https only.
- Sticky footer on short pages.
- Dark mode text stays light. Buttons, hero, and footer keep cream text. Gold kickers stay gold.
- Order is stored before email. Mail failure is a flag. Staff resend the PDF.
- Internal products. Manual customers. Manual orders.
- Bundle cover and gallery.
- Dashboard charts and a latest-orders bar chart.
- Sales analytics page, including promo codes.
- Customer analysis: when they visited, when they bought, which promo code.
- Wallpaper design asset.
- Mobile header, cart controls, coupon button, login form, and search field spacing.
- Empty cart, empty shop, and sign-in pictures no longer print file names. Admin lists those pictures with normal words.
- Staff guide layout and PDF download.
- phpMyAdmin import file at `database/phpmyadmin-import.sql` (shop tables plus Laravel `sessions`, `cache`, and `jobs`).

## Commands

- `php artisan orders:notify {order}` sends the order email and PDF.
- `php artisan store:process-subscriptions` processes due subscriptions.
- `php artisan migrate` creates tables. Tests use sqlite memory. Local shop data has been kept in MySQL database `e_comm`.

## Deploy notes

The store repository is `abdawyy/e_comm_2`. `/home/al-dawy.com/public_html` is a different project (`veg_fruits`). The coffee shop on the server is the directory whose remote is `e_comm_2`. After `git pull`, run `npm run build`, then `php artisan view:clear` and `php artisan config:clear`. Import `database/phpmyadmin-import.sql` in phpMyAdmin while the shop database is selected when the tables are missing.

Do not commit `.env`. Mail and database passwords stay there.
