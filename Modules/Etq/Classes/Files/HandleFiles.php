<?php

namespace Modules\Etq\Classes\Files;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Factory as FilesFactory;

use Illuminate\Support\Facades\Log;
use Modules\Etq\Classes\Files\LineReader;
use Modules\Etq\Classes\Traits\ConfigHandleFilesTrait;

class HandleFiles
{
    use ConfigHandleFilesTrait;


    /**
     *  Retorna os arquivos de um diretório específico.
     *
     * @param $config
     * @return mixed
     */
    public function getInfoFile($config, $date = null)
    {
        if ($config->production) {
            return $this->allFilesDirectory($config, $date);
        } else {
            return $this->allFilesStorage($config, $date);
        }
    }

    /**
     * Excluir diretório específico
     *
     * @param $path
     */
    public function deleteDirectory($path)
    {
        $fs = app(FilesFactory::class);
        if (is_dir($path)) {
            $fs->deleteDirectory($path, $preserve = false);
        }
    }

    /**
     * Criar diretório específico.
     *
     * @param $config
     * @throws \Exception
     */
    public function makeDirectory($path)
    {
        $fs = app(FilesFactory::class);
        if (!is_dir($path))
            $fs->makeDirectory($path);
    }



    /**
     * Retorna arquivos de um diretório específico fora da estrutura laravel.
     *
     * @param $config
     */
    public function allFilesDirectory($config, $date)
    {
        $dir  = $config->origin;
        $data = array();
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('Não foi possível abrir o diretório: ' . $dir);
        } else {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $path = $dir. DIRECTORY_SEPARATOR . $file;
                    $info = pathinfo($path);
                    ($date) ? $date = $this->searchDate($date) : $date = date($config->format);
                    if ($info['extension'] == $config->origin_ext) {
                        $info['dirname'] = $config->origin;
                        $dateFile = substr($info['filename'], $config->start, $config->lenght);
                        if ($dateFile == $date) {
                            $info['size'] = $this->getSize($info);
                            $info['modified'] = $this->getModified($info);
                            array_push($data, $info);
                        }
                    }
                }
                closedir($dh);
            }

            return json_decode(json_encode($data, FALSE));
        }
    }

    /**
     * Abre um diretório específico no "storage", e faz a leitura dos arquivos
     *
     * @param $config
     * @return mixed
     */
    public function allFilesStorage($config, $date)
    {
        $dir = storage_path('app') . DIRECTORY_SEPARATOR . $config->origin;
        $data = array();
        $files = $this->listFilesPath($config->origin, true);
        if ($files) {
            foreach ($files as $file) {
                $info = pathinfo($file);
                ($date) ? $thisDate = $this->searchDate($date) : $thisDate = date($config->format);
                if ($info['extension'] == $config->origin_ext) {
                    $info['dirname'] = $dir;
                    $dateFile = substr($info['filename'], $config->start, $config->lenght);
                    if ($dateFile == $thisDate) {
                        $info['size'] = $this->getSize($info);
                        $info['modified'] = $this->getModified($info);
                        array_push($data, $info);
                    }
                }
            }
        }


        return json_decode(json_encode($data, FALSE));
    }

    /**
     * Lista todos arquivos
     *
     * @param $path
     * @param null $rsort
     * @return mixed
     */
    public function listFilesPath($path, $rsort = null)
    {
        $fs = app(FilesFactory::class);
        $files   = $fs->allFiles($path);
        if ($rsort)
            rsort($files);

        return $files;
    }

    /**
     * Buscar informações sobre os arquivos
     *
     * @param $config
     * @param $info
     */
    public function searchFiles($config, $info, $date = null)
    {
        $i=1;
        $files = array();
        foreach ($info as $value) {
            $link    = "{$value->dirname}/{$value->basename}";
            $search  = $config->search;
            $ignore  = $config->ignore;
            $content = file_get_contents($link);
            if ($search) {
                if ($this->searchName($content, $search)) {
                    if ($ignore) {
                        if (!$this->searchName($content, $ignore)) {
                            if ($config->copy) {
                                (!$date) ? $pathDate = date('d-m-Y') : $pathDate = $date;
                                $name = "{$config->name}{$i}{$config->receiver_ext}";
                                $this->copyFiles($config, $content, $pathDate, $name);
                                $files[$i] = "{$config->path}/{$config->receiver}/{$pathDate}/{$name}";;
                            } else {
                                $files[$i] = file_get_contents($link);
                            }
                            $i++;
                        }
                    }
                }
            } else {
                // Se for o caso criar procedimento.
            }
        }

        return $files;
    }

    /**
     * Copia os arquivos para um pasta específica.
     *
     * @param $config
     * @param $content
     * @param $path
     */
    public function copyFiles($config, $content, $date, $name)
    {
        $fs = app(FilesFactory::class);
        $disk = $fs->disk($config->storage);
        $path = "{$config->receiver}/{$date}/{$name}";
        if ($disk->exists($path)) {
            $disk->put($path, $content);
        } else {
            $disk->append($path, $content);
        }
    }

    /**
     * Retorna o conteudo do arquivo
     *
     * @param $file
     * @return false|string
     */
    public function readTheFile($file) {
        try {
            return file_get_contents($file);
        } catch (FileNotFoundException $e) {
            throw new \InvalidArgumentException('Não foi possível ler o arquivo: ' . $file);
        }
    }

    /**
     * Obter o conteúdo do arquivo
     *
     * @param $info
     * @return false|string
     */
    public function getContent($info, $storage = null)
    {
        if (is_array($info)) {
            $path = "{$info['dirname']}/{$info['basename']}";
        } else {
            $path = "{$info->dirname}/{$info->basename}";
        }
        ($storage) ? $link = "{$storage}/{$path}" : $link = $path;
        $content = file_get_contents($link);
        if (!$content) {
            throw new \InvalidArgumentException("Não foi possível ler o conteúdo do arquivo: {$link}");
        } else {
            return $content;
        }
    }

    /**
     * Define local no disco
     *
     * @param $path
     * @return string
     */
    public function getPath($path)
    {
        return storage_path($path);
    }

    /**
     * Tamanho do arquivo
     *
     * @param $info
     * @return false|int
     */
    public function getSize($info)
    {
        return filesize($this->dirname($info));
    }

    /**
     * Data que o arquivo foi criado ou modificado
     *
     * @param $info
     * @return false|string
     */
    public function getModified($info)
    {
        return date('YmdHis', filemtime($this->dirname($info)));
    }

    /**
     * Verifica se existe a string no conteudo.
     *
     * @param $content
     * @param $str
     * @return false|int
     */
    public function searchName($content, $search)
    {
        $pattern = preg_match_all("/{$search}/", $content, $match);
        if ($pattern)
            return true;
    }

    /**
     * Caminho completo do arquivo
     *
     * @param $info
     * @return string
     */
    public function dirname($info)
    {
        return $info['dirname']. DIRECTORY_SEPARATOR . $info['basename'];
    }

    public function searchDate($date)
    {
        $arr = explode('-', $date);
        $d = $arr[0];
        $m = $arr[1];
        $y = $arr[2];
        return $y.$m.$d;
    }


    /**
     * Teste memory
     *
     * @param $bytes
     * @param int $precision
     * @return string
     */
    public function formatBytes($bytes, $precision = 2) {
        $units = array("b", "kb", "mb", "gb", "tb");

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . " " . $units[$pow];
    }

}
