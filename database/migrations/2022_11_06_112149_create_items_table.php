<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('number', 16);
            $table->integer('count');
            $table->string('description', 100);
            $table->double('price');
            $table->double('sale_price');
            $table->string('image');
            $table->foreignId('item_category_id')->constrained('item_categories')->nullOnDelete();
            $table->foreignId('promotion_id')->constrained('promotions')->nullOnDelete();
            $table->foreignId('item_invoice_id')->constrained('item_invoices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
