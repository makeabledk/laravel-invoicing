<?php

namespace Makeable\LaravelInvoicing;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Makeable\LaravelCurrencies\Amount;

class Invoice extends Eloquent
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function invoiceable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(get_class(app(InvoiceTag::class)));
    }

    /**
     * @param $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related($class)
    {
        return $this
            ->morphedByMany($class, 'related', app(InvoiceTag::class)->getTable())
            ->withPivot('tag')
            ->withTimestamps();
    }

    // _________________________________________________________________________________________________________________

    /**
     * @param $query
     * @param $invoiceable
     *
     * @return mixed
     */
    public function scopeInvoiceable($query, $invoiceable)
    {
        return $query
            ->where('invoiceable_type', $invoiceable->getMorphClass())
            ->where('invoiceable_id', $invoiceable->getKey());
    }

    // _________________________________________________________________________________________________________________

    /**
     * @param Amount $amount
     * @return $this
     */
    public function setTotalAmount($amount)
    {
        $this->attributes['total_amount'] = $amount->get();
        $this->attributes['currency_code'] = $amount->currency()->getCode();

        return $this;
    }

    /**
     * @param Amount $amount
     * @return $this
     */
    public function setVatAmount($amount)
    {
        $this->attributes['vat_amount'] = $amount->get();

        return $this;
    }

    /**
     * @return Amount
     */
    public function getTotalAmountAttribute()
    {
        return new Amount($this->attributes['total_amount'], $this->currency_code);
    }

    /**
     * @return Amount
     */
    public function getVatAmountAttribute()
    {
        return new Amount($this->attributes['vat_amount'], $this->currency_code);
    }
}
