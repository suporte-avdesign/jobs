<?php

namespace Modules\Etq\Entities;

use Illuminate\Database\Eloquent\Model;

class EtqEstoque extends Model
{
    /**
     * Define qual conexão será utilizada no model.
     */
    //protected $connection = 'DW';

    /**
     * Define o nome da tabela referente ao model.
     */
    //protected $table = 'controladoria.etq_estoque';


    /**
     * Campos liberados para mass-assignment.
     *
     * @var array
     */
    protected $fillable = [
        'firma',
        'filial',
        'categoria_estoque',
        'classificacao_fiscal',
        'codigo',
        'produto',
        'unid',
        'qtd',
        'unitario',
        'parcial',
        'total',
        'data_ref'
    ];
}
