<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create sectors table
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Add sector_id to zones table and make district_id nullable (will be removed)
        Schema::table('zones', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->after('district_id')->constrained()->cascadeOnDelete();
        });

        // Add sector_id to lands table
        Schema::table('lands', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->after('district_id')->constrained()->nullOnDelete();
        });

        // Migrate existing zones: create sectors from districts and link zones to them
        $zones = DB::table('zones')->get();
        foreach ($zones as $zone) {
            // Create a default sector for each district if not exists
            $sector = DB::table('sectors')
                ->where('district_id', $zone->district_id)
                ->where('name', 'قطاع افتراضي')
                ->first();

            if (!$sector) {
                $sectorId = DB::table('sectors')->insertGetId([
                    'district_id' => $zone->district_id,
                    'name' => 'قطاع افتراضي',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $sectorId = $sector->id;
            }

            // Update zone with sector_id
            DB::table('zones')
                ->where('id', $zone->id)
                ->update(['sector_id' => $sectorId]);
        }

        // Make sector_id required and remove district_id from zones
        Schema::table('zones', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropColumn('district_id');
        });
    }

    public function down(): void
    {
        // Add district_id back to zones
        Schema::table('zones', function (Blueprint $table) {
            $table->foreignId('district_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // Migrate sector relationships back to district
        $zones = DB::table('zones')->get();
        foreach ($zones as $zone) {
            $sector = DB::table('sectors')->where('id', $zone->sector_id)->first();
            if ($sector) {
                DB::table('zones')
                    ->where('id', $zone->id)
                    ->update(['district_id' => $sector->district_id]);
            }
        }

        // Remove sector_id from zones
        Schema::table('zones', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });

        // Remove sector_id from lands
        Schema::table('lands', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });

        // Drop sectors table
        Schema::dropIfExists('sectors');
    }
};
