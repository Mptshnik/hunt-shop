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
        Schema::create('item_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number', 16)->unique();
            $table->foreignId('seller_id')->constrained('sellers')->nullOnDelete();
            $table->foreignId('provider_id')->constrained('providers')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_invoices');
    }
};
