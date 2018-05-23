<?php

namespace Makeable\LaravelInvoicing\Tests\Stubs;

use App\User;
use Makeable\LaravelInvoicing\Invoiceable;

class Customer extends User
{
    use Invoiceable;

    /**
     * @var string
     */
    protected $table = 'users';
}
