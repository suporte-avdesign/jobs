<?php


namespace Modules\Etq\Classes\Exports\Excel;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class StockExports implements FromArray, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @var array
     */
    private $data;

    /**
     * StockExports constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Use an array to populate the export.
     *
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * Prepend a heading row.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'DATA',
            'FIRMA',
            'FILIAL',
            'TIPO',
            'CÓDIGO',
            'PRODUTO',
            'UNID',
            'QTD',
            'CUSTO UNITÁRIO',
            'CUSTO TOTAL'
        ];
    }

    /**
     * Format certain columns.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}
