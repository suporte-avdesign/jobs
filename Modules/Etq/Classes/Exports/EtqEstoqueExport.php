<?php


namespace Modules\Etq\Classes\Exports;

use Modules\Etq\Entities\EtqEstoque;
use Maatwebsite\Excel\Concerns\FromCollection;

class EtqEstoqueExport implements FromCollection
{
    private $select;
    private $where;

    public function __construct($where, $select=null)
    {
        $this->where = $where;
        $this->select = $select;
    }

    public function collection()
    {
        dd($this->where);
        if ($this->select) {
            return EtqEstoque::select()->where($this->where)->get();
        }
        return EtqEstoque::where($this->where)->get();

    }

}
