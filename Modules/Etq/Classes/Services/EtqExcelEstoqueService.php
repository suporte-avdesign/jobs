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

    public function firmaPorData($date)
    {
        $data = array();
        $date = $this->formatDate($date);
        $companies = $this->model->distinct()->orderBy('firma')->where('data_ref', $date)->get('firma');
        foreach ($companies as $key => $company) {
            $result['firma'] = $company->firma;
            $result['filiais'] = $this->filialPorData($company->firma, $date);
            foreach ($result['filiais'] as $value) {
                $result['types'] = $this->tipoProdutoData($value['filial'], $date);
            }
            array_push($data, $result);
        }
        return json_decode(json_encode($data, FALSE));

        return $data;
    }

    public function filialPorData($company, $date)
    {
        return $this->model->distinct()
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
