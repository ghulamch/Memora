<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->default('general'); // general, editor, features, etc
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('app_settings')->insert([
            [
                'key' => 'lut_filter_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'editor',
                'description' => 'Enable or disable LUT color filter feature in photo editor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'session_gap_minutes',
                'value' => '5',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'Gap waktu (menit) untuk session code yang sama. Jika foto upload dalam gap ini, pakai session code yang sama.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_name',
                'value' => 'Memora Photo Booth',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama aplikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_upload_size_mb',
                'value' => '10',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'Maximum upload size dalam MB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};