<?php


namespace Modules\Etq\Classes\Services;

use Modules\Etq\Entities\EtqEstoque;

class EtqExcelEstoqueService
{
    /**
     * @var EtqEstoque
     */
    private $model;

    public function __construct(EtqEstoque $model)
    {
        $this->model = $model;
    }

    public function porData($date)
    {
        $data = array();
        $date = $this->formatDate($date);
        $companies = $this->model->distinct()->orderBy('firma')->where('data_ref', $date)->get('firma');
        foreach ($companies as $key => $company) {
            $result['firma'] = $company->firma;
            $result['slug'] = \Illuminate\Support\Str::slug(str_replace('.', '-', $company->firma));
            $result['filiais'] = $this->filialPorData($company->firma, $date);
            foreach ($result['filiais'] as $value) {
                $result['types'] = $this->tipoProdutoData($value['filial'], $date);
            }
            array_push($data, $result);
        }
        return json_decode(json_encode($data, FALSE));
    }

    public function firmaPorData($company, $date)
    {
        $data = array();
        $date = $this->formatDate($date);
        $branches = $this->filialPorData($company, $date);
        foreach ($branches as $branch) {
            $result['filiais'] = $branch->filial;
            $result['types'] = $this->tipoProdutoData($branch->filial, $date);
            array_push($data, $result);
        }
        return json_decode(json_encode($data, FALSE));
    }

    public function filialPorData($company, $date)
    {
        return $this->model
            ->distinct()
            ->orderBy('filial')
            ->where(['firma' => $company, 'data_ref' => $date])
            ->get('filial');
    }

    public function tipoProdutoData($branch, $date)
    {
        return $this->model->distinct()
            ->where(['filial' => $branch, 'data_ref' => $date])
            ->get('categoria_estoque');
    }

    public function formatDate($date)
    {
        return date('d/m/y', strtotime($date));
    }

}
