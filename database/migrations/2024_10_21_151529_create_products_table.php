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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Přidání sloupce pro název produktu
            $table->text('description'); // Přidání sloupce pro popis produktu
            $table->decimal('price', 8, 2); // Přidání sloupce pro cenu (8 číslic, 2 desetinná místa)
            $table->string('sku')->unique(); // Přidání sloupce pro SKU, jedinečný identifikátor
            $table->integer('in_stock'); // Přidání sloupce pro množství na skladě
            $table->timestamps(); // Automatické sloupce created_at a updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
