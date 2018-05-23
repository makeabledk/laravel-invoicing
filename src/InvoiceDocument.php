<?php

namespace Makeable\LaravelInvoicing;

use Illuminate\Support\Facades\Facade;
use Makeable\LaravelInvoicing\Repositories\InvoiceDocumentRepository;
use Makeable\LaravelInvoicing\Tests\FakeInvoiceDocumentRepository;

class InvoiceDocument extends Facade
{
    /**
     * Use the fake implementation for performance
     */
    public static function fake()
    {
        static::swap(new FakeInvoiceDocumentRepository);
    }

    /**
     * Reset fake
     */
    public static function real()
    {
        static::swap(new InvoiceDocumentRepository);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return InvoiceDocumentRepository::class;
    }
}
