<?php

namespace Makeable\LaravelInvoicing\Tests;

use Illuminate\Http\File;
use Makeable\LaravelInvoicing\Invoice;
use Makeable\LaravelInvoicing\Repositories\InvoiceDocumentRepository;

class FakeInvoiceDocumentRepository extends InvoiceDocumentRepository
{
    /**
     * @param Invoice $invoice
     * @param string  $html
     * @param null    $filename
     *
     * @return Invoice
     *
     * @throws \Exception
     */
    public function createPdf($invoice, $html, $filename = null)
    {
        file_put_contents($tempPdf = tempnam(sys_get_temp_dir(), 'LaravelInvoice').'.pdf', $html);

        return tap($this->store($invoice, new File($tempPdf), $filename), function () use ($tempPdf) {
            @unlink($tempPdf);
        });
    }
}
