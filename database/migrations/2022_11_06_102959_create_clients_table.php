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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('juridical_status_id')->constrained('juridical_statuses')->onDelete('restrict');
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->onDelete('cascade');
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->string('taxpayer_number')->unique();
            $table->string('juridical_address');
            $table->string('physical_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
