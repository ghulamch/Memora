<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('luts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path'); // path to .cube file
            $table->string('thumbnail')->nullable(); // preview image
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('luts');
    }
};
