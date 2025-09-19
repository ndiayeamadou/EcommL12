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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Snapshot du nom
            $table->string('product_sku'); // Snapshot du SKU
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('discount_per_item', 10, 2)->default(0);
            $table->decimal('subtotal', 12, 2); // (unit_price * quantity) - discount
            $table->json('product_snapshot')->nullable(); // Snapshot des détails produit
            $table->timestamps();

            $table->index(['sale_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
