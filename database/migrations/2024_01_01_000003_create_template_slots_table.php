<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->integer('slot_order')->default(0);
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->integer('width')->default(300);
            $table->integer('height')->default(400);
            $table->integer('rotation')->default(0);
            $table->string('border_style')->default('none'); // none, solid, dashed
            $table->integer('border_width')->default(0);
            $table->string('border_color')->default('#000000');
            $table->integer('border_radius')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_slots');
    }
};
