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
        Schema::create('stocks_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toy_id');//外部キー
            $table->unsignedInteger('be_stored');
            $table->unsignedInteger('stock_in');
            $table->unsignedInteger('stock_out');
            $table->timestamps();
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_history');
    }
};
