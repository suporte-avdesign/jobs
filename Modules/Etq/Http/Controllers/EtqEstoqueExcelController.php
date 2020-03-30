<?php

namespace Modules\Etq\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Etq\Classes\Exports\EtqEstoqueExport;
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
            'date_empty' => 'Não existe movimento de estoque nesta data.'
        );
    }

    public function filter()
    {
        $data_ref = '2020-03-24';
        $data = $this->excelService->firmaPorData($data_ref);
        if (!$data) {

        }
        return view('etq::etq_estoque.filter', compact('data', 'data_ref'));
    }

    /**
     * Update the specified resource in storage.
     * @param string $$date
     * @return Response
     */

    public function companiesDate(Request $request)
    {
        $dataForm = $request->all();
        dd($dataForm);
        $date = $request->get('data_ref');
        $data = $this->excelService->firmaPorData($date);
        if (!$data) {

        }
    }


    public function branchesDate(Request $request)
    {
        dd($request->all());
    }

    public function responseJson($res)
    {
        (!is_array($res) ? $out = ['error' => $res] : $out = $res);
        return response()->json($out);
    }



}
