<?php

namespace Modules\Etq\Entities;

use Illuminate\Database\Eloquent\Model;

class EtqEstoque extends Model
{

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
