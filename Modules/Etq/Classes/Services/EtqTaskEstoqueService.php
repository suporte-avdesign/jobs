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
        foreach ($files as $file) {
            $this->taskFile($file);
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

            //Data
            $patternData = "(DT\.Ref\.:)";
            $successData = preg_match($patternData, $fileLine, $match);
            if($successData){
                $lastData = trim(substr($fileLine, 138, 8));
            }
            //Firma
            $patternFirma = "(^\| FIRMA:)";
            $successFirma = preg_match($patternFirma, $fileLine, $match);
            if($successFirma){
                $lastFirma = trim(substr($fileLine, 8, 60));
                $lastFilial = trim(substr($fileLine, 68, 50));
            }
            //Datd ref
            $patternData = "/.*([0-9]{2}\/[0-9]{2}\/[0-9]{2}).*/";
            $successData = preg_match($patternData, $fileLine, $match);
            if($successData){
                $lastData = $match[1];
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
                $produto['parcial'] = floatval(str_replace(',', '.', str_replace('.', '', trim($value[5]))));
                $produto['data_ref'] = $lastData;

                $sum += $produto['parcial'];
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
        $vsum = number_format($sum, 2, ',', '.');
        $vtotal = number_format($total, 2, ',', '.');
        if ($vsum == $vtotal) {
            return $this->chunkData($data);
        } else {
            throw new \InvalidArgumentException(
                "Method:checkTotals() - {$data[0]['firma']} - {$data[0]['filial']} - Valores não coincidem:".
                 " Total: R$ ".$vtotal.
                 " - Soma: R$ ".$vsum
            );
        }
    }

    /**
     * Divide o array em pedaços e salva no banco de dados.
     * O primeiro e último pedaço pode conter menos elementos que o parâmetro size.
     *
     * @param $data
     */
    private function chunkData($data)
    {
        $items = collect($data);
        $count = $items->count();
        ($count <= $this->config->chunk) ? $part = $data : $part = $this->config->chunk;

        $chunks = $items->chunk((int)$part);
        $chunks->toArray();
        foreach ($chunks as $chunk) {
            foreach ($chunk as $product) {
                $this->model->create($product);
                //\Log::info("Data: {$product['data_ref']} -  Código: {$product['codigo']} -  Unid: {$product['unid']} - Qtd:{$product['qtd']} - Unitário:{$product['unitario']} - Total:{$product['total']}");
            }
        }
    }

}
