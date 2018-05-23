<?php

namespace Makeable\LaravelInvoicing\Tests\Stubs;

use Makeable\LaravelCurrencies\Amount;
use Makeable\LaravelInvoicing\RelatesToInvoices;

class Product extends \Illuminate\Database\Eloquent\Model
{
    use RelatesToInvoices;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return Amount
     */
    public function getPriceInclVatAttribute()
    {
        return new Amount(100);
    }
}
