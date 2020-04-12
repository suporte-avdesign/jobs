<?php

namespace Modules\Etq\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class EtqEstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //$data_ref = date('Y-m-d');
        $data_ref = '2020-03-24';
        return view('etq::etq_estoque.index', compact('data_ref'));
    }


}
