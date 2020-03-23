<?php


namespace App\Services;


class SendInvoice
{

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}
