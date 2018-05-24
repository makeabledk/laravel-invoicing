<?php

namespace Makeable\LaravelInvoicing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Makeable\LaravelInvoicing\Events\InvoiceCreated;
use Makeable\LaravelInvoicing\Invoice;
use Makeable\LaravelInvoicing\InvoiceBuilder;
use Makeable\LaravelInvoicing\InvoiceDocument;
use Makeable\LaravelInvoicing\InvoiceTag;

class CreateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Model
     */
    public $invoiceable;

    /**
     * @var InvoiceBuilder
     */
    public $builder;

    /**
     * @param Model          $invoiceable
     * @param InvoiceBuilder $builder
     */
    public function __construct($invoiceable, $builder)
    {
        $this->invoiceable = $invoiceable;
        $this->builder = $builder;
    }

    /**
     * Handle the invoice creation.
     */
    public function handle()
    {
        DB::transaction(function () {
            $invoice = $this->createInvoice();

            $this->createDocument($invoice);
            $this->tagInvoice($invoice);

            InvoiceCreated::dispatch($invoice);
        });
    }

    /**
     * @return Invoice
     */
    protected function createInvoice()
    {
        $invoice = new Invoice();
        $invoice->setType($this->builder->type());
        $invoice->invoiceable()->associate($this->invoiceable);

        return tap($this->builder->invoice($invoice))->save();
    }

    /**
     * @param Invoice $invoice
     */
    protected function createDocument($invoice)
    {
        InvoiceDocument::createPdf(
            $invoice,
            $this->builder->contents($invoice),
            $this->builder->filename($invoice)
        );
    }

    /**
     * @param Invoice $invoice
     */
    protected function tagInvoice($invoice)
    {
        collect($this->builder->tags())
            ->map(function ($relation) use ($invoice) {
                return $relation instanceof InvoiceTag
                    ? $relation
                    : app(InvoiceTag::class)->related()->associate($relation);
            })
            ->each(function ($relation) use ($invoice) {
                $relation->invoice()->associate($invoice);
                $relation->save();
            });
    }
}
