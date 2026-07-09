<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Polymorphic media (images + datasheet PDFs); reusable beyond products (gallery, blog, etc.).
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable');
            $table->string('type')->default('image'); // image, datasheet
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
