<?php

namespace Modules\Etq\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Etq\Classes\Files\HandleFiles;
use Modules\Etq\Classes\Services\EtqTaskEstoqueService;

class EtqTaskEstoqueController extends Controller
{
    private $config;
    private $taskService;
    private $handleFiles;

    public function __construct(EtqTaskEstoqueService $taskService, HandleFiles $handleFiles)
    {
        $this->taskService = $taskService;
        $this->handleFiles = $handleFiles;

        $this->config = $this->handleFiles
            ->filesDirectories()
            ->etq_estoque;
        }

    /**
     * InfoFiles: Retorna os arquivos de um diretório específico.
     * Se config->copy => true  Se existe(exclui) cria o diretório de destino.
     * SearchFiles: Buscar local e informações sobre os arquivos
     * TaskFiles: Inicia a leitura e as tarefas dos arquivos.
     *
     * @return Response
     */
    public function tasks()
    {
        try{
            \DB::beginTransaction();
            $infoFiles  = $this->handleFiles->getInfoFile($this->config);
            if ($this->config->copy) {
                $this->handleFiles->deleteDirectory($this->config->receiver);
                $this->handleFiles->makeDirectory($this->config->receiver);
            }

            $files = $this->handleFiles->searchFiles($this->config, $infoFiles);

            $tasks = $this->taskService->initTasks($files);

            \DB::commit();

        } catch(\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }
    }
}
