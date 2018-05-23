<?php

namespace Makeable\LaravelInvoicing;

use Makeable\LaravelInvoicing\Jobs\CreateInvoice;

trait Invoiceable
{
    /**
     * @param InvoiceBuilder $builder
     *
     * @return $this
     */
    public function invoice($builder)
    {
        CreateInvoice::dispatch($this, $builder);

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function invoices()
    {
        return $this->morphMany(get_class(app(Invoice::class)), 'invoiceable');
    }
}
