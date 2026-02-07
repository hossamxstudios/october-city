<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $items = [
                ['name' => 'رخصة', 'description' => 'رخص البناء والتشغيل', 'order' => 1],
                ['name' => 'تأمينات اجتماعية', 'description' => 'مستندات التأمينات الاجتماعية', 'order' => 2],
                ['name' => 'بيان صلاحية الموقع', 'description' => 'بيانات صلاحية المواقع', 'order' => 3],
                ['name' => 'إخطار تخصيص قطعة قطعه', 'description' => 'إخطارات تخصيص القطع', 'order' => 4],
                ['name' => 'محضر استلام موقع', 'description' => 'محاضر استلام المواقع', 'order' => 5],
                ['name' => 'الحماية المدنية', 'description' => 'مستندات الحماية المدنية', 'order' => 6],
                ['name' => 'محضر معاينة', 'description' => 'محاضر المعاينة', 'order' => 7],
                ['name' => 'نتيجة معاينة', 'description' => 'نتائج المعاينات', 'order' => 8],
                ['name' => 'طلب الحصول على خدمة', 'description' => 'طلبات الحصول على الخدمات', 'order' => 9],
                ['name' => 'طلب تراخيص', 'description' => 'طلبات التراخيص', 'order' => 10],
                ['name' => 'تقرير هندسي استشاري - جوانب الحفر منشأة', 'description' => 'تقرير هندسي استشاري - جوانب الحفر منشأة', 'order' => 11],
                ['name' => 'توكيل (عام / خاص)', 'description' => 'التوكيلات العامة والخاصة', 'order' => 12],
                ['name' => 'وثيقة التأمين', 'description' => 'وثائق التأمين', 'order' => 13],
                ['name' => 'شهادة مهندس استشاري', 'description' => 'شهادات المهندسين الاستشاريين', 'order' => 14],
                ['name' => 'النقابة العامة للمهندسين', 'description' => 'مستندات النقابة العامة للمهندسين', 'order' => 15],
                ['name' => 'سجل قيد الرسومات', 'description' => 'سجلات قيد الرسومات', 'order' => 16],
                ['name' => 'جدول حصر المهندسين', 'description' => 'جداول حصر المهندسين', 'order' => 17],
                ['name' => 'النوته الحسابية', 'description' => 'النوتة الحسابية', 'order' => 18],
                ['name' => 'تقرير فني', 'description' => 'التقارير الفنية', 'order' => 19],
                ['name' => 'سجل تجاري', 'description' => 'السجلات التجارية', 'order' => 20],
                ['name' => 'رسم فحص', 'description' => 'رسم فحص', 'order' => 21],
                ['name' => 'رسم التصالح', 'description' => 'رسم التصالح', 'order' => 22],
                ['name' => 'ايصال استلام نقدية', 'description' => 'ايصال استلام نقدية', 'order' => 23],
                ['name' => 'بطايق شخصية', 'description' => 'البطاقات الشخصية', 'order' => 24],
                ['name' => 'كارنية نقابة المهندسين', 'description' => 'كارنيهات نقابة المهندسين', 'order' => 25],
                ['name' => 'بطاقة ضريبة', 'description' => 'البطاقات الضريبية', 'order' => 26],
                ['name' => 'إقرار ضريبي', 'description' => 'الإقرارات الضريبية', 'order' => 27],
                ['name' => 'إقرار وتعهد وتفويض', 'description' => 'إقرارات وتعهدات وتفويضات', 'order' => 28],
                ['name' => 'خطابات', 'description' => 'الخطابات الرسمية', 'order' => 29],
                ['name' => 'ورق بخط اليد', 'description' => 'أوراق مكتوبة بخط اليد', 'order' => 30],
                ['name' => 'جدول زمني', 'description' => 'جدول زمني', 'order' => 31],
                ['name' => 'دعم صرف', 'description' => 'دعم صرف', 'order' => 32],
                ['name' => 'طلب تصالح', 'description' => 'طلب تصالح', 'order' => 33],
                ['name' => 'اخطار طلب تصالح', 'description' => 'اخطار طلب تصالح', 'order' => 34],
                ['name' => 'تقرير بمساحة المخالفة', 'description' => 'تقرير بمساحة المخالفة', 'order' => 35],
                ['name' => 'تقرير الامانة الفنية', 'description' => 'تقرير الامانة الفنية', 'order' => 36],
                ['name' => 'صورة المنشآة', 'description' => 'صورة المنشآة', 'order' => 37],
                ['name' => 'ايصالات', 'description' => 'ايصالات', 'order' => 38],
                ['name' => 'قرار وزاري', 'description' => 'قرار وزاري', 'order' => 39],
            ];

            foreach ($items as $item) {
                Item::firstOrCreate(
                    ['name' => $item['name']],
                    $item
                );
            }

            DB::commit();

            $this->command->info('Items seeded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemSeeder failed: '.$e->getMessage());
            $this->command->error('Failed to seed items: '.$e->getMessage());
        }
    }
}
