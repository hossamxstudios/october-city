<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GovernorateSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $data = [
                'الجيزة' => ['اكتوبر'],
            ];
            foreach ($data as $governorateName => $cities) {
                $governorate = Governorate::firstOrCreate(['name' => $governorateName]);

                foreach ($cities as $cityName) {
                    City::firstOrCreate([
                        'governorate_id' => $governorate->id,
                        'name' => $cityName,
                    ]);
                }
            }
            DB::commit();
            $this->command->info('Governorates and cities seeded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GovernorateSeeder failed: ' . $e->getMessage());
            $this->command->error('Failed to seed governorates: ' . $e->getMessage());
        }
    }
}
