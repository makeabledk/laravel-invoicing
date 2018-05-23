<?php

namespace Makeable\LaravelInvoicing\Repositories;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Makeable\LaravelInvoicing\Invoice;
use Spatie\Browsershot\Browsershot;

class InvoiceDocumentRepository
{
    /**
     * Proxy call to disk using Invoice storage-path instead of normal path.
     *
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $invoice = array_shift($arguments);
        $arguments = array_prepend($arguments, $invoice->storage_path);

        return call_user_func([$this->disk(), $name], ...$arguments);
    }

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
        Browsershot::html($html)->savePdf($tempPdf = tempnam(sys_get_temp_dir(), 'LaravelInvoice').'.pdf');

        return tap($this->store($invoice, new File($tempPdf), $filename), function () use ($tempPdf) {
            @unlink($tempPdf);
        });
    }

    /**
     * @param Invoice $invoice
     * @param File    $file
     * @param null    $filename
     *
     * @return Invoice
     *
     * @throws \Exception
     */
    public function store($invoice, $file, $filename = null)
    {
        $filename = $filename ?: $invoice->id.'_'.$file->hashName();
        $path = trim(config('laravel-invoicing.invoice_storage.path'), '/');

        if (! $storagePath = $this->disk()->putFileAs($path, $file, $filename, [
            'visibility' => config('laravel-invoicing.invoice_storage.visibility'),
        ])) {
            throw new \Exception('Failed to store invoice');
        }

        $invoice->storage_path = $storagePath;
        $invoice->save();

        return $invoice;
    }

    /**
     * @return FilesystemAdapter
     */
    public function disk()
    {
        return Storage::disk(config('laravel-invoicing.invoice_storage.disk'));
    }
}
