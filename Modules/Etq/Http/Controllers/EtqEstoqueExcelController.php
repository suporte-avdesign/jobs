<?php

namespace Modules\Etq\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Etq\Classes\Exports\EtqEstoqueExport;
use Modules\Etq\Http\Requests\EtqEstoqueRequest;
use Modules\Etq\Classes\Services\EtqExcelEstoqueService;


class EtqEstoqueExcelController extends Controller
{
    /**
     * @var EtqExcelEstoqueService
     */
    private $message;
    private $excelService;

    public function __construct(EtqExcelEstoqueService $excelService)
    {
        $this->excelService = $excelService;
        $this->message = array(
            'return_empty' => 'Não existe movimento de estoque nesta data.',
        );
    }

    public function filter()
    {
        $data_ref = '2020-03-24';
        $items = $this->excelService->porData($data_ref);
        if (!$items) {

        }
        return view('etq::etq_estoque.filter', compact('items', 'data_ref'));
    }

    /**
     * Update the specified resource in storage.
     * @param string $$date
     * @return Response
     */

    public function loadFirmas(Request $request)
    {
        //sleep(20);
        $firma = $request->get('firma');
        $data_ref = $request->get('data_ref');
        $slug = Str::slug(str_replace('.', '-', $firma));
        $items = $this->excelService->firmaPorData($firma, $data_ref);
        if (!$items) {
            return $this->responseJson( ['error' => $this->message['return_empty']]);
        }

        $html = view('etq::etq_estoque.ajax._filiais',
            compact('slug','firma', 'items'))
            ->render();

        return $this->responseJson(['html' => $html]);
    }


    public function filterData(EtqEstoqueRequest $request)
    {
        $dataForm = $request->except(['_token']);
        dd($dataForm);
    }

    public function responseJson($out)
    {
        return response()->json($out);
    }



}
