<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create boxes table (new level in physical hierarchy: rooms → lanes → stands → racks → boxes)
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rack_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Add box_id to files table
        Schema::table('files', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->after('rack_id')->constrained()->nullOnDelete();
            $table->foreignId('box_id')->nullable()->after('rack_id')->constrained()->nullOnDelete();
        });

        // Add box_id to lands table
        Schema::table('lands', function (Blueprint $table) {
            $table->foreignId('box_id')->nullable()->after('rack_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropForeign(['box_id']);
            $table->dropColumn(['sector_id', 'box_id']);
        });

        Schema::table('lands', function (Blueprint $table) {
            $table->dropForeign(['box_id']);
            $table->dropColumn('box_id');
        });

        Schema::dropIfExists('boxes');
    }
};
