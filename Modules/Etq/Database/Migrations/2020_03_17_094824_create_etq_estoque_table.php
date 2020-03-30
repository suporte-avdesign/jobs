<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtqEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etq_estoques', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firma');
            $table->string('filial');
            $table->string('categoria_estoque');
            $table->string('classificacao_fiscal');
            $table->string('codigo');
            $table->string('produto');
            $table->string('unid');
            $table->float('qtd', 11, 4)->nullable();
            $table->float('unitario', 6, 3)->nullable();
            $table->float('parcial', 11, 4)->nullable();
            $table->float('total', 11, 4)->nullable();
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
        Schema::dropIfExists('etq_estoques');
    }
}
