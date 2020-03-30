<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangingFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('etq_estoques', function (Blueprint $table) {
            $table->string('classificacao_fiscal', 100)->nullable()->change();
            $table->float('qtd', 12, 3)->nullable()->change();
            $table->decimal('unitario', 15, 2)->nullable()->change();
            $table->decimal('parcial', 15, 2)->nullable()->change();
            $table->decimal('total', 15, 2)->nullable()->change();
            $table->string('data_ref', 30)->change();
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
            $table->dropColumn('classificacao_fiscal');
            $table->dropColumn('qtd');
            $table->dropColumn('unitario');
            $table->dropColumn('parcial');
            $table->dropColumn('total');
            $table->dropColumn('data_ref');
        });
    }
}
