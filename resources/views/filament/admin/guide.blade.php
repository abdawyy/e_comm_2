<div class="prose max-w-none dark:prose-invert">
    <p><a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}">English</a> · <a href="{{ request()->fullUrlWithQuery(['locale' => 'ar']) }}">العربية</a></p>
    @if(app()->getLocale() === 'ar')
        <h2>دليل فريق العمل</h2>
        <p>يمكن تغيير اسم المتجر والشعار والصور والألوان من قسم التصميم دون إعادة نشر الكود. العملة الافتراضية جنيه مصري، واللغتان العربية والإنجليزية.</p>
        <ul>
            <li><strong>super_admin</strong>: كل الصلاحيات. وحده يمنح دور المشرف الأعلى أو يعدّل الأدوار.</li>
            <li><strong>orders_manager</strong>: إدارة الطلبات، عرض العملاء، ولوحة المبيعات.</li>
            <li><strong>catalog_manager</strong>: المنتجات والأقسام والحزم والخدمات والتغليف والمدن والكوبونات.</li>
            <li><strong>content_manager</strong>: البانرات ونصوص الموقع وإعدادات الظهور في محركات البحث.</li>
            <li><strong>design_manager</strong>: الصور وألوان الهوية والبانرات، دون تعديل الكتالوج.</li>
            <li><strong>analytics_viewer</strong>: لوحات المبيعات والزيارات ودليل الإدارة. بلا إنشاء أو تعديل أو حذف.</li>
            <li><strong>finance_viewer</strong>: عرض الطلبات ومحاولات الدفع فقط، بلا تعديل الحالة أو الكتالوج.</li>
        </ul>
        <p>الدفع عند الاستلام يبقى غير مدفوع حتى يحصّله الفريق. بطاقة فيزا عبر Paymob لا تُعلَّم مدفوعة إلا بعد تحقق توقيع الويب هوك على الخادم.</p>
    @else
        <h2>Staff guide</h2>
        <p>Change the brand name, logo, images, and colors under Design. The store updates on the next page load. Default currency is EGP. English and Arabic are both supported.</p>
        <ul>
            <li><strong>super_admin</strong>: every permission. Only this role may assign super admin or edit roles.</li>
            <li><strong>orders_manager</strong>: full orders, view customers, and the sales dashboard.</li>
            <li><strong>catalog_manager</strong>: products, categories, bundles, services, packaging, cities, and coupons.</li>
            <li><strong>content_manager</strong>: banners, CMS strings, and SEO.</li>
            <li><strong>design_manager</strong>: images, colors, and banners, without catalog access.</li>
            <li><strong>analytics_viewer</strong>: sales and traffic dashboards, visitors, and this guide. No create, update, or delete.</li>
            <li><strong>finance_viewer</strong>: view orders and payment attempts only. No status edits and no catalog access.</li>
        </ul>
        <p>Cash on delivery stays unpaid until staff collect it. Visa orders become paid only after the server verifies the Paymob webhook signature. The browser return page never marks an order paid.</p>
    @endif
</div>
