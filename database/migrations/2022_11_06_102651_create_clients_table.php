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
            $table->string('taxpayer_number')->unique();
            $table->string('juridical_address');
            $table->string('physical_address');
            $table->foreignId('juridical_status_id')->constrained('juridical_statuses')->restrictOnDelete();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('people')->cascadeOnDelete();
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
