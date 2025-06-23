<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('toys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('information');
            $table->unsignedInteger('price');
            $table->boolean('is_selling')->default(true);
            $table->foreignId('category_id');//外部キー
            $table->foreignId('series_id');//外部キー
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->unsignedInteger('stock');
            $table->boolean('is_reserve')->default(false);
            $table->date('release_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toys');
    }
};
