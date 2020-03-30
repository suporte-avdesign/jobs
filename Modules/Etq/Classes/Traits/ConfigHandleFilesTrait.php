<?php

namespace Modules\Etq\Classes\Traits;

trait ConfigHandleFilesTrait
{
    /**
     * Parâmetros para  busca, leitura e cópias (files).
     *
     * @return mixed
     */
    public function filesDirectories()
    {
        $isPrduction = app()->environment('production') || app()->environment('production');
        //Path: local ou (homologação/produção).
        $etq_estoque_origem = ($isPrduction) ? '/data/mnt/controladoria/etq/' : 'modules/Etq/tmp/';
        //$etq_estoque_origem = ($isPrduction) ? '/data/mnt/controladoria/etq/' : '/data/mnt/controladoria/etq/';
        $etq_estoque_destino = 'modules/Etq/etq_estoque/';
        //windows
        //$etq_estoque_local = ($isPrduction) ? 'D:/www/portal-ebba/storage/app' : 'D:/www/portal-ebba/storage/app';
        $etq_estoque_local = ($isPrduction) ? storage_path('app') : storage_path('app');

        $arr = [
            'etq_estoque' => [
                'copy' => true,                     # Copiar file.
                'read' => 'receiver',               # Especifica o local para leitura.(origin ou receiver).
                'format' => 'Ymd',                  # Formato da data 20200312.
                'start' => 13,                      # Início da string data (administrador->13).
                'lenght' => 8,                      # Comprimento da data (20200312).
                'chunk' => 1000,                    # Quantidade(max) das partes da matriz para inserir no bd.
                'name' => 'MATR460-',               # nome-auto incremento (copy = true).
                'search' => 'MATR460',              # Percorrer file e verifica se existe a string.
                'ignore' => 'ESTOQUE INEXISTENTE',  # Ignorar o file se existir a string.
                'compare' => 'TOTAL GERAL ====>',   # String referente ao total do estoque para comparar a soma dos produtos.
                'storage' => 'local',               # Define o local a ser gravado (copy = true).
                'origin_ext' => '##r',              # Extensão do arquivo.
                'receiver_ext' => '.txt',           # Nova extensão (copy = true).
                'origin' => $etq_estoque_origem,    # Diretório de origem.
                'receiver' => $etq_estoque_destino, # Diretório destino (copy = true).
                'path' => $etq_estoque_local,       # Caminho completo da pasta dos arquivos (copy = true).
                'production' => $isPrduction        # Local:false - Homologação/Produção:true
            ]
        ];

        return json_decode(json_encode($arr, FALSE));
    }
}
