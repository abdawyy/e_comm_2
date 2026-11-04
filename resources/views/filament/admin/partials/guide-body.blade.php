@if($locale === 'ar')
    <section>
        <h2>المتجر</h2>
        <p>أطلس ماركت متجر قهوة ثنائي اللغة، بالجنيه المصري. الصفحة الرئيسية والمتجر وصفحة المنتج والسلة وإتمام الطلب وتتبع الطلب والحزم تعمل من الواجهة. حساب العميل على <strong>/my</strong> ولوحة الفريق على <strong>/admin</strong>.</p>
    </section>
    <section>
        <h2>الأدوار</h2>
        <ul>
            <li><strong>super_admin</strong>: كل الصلاحيات. وحده يمنح المشرف الأعلى أو يعدّل الأدوار.</li>
            <li><strong>orders_manager</strong>: الطلبات، عرض العملاء، ولوحة المبيعات.</li>
            <li><strong>catalog_manager</strong>: المنتجات والأقسام والحزم والخدمات والتغليف والمدن والكوبونات.</li>
            <li><strong>content_manager</strong>: البانرات ونصوص الموقع وتحسين محركات البحث.</li>
            <li><strong>design_manager</strong>: الصور والألوان والبانرات، دون الكتالوج.</li>
            <li><strong>analytics_viewer</strong>: المبيعات والزيارات وهذا الدليل. بلا إنشاء أو تعديل أو حذف.</li>
            <li><strong>finance_viewer</strong>: عرض الطلبات ومحاولات الدفع فقط.</li>
        </ul>
    </section>
    <section>
        <h2>لوحة التحكم</h2>
        <p>اختر الفترة: اليوم، أو 7 أو 30 أو 90 أو 365 يومًا. البطاقات تعرض الإيراد وعدد الطلبات والمتوسط والمعلّق. الرسوم: الإيراد، الحالة، طريقة الدفع، أكثر المنتجات، المدن، مشاهدات المنتجات. آخر الطلبات رسم أعمدة: طول العمود هو الإجمالي، والتسمية اسم العميل والحالة.</p>
    </section>
    <section>
        <h2>المبيعات والعملاء</h2>
        <p><strong>المبيعات</strong> في <strong>/admin/sales</strong> تعرض الإيراد والمنتجات والمدن والدفع وأكواد الخصم المستخدمة. الطلبات الملغاة لا تدخل في الإيراد.</p>
        <p><strong>تحليل العملاء</strong> يعرض وقت الزيارة، وقت الشراء، كود الخصم، المكان، والجهاز. <strong>العملاء والزيارات</strong> تعرض الصفحات والساعات والأيام.</p>
    </section>
    <section>
        <h2>الطلبات والعملاء اليدويون</h2>
        <ul>
            <li>احفظ الطلب أولًا. إرسال البريد لا يوقف إتمام الطلب. إذا فشل البريد تظهر الحالة على الطلب ويمكن إعادة إرسال PDF للعميل أو للفريق.</li>
            <li>إلغاء الطلب يعيد المخزون مرة واحدة.</li>
            <li>منتج داخلي: غير نشط في المتجر، ويظهر فقط عند اختيار المنتجات في الإدارة والحزم والطلب اليدوي.</li>
            <li>عميل يدوي: رقم الهاتف إلزامي. الاسم والبريد وكلمة المرور والعنوان اختيارية.</li>
            <li>طلب يدوي من الإدارة يُربط بعميل ويمكن أن يحتوي منتجات داخلية.</li>
        </ul>
    </section>
    <section>
        <h2>الدفع</h2>
        <p>الدفع عند الاستلام يبقى غير مدفوع حتى يحصّله الفريق. فيزا عبر Paymob لا تُعلَّم مدفوعة إلا بعد تحقق توقيع الويب هوك. صفحة العودة في المتصفح لا تغيّر حالة الدفع. المتجر لا يحفظ رقم البطاقة.</p>
    </section>
    <section>
        <h2>التصميم</h2>
        <p>من التصميم غيّر الشعار والصور والألوان. مفتاح <strong>Wallpaper</strong> يضع خلفية الموقع. الأسماء في القائمة كلمات مفهومة مثل سلة فارغة وصورة تسجيل الدخول، وليست أسماء ملفات. الوضع الداكن يجعل النص فاتحًا على الخلفية الداكنة.</p>
    </section>
@else
    <section>
        <h2>The store</h2>
        <p>Atlas Market is a bilingual coffee shop in Egyptian pounds. Home, shop, product, cart, checkout, order tracking, and bundles run on the storefront. Customers use <strong>/my</strong>. Staff use <strong>/admin</strong>.</p>
    </section>
    <section>
        <h2>Roles</h2>
        <ul>
            <li><strong>super_admin</strong>: every permission. Only this role may assign super admin or edit roles.</li>
            <li><strong>orders_manager</strong>: orders, customer view, and the sales dashboard.</li>
            <li><strong>catalog_manager</strong>: products, categories, bundles, services, packaging, cities, and coupons.</li>
            <li><strong>content_manager</strong>: banners, site text, and SEO.</li>
            <li><strong>design_manager</strong>: images, colors, and banners, without the catalog.</li>
            <li><strong>analytics_viewer</strong>: sales, traffic, and this guide. No create, update, or delete.</li>
            <li><strong>finance_viewer</strong>: view orders and payment attempts only.</li>
        </ul>
    </section>
    <section>
        <h2>Dashboard</h2>
        <p>Pick today, 7, 30, 90, or 365 days. The cards show revenue, order count, average order, and pending orders. Charts cover revenue, status, payment method, top products, cities, and product views. Latest orders is a bar chart: the bar length is the total, and the label is the customer and status.</p>
    </section>
    <section>
        <h2>Sales and customers</h2>
        <p><strong>Sales</strong> at <strong>/admin/sales</strong> shows revenue, products, cities, payment methods, and the promo codes used. Cancelled orders are left out of revenue.</p>
        <p><strong>Customer analysis</strong> shows when someone visited, when they bought, and which promo code was on the order, plus place and device. <strong>Customers and traffic</strong> shows pages, hours, and weekdays.</p>
    </section>
    <section>
        <h2>Orders and manual customers</h2>
        <ul>
            <li>The order is saved first. Email does not hold up checkout. If mail fails, the order shows that, and staff can send the PDF again to the customer or to the team.</li>
            <li>Cancelling an order restores stock once.</li>
            <li>An internal product is hidden from the shop and appears only in admin pickers, bundles, and manual orders.</li>
            <li>A manual customer needs a phone number. Name, email, password, and address are optional.</li>
            <li>A manual order is assigned to a customer and may include internal products.</li>
        </ul>
    </section>
    <section>
        <h2>Payments</h2>
        <p>Cash on delivery stays unpaid until staff collect it. Visa through Paymob is marked paid only after the server checks the webhook signature. The browser return page does not change payment status. The store does not keep the card number.</p>
    </section>
    <section>
        <h2>Design</h2>
        <p>Under Design, change the logo, pictures, and colors. The <strong>Wallpaper</strong> key sets the site background. List names are words such as Empty cart and Sign in picture, not file names. Dark mode keeps the words light on the dark background.</p>
    </section>
@endif
