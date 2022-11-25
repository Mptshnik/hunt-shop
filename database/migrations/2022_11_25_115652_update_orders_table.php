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
        Schema::table('orders', function (Blueprint $table) {
           // $table->dropForeign('client_application_form_id');
           // $table->dropColumn('client_application_form_id');
            $table->dropColumn('name');
            $table->foreignId('client_id')->nullable()->default(null)->change()->
            constrained('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
