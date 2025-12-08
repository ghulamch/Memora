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

             // Add usage tracking columns
            $table->unsignedBigInteger('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            
            // Add index for better query performance
            $table->index('usage_count');
            $table->index('last_used_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('luts');
    }
};
