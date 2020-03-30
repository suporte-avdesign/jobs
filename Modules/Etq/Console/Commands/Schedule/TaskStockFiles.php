<?php

namespace Modules\Etq\Console\Commands\Schedule;

use Illuminate\Console\Command;

use Modules\Etq\Classes\Files\HandleFiles;
use Modules\Etq\Classes\Services\EtqTaskEstoqueService;


class TaskStockFiles extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'etq:task-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarefas a serem realizadas todo dia para atualizar o estoque';

    /**
     * @var EtqTaskEstoqueService
     */
    private $taskService;
    /**
     * @var HandleFiles
     */
    private $handleFiles;

    private $config;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EtqTaskEstoqueService $taskService, HandleFiles $handleFiles)
    {
        parent::__construct();
        $this->taskService = $taskService;
        $this->handleFiles = $handleFiles;
        $this->config = $this->handleFiles
            ->filesDirectories()
            ->etq_estoque;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
