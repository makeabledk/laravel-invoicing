<?php

use Illuminate\Database\Migrations\Migration;

require __DIR__.'/../../database/migrations/create_invoicing_tables.php.stub';

class CreateProductionTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        (new CreateInvoicingTables())->up();
    }
}
