<?php


namespace Modules\Etq\Classes\Services;

use Illuminate\Support\Str;

use Modules\Etq\Entities\EtqEstoque;
use Modules\Etq\Classes\Helpers\Useful;

class EtqExportEstoqueService
{
    /**
     * @var EtqEstoque
     */
    private $model;
    /**
     * @var Useful
     */
    private $helper;

    /**
     * EtqExportEstoqueService constructor.
     * @param EtqEstoque $model
     * @param Useful $helper
     */
    public function __construct(EtqEstoque $model, Useful $helper)
    {
        $this->model = $model;
        $this->helper = $helper;
    }

    /**
     * Get the companies
     *
     * @param $date
     * @return mixed
     */
    public function getData($date)
    {
        $data = array();
        $date = $this->dataRef($date);
        $companies = $this->model->distinct()->orderBy('firma')->where('data_ref', $date)->get('firma');
        foreach ($companies as $key => $company) {
            $result['firma'] = $company->firma;
            $result['slug'] = Str::slug(str_replace('.', '-', $company->firma));
            array_push($data, $result);
        }
        return $this->helper->typeObject($data);
    }


    /**
     * Get the affiliates of the company.
     *
     * @param $company
     * @param $date
     * @return mixed
     */
    public function getFirma($company, $date)
    {
        $data = array();
        $date = $this->dataRef($date);
        $affiliates = $this->getFilial($company, $date);
        foreach ($affiliates as $affiliate) {
            $result['filiais'] = $affiliate->filial;
            $result['types'] = $this->getCategoria($affiliate->filial, $date);
            array_push($data, $result);
        }
        return $this->helper->typeObject($data);
    }

    /**
     * Get name of company affiliates without duplication.
     *
     * @param $company
     * @param $date
     * @return mixed
     */
    public function getFilial($company, $date)
    {
        return $this->model
            ->distinct()
            ->orderBy('filial')
            ->where(['firma' => $company, 'data_ref' => $date])
            ->get('filial');
    }

    /**
     * Get the name of the product categories for the affiliate without duplication.
     *
     * @param $affiliate
     * @param $date
     * @return mixed
     */
    public function getCategoria($affiliate, $date)
    {
        return $this->model->distinct()
            ->where(['filial' => $affiliate, 'data_ref' => $date])
            ->get(['categoria_estoque']);
    }

    public function filter($input)
    {
        $data = array();
        foreach ($input['tipos'] as $keys => $values) {
            foreach ($values as $categories) {
                foreach ($input['filiais'][$keys] as $key => $affiliate) {
                    $result = $this->filterData($this->dataRef($input['data_ref']), $affiliate, $categories);
                    array_push($data, $result);
                }
            }
        }
        return $data;
    }

    public function filterData($date, $affiliate, $categories)
    {
        $data = $this->model->select($this->sclectFields())
            ->when($date, function ($query, $date) use ($affiliate, $categories) {
                return $query
                    ->where(['filial' => $affiliate,'data_ref' => $date])
                    ->whereIn('categoria_estoque', $categories);
            })->get();
        return collect($data)->toArray();
    }

    /**
     * Select fields
     *
     * @return array
     */
    public function sclectFields()
    {
        return [
            'data_ref','firma','filial','categoria_estoque','codigo','produto','unid','qtd', 'unitario','parcial'
        ];
    }

    /**
     * Get the date in the format registered in the database.
     *
     * @param $date
     * @return false|string
     */
    public function dataRef($date)
    {
        return date('d/m/y', strtotime($date));
    }

}
