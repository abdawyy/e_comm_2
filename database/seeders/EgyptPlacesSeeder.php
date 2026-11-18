<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EgyptPlacesSeeder extends Seeder
{
    public function run(): void
    {
        $this->permissions();

        $sort = 1;

        foreach ($this->catalog() as $row) {
            $city = City::query()->where('name->en', $row['en'])->first();

            if (! $city) {
                $city = City::query()->create([
                    'name' => ['en' => $row['en'], 'ar' => $row['ar']],
                    'shipping_fee' => $row['fee'],
                    'is_active' => true,
                    'sort_order' => $sort,
                ]);
            }

            $placeSort = 1;

            foreach ($row['places'] as $place) {
                $key = Str::slug($row['en'].'-'.$place[0]);
                Area::query()->firstOrCreate(['key' => $key], [
                    'city_id' => $city->id,
                    'name' => ['en' => $place[0], 'ar' => $place[1]],
                    'shipping_fee' => $place[2] ?? $row['fee'],
                    'is_active' => true,
                    'sort_order' => $placeSort,
                ]);
                $placeSort++;
            }

            $sort++;
        }
    }

    private function permissions(): void
    {
        $abilities = [
            'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore',
            'forceDelete', 'forceDeleteAny', 'restoreAny', 'replicate', 'reorder',
        ];
        $names = [];

        foreach ($abilities as $ability) {
            $name = str($ability)->studly()->toString().':Area';
            Permission::findOrCreate($name, 'web');
            $names[] = $name;
        }

        foreach (['super_admin', 'catalog_manager'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->where('guard_name', 'web')->first();
            $role?->givePermissionTo($names);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Governorates and the cities or districts that belong to them.
     * A place fee overrides the governorate fee. Sheraton is 75 and Rehab is 100.
     *
     * @return array<int, array{en: string, ar: string, fee: string, places: array<int, array{0: string, 1: string, 2?: string}>}>
     */
    private function catalog(): array
    {
        $place = fn (string $en, string $ar, ?string $fee = null): array => $fee === null ? [$en, $ar] : [$en, $ar, $fee];

        return [
            ['en' => 'Cairo', 'ar' => 'القاهرة', 'fee' => '75.0000', 'places' => [
                $place('Sheraton', 'شيراتون', '75.0000'),
                $place('Rehab', 'الرحاب', '100.0000'),
                $place('Heliopolis', 'مصر الجديدة'),
                $place('Nasr City', 'مدينة نصر'),
                $place('Nozha', 'النزهة'),
                $place('Maadi', 'المعادي'),
                $place('Zahraa El Maadi', 'زهراء المعادي'),
                $place('New Cairo', 'القاهرة الجديدة'),
                $place('Fifth Settlement', 'التجمع الخامس', '100.0000'),
                $place('Madinaty', 'مدينتي', '100.0000'),
                $place('Katameya', 'القطامية'),
                $place('El Shorouk', 'الشروق', '100.0000'),
                $place('Badr', 'بدر', '100.0000'),
                $place('Downtown Cairo', 'وسط البلد'),
                $place('Garden City', 'جاردن سيتي'),
                $place('Zamalek', 'الزمالك'),
                $place('Manial', 'المنيل'),
                $place('Old Cairo', 'مصر القديمة'),
                $place('Sayeda Zeinab', 'السيدة زينب'),
                $place('Abdeen', 'عابدين'),
                $place('Bab El Shaaria', 'باب الشعرية'),
                $place('El Gamaliya', 'الجمالية'),
                $place('El Muski', 'الموسكي'),
                $place('Boulak', 'بولاق'),
                $place('El Wayli', 'الوايلي'),
                $place('El Zaher', 'الظاهر'),
                $place('Hadayek El Kobba', 'حدائق القبة'),
                $place('Zeitoun', 'الزيتون'),
                $place('Ain Shams', 'عين شمس'),
                $place('Matareya', 'المطرية'),
                $place('El Marg', 'المرج'),
                $place('El Salam', 'السلام'),
                $place('Shubra', 'شبرا'),
                $place('Rod El Farag', 'روض الفرج'),
                $place('El Sahel', 'الساحل'),
                $place('Mokattam', 'المقطم'),
                $place('El Khalifa', 'الخليفة'),
                $place('El Basatin', 'البساتين'),
                $place('Dar El Salam', 'دار السلام'),
                $place('Helwan', 'حلوان'),
                $place('15th of May', '15 مايو'),
                $place('El Tebbin', 'التبين'),
                $place('Tura', 'طرة'),
            ]],
            ['en' => 'Giza', 'ar' => 'الجيزة', 'fee' => '80.0000', 'places' => [
                $place('Dokki', 'الدقي'),
                $place('Mohandessin', 'المهندسين'),
                $place('Agouza', 'العجوزة'),
                $place('Giza Square', 'ميدان الجيزة'),
                $place('Haram', 'الهرم'),
                $place('Faisal', 'فيصل'),
                $place('Imbaba', 'إمبابة'),
                $place('Bulaq El Dakrour', 'بولاق الدكرور'),
                $place('Omraniya', 'العمرانية'),
                $place('Warraq', 'الوراق'),
                $place('6th of October', '6 أكتوبر'),
                $place('Sheikh Zayed', 'الشيخ زايد'),
                $place('Hadayek October', 'حدائق أكتوبر'),
                $place('Hawamdiya', 'الحوامدية'),
                $place('Kerdasa', 'كرداسة'),
                $place('Abu Nomros', 'أبو النمرس'),
                $place('Badrashein', 'البدرشين'),
                $place('El Saff', 'الصف'),
                $place('Atfih', 'أطفيح'),
                $place('El Ayyat', 'العياط'),
                $place('Osim', 'أوسيم'),
                $place('Manshiyet El Qanater', 'منشأة القناطر'),
            ]],
            ['en' => 'Qalyubia', 'ar' => 'القليوبية', 'fee' => '90.0000', 'places' => [
                $place('Banha', 'بنها'),
                $place('Qalyub', 'قليوب'),
                $place('Shubra El Kheima', 'شبرا الخيمة'),
                $place('Obour', 'العبور'),
                $place('Khanka', 'الخانكة'),
                $place('Qanater Khairiya', 'القناطر الخيرية'),
                $place('Kafr Shukr', 'كفر شكر'),
                $place('Tukh', 'طوخ'),
                $place('Khusus', 'الخصوص'),
                $place('Shibin El Qanater', 'شبين القناطر'),
            ]],
            ['en' => 'Alexandria', 'ar' => 'الإسكندرية', 'fee' => '110.0000', 'places' => [
                $place('Montaza', 'المنتزه'),
                $place('East Alexandria', 'شرق الإسكندرية'),
                $place('Middle Alexandria', 'وسط الإسكندرية'),
                $place('West Alexandria', 'غرب الإسكندرية'),
                $place('Gomrok', 'الجمرك'),
                $place('Agami', 'العجمي'),
                $place('Amreya', 'العامرية'),
                $place('Borg El Arab', 'برج العرب'),
                $place('Miami', 'ميامي'),
                $place('Sidi Bishr', 'سيدي بشر'),
                $place('Mandara', 'المندرة'),
                $place('Asafra', 'العصافرة'),
                $place('Sidi Gaber', 'سيدي جابر'),
                $place('Stanley', 'ستانلي'),
                $place('Smouha', 'سموحة'),
                $place('Gleem', 'جليم'),
                $place('Roushdy', 'رشدي'),
                $place('Ibrahimia', 'الإبراهيمية'),
                $place('Sporting', 'سبورتنج'),
                $place('Cleopatra', 'كليوباترا'),
                $place('Abu Qir', 'أبو قير'),
            ]],
            ['en' => 'Sharqia', 'ar' => 'الشرقية', 'fee' => '100.0000', 'places' => [
                $place('Zagazig', 'الزقازيق'), $place('10th of Ramadan', 'العاشر من رمضان'), $place('Minya El Qamh', 'منيا القمح'),
                $place('Belbeis', 'بلبيس'), $place('Abu Hammad', 'أبو حماد'), $place('Abu Kebir', 'أبو كبير'),
                $place('Faqous', 'فاقوس'), $place('Hihya', 'ههيا'), $place('Kafr Saqr', 'كفر صقر'),
                $place('Awlad Saqr', 'أولاد صقر'), $place('Husseiniya', 'الحسينية'), $place('Diyarb Negm', 'ديرب نجم'),
                $place('Mashtool El Souk', 'مشتول السوق'), $place('El Ibrahimiya', 'الإبراهيمية'), $place('El Qanayat', 'القنايات'),
            ]],
            ['en' => 'Dakahlia', 'ar' => 'الدقهلية', 'fee' => '100.0000', 'places' => [
                $place('Mansoura', 'المنصورة'), $place('Talkha', 'طلخا'), $place('Mit Ghamr', 'ميت غمر'),
                $place('Aga', 'أجا'), $place('Sinbillawain', 'السنبلاوين'), $place('Sherbin', 'شربين'),
                $place('Belqas', 'بلقاس'), $place('Dekernes', 'دكرنس'), $place('Manzala', 'المنزلة'),
                $place('Matareya Dakahlia', 'المطرية'), $place('Gamasa', 'جمصة'), $place('Nabaroh', 'نبروه'),
            ]],
            ['en' => 'Beheira', 'ar' => 'البحيرة', 'fee' => '100.0000', 'places' => [
                $place('Damanhour', 'دمنهور'), $place('Kafr El Dawwar', 'كفر الدوار'), $place('Rashid', 'رشيد'),
                $place('Edku', 'إدكو'), $place('Abu El Matamir', 'أبو المطامير'), $place('Abu Hummus', 'أبو حمص'),
                $place('Delengat', 'الدلنجات'), $place('Mahmoudiya', 'المحمودية'), $place('Itay El Baroud', 'إيتاي البارود'),
                $place('Kom Hamada', 'كوم حمادة'), $place('Hosh Issa', 'حوش عيسى'), $place('Shubrakhit', 'شبراخيت'),
                $place('Rahmaniya', 'الرحمانية'), $place('Nubariya', 'النوبارية'), $place('Wadi El Natrun', 'وادي النطرون'),
            ]],
            ['en' => 'Gharbia', 'ar' => 'الغربية', 'fee' => '100.0000', 'places' => [
                $place('Tanta', 'طنطا'), $place('El Mahalla El Kubra', 'المحلة الكبرى'), $place('Kafr El Zayat', 'كفر الزيات'),
                $place('Zefta', 'زفتى'), $place('Samannoud', 'سمنود'), $place('Basyoun', 'بسيون'),
                $place('Qutour', 'قطور'), $place('Santa', 'السنطة'),
            ]],
            ['en' => 'Monufia', 'ar' => 'المنوفية', 'fee' => '100.0000', 'places' => [
                $place('Shibin El Kom', 'شبين الكوم'), $place('Menouf', 'منوف'), $place('Ashmoun', 'أشمون'),
                $place('Quesna', 'قويسنا'), $place('Bagour', 'الباجور'), $place('Tala', 'تلا'),
                $place('El Shohada', 'الشهداء'), $place('Sadat City', 'مدينة السادات'), $place('Birket El Sab', 'بركة السبع'),
            ]],
            ['en' => 'Kafr El Sheikh', 'ar' => 'كفر الشيخ', 'fee' => '110.0000', 'places' => [
                $place('Kafr El Sheikh', 'كفر الشيخ'), $place('Desouk', 'دسوق'), $place('Fuwwah', 'فوه'),
                $place('Metoubes', 'مطوبس'), $place('Baltim', 'بلطيم'), $place('El Hamoul', 'الحامول'),
                $place('Biyala', 'بيلا'), $place('Sidi Salem', 'سيدي سالم'), $place('Qillin', 'قلين'), $place('Riyadh', 'الرياض'),
            ]],
            ['en' => 'Damietta', 'ar' => 'دمياط', 'fee' => '110.0000', 'places' => [
                $place('Damietta', 'دمياط'), $place('New Damietta', 'دمياط الجديدة'), $place('Ras El Bar', 'رأس البر'),
                $place('Faraskur', 'فارسكور'), $place('Zarqa', 'الزرقا'), $place('Kafr Saad', 'كفر سعد'), $place('Kafr El Battikh', 'كفر البطيخ'),
            ]],
            ['en' => 'Port Said', 'ar' => 'بورسعيد', 'fee' => '110.0000', 'places' => [
                $place('Port Said', 'بورسعيد'), $place('Port Fouad', 'بورفؤاد'), $place('El Arab', 'العرب'),
                $place('El Zohour', 'الزهور'), $place('El Sharq', 'الشرق'), $place('El Manakh', 'المناخ'), $place('El Dawahy', 'الضواحي'),
            ]],
            ['en' => 'Ismailia', 'ar' => 'الإسماعيلية', 'fee' => '110.0000', 'places' => [
                $place('Ismailia', 'الإسماعيلية'), $place('Fayed', 'فايد'), $place('Qantara Sharq', 'القنطرة شرق'),
                $place('Qantara Gharb', 'القنطرة غرب'), $place('Tell El Kebir', 'التل الكبير'), $place('Abu Suwir', 'أبو صوير'), $place('Qassasin', 'القصاصين'),
            ]],
            ['en' => 'Suez', 'ar' => 'السويس', 'fee' => '110.0000', 'places' => [
                $place('Suez', 'السويس'), $place('Arbaeen', 'الأربعين'), $place('Attaka', 'عتاقة'),
                $place('Faisal Suez', 'فيصل'), $place('Ganayen', 'الجناين'), $place('Ain Sokhna', 'العين السخنة'),
            ]],
            ['en' => 'North Sinai', 'ar' => 'شمال سيناء', 'fee' => '160.0000', 'places' => [
                $place('Arish', 'العريش'), $place('Sheikh Zuweid', 'الشيخ زويد'), $place('Rafah', 'رفح'),
                $place('Bir El Abd', 'بئر العبد'), $place('Hasana', 'الحسنة'), $place('Nakhl', 'نخل'),
            ]],
            ['en' => 'South Sinai', 'ar' => 'جنوب سيناء', 'fee' => '160.0000', 'places' => [
                $place('El Tor', 'الطور'), $place('Sharm El Sheikh', 'شرم الشيخ'), $place('Dahab', 'دهب'),
                $place('Nuweiba', 'نويبع'), $place('Taba', 'طابا'), $place('Saint Catherine', 'سانت كاترين'),
                $place('Ras Sidr', 'رأس سدر'), $place('Abu Zenima', 'أبو زنيمة'), $place('Abu Rudeis', 'أبو رديس'),
            ]],
            ['en' => 'Red Sea', 'ar' => 'البحر الأحمر', 'fee' => '160.0000', 'places' => [
                $place('Hurghada', 'الغردقة'), $place('El Gouna', 'الجونة'), $place('Safaga', 'سفاجا'),
                $place('Quseer', 'القصير'), $place('Marsa Alam', 'مرسى علم'), $place('Ras Gharib', 'رأس غارب'),
                $place('Shalateen', 'الشلاتين'), $place('Halayeb', 'حلايب'),
            ]],
            ['en' => 'Fayoum', 'ar' => 'الفيوم', 'fee' => '120.0000', 'places' => [
                $place('Fayoum', 'الفيوم'), $place('Sinnuris', 'سنورس'), $place('Tamiya', 'طامية'),
                $place('Ibshaway', 'إبشواي'), $place('Itsa', 'إطسا'), $place('Yousef El Seddik', 'يوسف الصديق'), $place('New Fayoum', 'الفيوم الجديدة'),
            ]],
            ['en' => 'Beni Suef', 'ar' => 'بني سويف', 'fee' => '120.0000', 'places' => [
                $place('Beni Suef', 'بني سويف'), $place('New Beni Suef', 'بني سويف الجديدة'), $place('El Wasta', 'الواسطى'),
                $place('Nasser', 'ناصر'), $place('Ihnasia', 'إهناسيا'), $place('Biba', 'ببا'), $place('Samasta', 'سمسطا'), $place('El Fashn', 'الفشن'),
            ]],
            ['en' => 'Minya', 'ar' => 'المنيا', 'fee' => '140.0000', 'places' => [
                $place('Minya', 'المنيا'), $place('New Minya', 'المنيا الجديدة'), $place('Samalout', 'سمالوط'),
                $place('Matay', 'مطاي'), $place('Beni Mazar', 'بني مزار'), $place('Maghagha', 'مغاغة'),
                $place('Adwa', 'العدوة'), $place('Abu Qurqas', 'أبو قرقاص'), $place('Mallawi', 'ملوي'), $place('Deir Mawas', 'دير مواس'),
            ]],
            ['en' => 'Asyut', 'ar' => 'أسيوط', 'fee' => '140.0000', 'places' => [
                $place('Asyut', 'أسيوط'), $place('New Asyut', 'أسيوط الجديدة'), $place('Dayrout', 'ديروط'),
                $place('El Qusiya', 'القوصية'), $place('Manfalut', 'منفلوط'), $place('Abnub', 'أبنوب'),
                $place('El Fath', 'الفتح'), $place('Abu Tig', 'أبو تيج'), $place('Sahel Selim', 'ساحل سليم'),
                $place('El Badari', 'البداري'), $place('Sidfa', 'صدفا'), $place('El Ghanayem', 'الغنايم'),
            ]],
            ['en' => 'Sohag', 'ar' => 'سوهاج', 'fee' => '140.0000', 'places' => [
                $place('Sohag', 'سوهاج'), $place('New Sohag', 'سوهاج الجديدة'), $place('Akhmim', 'أخميم'),
                $place('El Balyana', 'البلينا'), $place('El Maragha', 'المراغة'), $place('Girga', 'جرجا'),
                $place('Juhayna', 'جهينة'), $place('Dar El Salam Sohag', 'دار السلام'), $place('Saqalta', 'ساقلتة'),
                $place('Tama', 'طما'), $place('Tahta', 'طهطا'), $place('El Monsha', 'المنشأة'),
            ]],
            ['en' => 'Qena', 'ar' => 'قنا', 'fee' => '150.0000', 'places' => [
                $place('Qena', 'قنا'), $place('New Qena', 'قنا الجديدة'), $place('Abu Tesht', 'أبو تشت'),
                $place('Farshut', 'فرشوط'), $place('Nag Hammadi', 'نجع حمادي'), $place('El Waqf', 'الوقف'),
                $place('Deshna', 'دشنا'), $place('Qift', 'قفط'), $place('Naqada', 'نقادة'), $place('Qus', 'قوص'),
            ]],
            ['en' => 'Luxor', 'ar' => 'الأقصر', 'fee' => '150.0000', 'places' => [
                $place('Luxor', 'الأقصر'), $place('New Luxor', 'الأقصر الجديدة'), $place('Esna', 'إسنا'),
                $place('Armant', 'أرمنت'), $place('El Tod', 'الطود'), $place('El Qurna', 'القرنة'), $place('El Bayadiya', 'البياضية'),
            ]],
            ['en' => 'Aswan', 'ar' => 'أسوان', 'fee' => '160.0000', 'places' => [
                $place('Aswan', 'أسوان'), $place('New Aswan', 'أسوان الجديدة'), $place('Kom Ombo', 'كوم أمبو'),
                $place('Daraw', 'دراو'), $place('Nasr El Nuba', 'نصر النوبة'), $place('Edfu', 'إدفو'),
                $place('Abu Simbel', 'أبو سمبل'),
            ]],
            ['en' => 'New Valley', 'ar' => 'الوادي الجديد', 'fee' => '180.0000', 'places' => [
                $place('Kharga', 'الخارجة'), $place('Dakhla', 'الداخلة'), $place('Farafra', 'الفرافرة'),
                $place('Baris', 'باريس'), $place('Balat', 'بلاط'), $place('Mut', 'موط'),
            ]],
            ['en' => 'Matrouh', 'ar' => 'مطروح', 'fee' => '160.0000', 'places' => [
                $place('Marsa Matrouh', 'مرسى مطروح'), $place('El Alamein', 'العلمين'), $place('El Hammam', 'الحمام'),
                $place('El Dabaa', 'الضبعة'), $place('Sidi Barrani', 'سيدي براني'), $place('Salloum', 'السلوم'),
                $place('Siwa', 'سيوة'), $place('Negila', 'النجيلة'),
            ]],
        ];
    }
}
