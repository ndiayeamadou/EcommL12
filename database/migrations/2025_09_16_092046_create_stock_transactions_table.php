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
        // Table des transactions de stock (pour traçabilité)
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['sale', 'restock', 'adjustment', 'return']);
            $table->integer('quantity_before');
            $table->integer('quantity_change'); // Négatif pour vente, positif pour réapprovisionnement
            $table->integer('quantity_after');
            $table->text('reason')->nullable();
            $table->string('reference')->nullable(); // Numéro de vente, bon de commande, etc.
            $table->timestamps();

            $table->index(['product_id', 'type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
