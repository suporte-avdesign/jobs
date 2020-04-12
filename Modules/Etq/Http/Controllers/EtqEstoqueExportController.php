<?php

namespace Modules\Etq\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Etq\Classes\Exports\Excel\StockExports;
use Modules\Etq\Http\Requests\StockExportRequest;
use Modules\Etq\Classes\Services\EtqExportEstoqueService;

class EtqEstoqueExportController extends Controller
{
    /**
     * @var EtqExportEstoqueService
     */
    private $message;
    private $exportService;

    /**
     * EtqEstoqueExportController constructor.
     * @param EtqExportEstoqueService $exportService
     */
    public function __construct(EtqExportEstoqueService $exportService)
    {
        $this->exportService = $exportService;
        $this->message = (object) array(
            'return_empty' => 'Não existe movimento de estoque nesta data.',
        );
    }

    /**
     * Get the form of all companies with stock on this date.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter()
    {
        $data_ref = '2020-03-25';
        $items = $this->exportService->getData($data_ref);
        if ($items) {
            return view('etq::etq_estoque.filter', compact('items', 'data_ref'));
        }
    }

    /**
     * Load form of companies with stock.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function loadData(Request $request)
    {
        $firma = $request->get('firma');
        $data_ref = $request->get('data_ref');
        $slug = Str::slug(str_replace('.', '-', $firma));
        $items = $this->exportService->getFirma($firma, $data_ref);
        if (!$items) {
            return response()->json(['error' => $this->message->return_empty]);
        }

        $html = view('etq::etq_estoque.ajax._filiais',
            compact('slug','firma', 'items'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Call this export for user to download.
     *
     * @param StockExportRequest $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportData(StockExportRequest $request)
    {
        $dataForm = $request->except('_token');
        $data = $this->exportService->filter($dataForm);
        $dateFile = Carbon::now();

        return Excel::download(new StockExports($data), "Estoque fitrado em {$dateFile}.xlsx");
    }
}
