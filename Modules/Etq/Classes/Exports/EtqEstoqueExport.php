<?php


namespace Modules\Etq\Classes\Exports;

use Modules\Etq\Entities\EtqEstoque;
use Modules\Etq\Http\Requests\StockExportRequest;


class EtqEstoqueExport
{


    /**
     * @var StockExportRequest
     */
    private $request;

    public function __construct(StockExportRequest $request)
    {
        $this->request = $request;
    }

    public function collection()
    {

        $firmas = 'BELA ISCHIA ALIMENTOS LTDA,ASTOLFO - MG,EMPRESA BRASILEIRA DE BEBIDAS E ALIM.S/A';
        $filiais = 'DEPOSITO - RJ,ASTOLFO - MG,DEPOSITO - DF';
        $categoria = 'ATIVO FIXO,PRODUTO ACABADO,MATERIAL DE CONSUMO';
        $myCategoria = explode(',', $categoria);
        $myFirmas = explode(',', $firmas);
        $myFiliais = explode(',', $filiais);
        $select = "data_ref,firma,filial,categoria_estoque,codigo,produto,unid,qtd,unitario,total";

        $data = EtqEstoque::select(
            'data_ref',
            'firma',
            'filial',
            'categoria_estoque',
            'codigo',
            'produto',
            'unid',
            'qtd',
            'unitario',
            'total')
            ->orderBy('filial', 'desc')
            ->where('data_ref','24/03/20')
            ->whereIn('firma', $myFirmas)
            ->whereIn('filial', $myFiliais)
            ->whereIn('categoria_estoque', $myCategoria)
            ->get();

        return $data;
    }

}
