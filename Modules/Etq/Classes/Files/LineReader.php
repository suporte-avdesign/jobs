<?php


namespace Modules\Etq\Classes\Files;


class LineReader
{
    /**
     * Impedir instantiation
     */
    private function __construct() {}

    /**
     * @param string $filePath
     * @return \Generator
     * @throws \InvalidArgumentException
     */
    public static function readLines(string $filePath): \Generator
    {
        if (!$fh = @fopen($filePath, 'r')) {
            throw new \InvalidArgumentException('Não é possível abrir o arquivo para leitura: ' . $filePath);
        }

        return self::read($fh);
    }

    /**
     * @param $filePath
     * @return \Generator
     */
    public static function countLines(string $fh): \Generator
    {
        $count=0;
        while (false !== $line = fgets($fh)) {
            yield $count++;
        }
        return $count;
    }

    /**
     * @param string $filePath
     * @return \Generator
     * @throws \InvalidArgumentException
     */
    public static function readLinesBackwards(string $filePath): \Generator
    {
        if (!$fh = @fopen($filePath, 'r')) {
            throw new \InvalidArgumentException('Não é possível abrir o arquivo para leitura: ' . $filePath);
        }

        $size = filesize($filePath);

        return self::readBackwards($fh, $size);
    }

    /**
     * @param resource $fh
     * @return \Generator
     */
    private static function read($fh): \Generator
    {
        while (false !== $line = fgets($fh)) {
            yield $line."\n";
        }

        fclose($fh);
    }

    /**
     * Ler o arquivo do final usando um buffer.
     *
     * Isso é muito mais eficiente do que usar o método naive method
     * de ler o arquivo invertido byte por byte procurando um caractere na nova linha.
     * Ref: http://stackoverflow.com/a/10494801/147634
     *
     * @param resource $fh
     * @param int $pos
     * @return \Generator
     */
    private static function readBackwards($fh, int $pos): \Generator
    {
        $buffer = null;
        $bufferSize = 4096;

        if ($pos === 0) {
            return;
        }

        while (true) {
            if (isset($buffer[1])) { // mais rápido que count($buffer) > 1
                yield array_pop($buffer);
                continue;
            }

            if ($pos === 0) {
                yield array_pop($buffer);
                break;
            }

            if ($bufferSize > $pos) {
                $bufferSize = $pos;
                $pos = 0;
            } else {
                $pos -= $bufferSize;
            }
            fseek($fh, $pos);
            $chunk = fread($fh, $bufferSize);
            if ($buffer === null) {
                // remover uma nova linha à direita, o rtrim não pode ser usado aqui.
                if (substr($chunk, -1) === "\n") {
                    $chunk = substr($chunk, 0, -1);
                }
                $buffer = explode("\n", $chunk);
            } else {
                $buffer = explode("\n", $chunk . $buffer[0]);
            }
        }
    }
}
