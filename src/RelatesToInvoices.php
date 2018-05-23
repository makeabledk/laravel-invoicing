<?php

namespace Makeable\LaravelInvoicing;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait RelatesToInvoices
{
    /**
     * @return MorphToMany
     */
    public function relatedInvoices()
    {
        return $this
            ->morphToMany(get_class(app(Invoice::class)), 'related', app(InvoiceTag::class)->getTable())//, 'attachable_id', 'image_id')
            ->withPivot('tag')
            ->withTimestamps();
    }
}
