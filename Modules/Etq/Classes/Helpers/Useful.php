<?php


namespace Modules\Etq\Classes\Helpers;


class Useful
{
    /**
     * Retorna o array como obteto.
     *
     * @param $array
     * @return mixed
     */
    public function typeObject($array)
    {
        return json_decode(json_encode($array, FALSE));
    }

}
