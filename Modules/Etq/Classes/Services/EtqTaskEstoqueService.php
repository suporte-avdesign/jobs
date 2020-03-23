<?php

namespace Modules\Etq\Classes\Services;

use Modules\Etq\Classes\Files\HandleFiles;
use Modules\Etq\Entities\EtqEstoque;

class EtqTaskEstoqueService
{
    private $model;
    private $config;
    private $handleFiles;

    public function __construct(EtqEstoque $model, HandleFiles $handleFiles)
    {
        $this->model = $model;
        $this->handleFiles = $handleFiles;
        $this->config = $this->handleFiles
            ->filesDirectories()
            ->etq_estoque;
    }

    /**
     * Inicia a leitura e as tarefas.
     *
     * @param $files
     */
    public function initTasks($files)
    {
        for ($i = 1; $i <= count($files); $i++) {
            $read[$i] = $this->taskFile($files[$i]);
        }
    }

    /**
     * Adicionar o arquivo ao fluxo para termos os nossos conteúdos.
     *
     * @param $file
     */
    private function taskFile($file)
    {
        if ($this->config->copy) {
            $content = $this->handleFiles->readTheFile($file);
            $data = $this->readContent($content);
        } else {
            $data = $this->readContent($file);
        }
        $chunk = $this->chunkData($data);

        // Outras tarefas ...............

    }

    /**
     * Ler as informações do produto para criar o fluxo de dados.
     *
     * @param $content
     */
    private function readContent($content)
    {
        $sum = 0;
        $total = 0;
        $data = array();
        $lastFirma = "";
        $lastFilial = "";
        $lastCategory = "";

        $fileLines = explode(PHP_EOL, $content);
        foreach($fileLines as $fileLine){
            //Firma
            $patternFirma = "(^\| FIRMA:)";
            $successFirma = preg_match($patternFirma, $fileLine, $match);
            if($successFirma){
                $lastFirma = trim(substr($fileLine, 8, 60));
                $lastFilial = trim(substr($fileLine, 68, 50));
            }
            //Em Estoque
            $patternEstoque = "((\*) (.+?) (\*))";
            $successEstoque = preg_match($patternEstoque, $fileLine, $match);
            if($successEstoque){
                $lastCategory = $match[2];
            }
            //Identificação de Produtos
            $patternProducts = "(\|[A-Z0-9]{3} \||\| [A-Z-0-9]{2} \||\| [A-Z-0-9]{1}  \|)";
            $successProducts = preg_match($patternProducts, $fileLine, $match);
            if($successProducts){
                $produto['firma'] = $lastFirma;
                $produto['filial'] = $lastFilial;
                $produto['categoria_estoque'] = $lastCategory;
                //Atributos do produto
                $attributes = trim(substr($fileLine, 1, 130));
                $value = explode('|', $attributes);
                $produto['classificacao_fiscal'] = trim($value[0]);
                $code = explode('-', $value[1]);
                $produto['codigo'] = trim($code[0]);
                $produto['produto'] = mb_convert_encoding(trim($code[1]), 'UTF-8', 'Windows-1252');
                $produto['unid'] = trim($value[2]);
                $produto['qtd'] = floatval(str_replace(',', '.', str_replace('.', '', trim($value[3]))));
                $produto['unitario'] = floatval(str_replace(',', '.', str_replace('.', '', trim($value[4]))));
                $produto['total'] = floatval(str_replace(',', '.', str_replace('.', '', trim($value[5]))));
                $produto['data_ref'] = date('d/m/Y');

                $sum += $produto['total'];
                array_push($data, $produto);
            }

            $patternTotal = "(\| {$this->config->compare})";
            $successTotal = preg_match($patternTotal, $fileLine, $match);
            if($successTotal){
                $total = floatval(str_replace(',', '.', str_replace('.', '', substr($fileLine, 112, 19))));
            }
        }

        return $this->checkTotals($data, $total, $sum);

    }

    /**
     * Verifica se os valores coincidem.
     *
     * @param $data
     * @param $total
     * @param $sum
     */
    private function checkTotals($data, $total, $sum)
    {
        if ((int)$total == (int)$sum) {
            return $this->chunkData($data);
        } else {
            throw new \InvalidArgumentException(
                "Method:checkTotals() - {$data[0]['firma']} - {$data[0]['filial']} - Valores não coincidem:".
                 " Total: R$ ".number_format($total, 2, ',', '.').
                 " - Soma: R$ ".number_format($sum, 2, ',', '.')
            );
        }
    }

    /**
     * Divide o array em pedaços.
     * O primeiro e último pedaço pode conter menos elementos que o parâmetro size.
     *
     * @param $data
     */
    private function chunkData($data)
    {
        $products = collect($data);
        (count($products) <= $this->config->chunk) ? $part = $data : $part = $this->config->chunk;

        foreach ($products->chunk($part) as $chunk) {
            foreach ($chunk as $product) {
                $this->model->create($product);
                //Tests Local: memory/ Log
                //$memory = $this->handleFiles->formatBytes(memory_get_peak_usage());
                //\Log::info("Código: {$product['codigo']} -  Unid: {$product['unid']} - Qtd:{$product['qtd']} - Unitário:{$product['unitario']} - Total:{$product['total']}");
            }
        }
    }

}
