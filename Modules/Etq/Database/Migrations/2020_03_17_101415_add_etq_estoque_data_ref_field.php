<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEtqEstoqueDataRefField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('etq_estoques', function (Blueprint $table) {
            $table->string('data_ref')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etq_estoques', function (Blueprint $table) {
            $table->dropColumn('data_ref');
        });
    }
}
