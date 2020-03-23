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
            $table->float('qtd', 12, 3)->nullable();
            $table->decimal('unitario', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
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
