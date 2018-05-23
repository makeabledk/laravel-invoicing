<?php

namespace Makeable\LaravelInvoicing\Tests\Feature\Interactions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Makeable\LaravelInvoicing\Events\InvoiceCreated;
use Makeable\LaravelInvoicing\Invoice;
use Makeable\LaravelInvoicing\InvoiceDocument;
use Makeable\LaravelInvoicing\Jobs\CreateInvoice;
use Makeable\LaravelInvoicing\Tests\Stubs\Customer;
use Makeable\LaravelInvoicing\Tests\Stubs\Product;
use Makeable\LaravelInvoicing\Tests\Stubs\SalesInvoice;
use Makeable\LaravelInvoicing\Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function invoices_are_created_with_an_invoiceable_and_a_builder()
    {
        CreateInvoice::dispatch($this->customer(), new SalesInvoice($this->products()));

        $this->assertEquals(1, Invoice::count());
    }

    /** @test **/
    public function invoice_document_proxies_calls_to_disk()
    {
        CreateInvoice::dispatch($customer = $this->customer(), new SalesInvoice($this->products()));

        $this->assertEquals('foobar', InvoiceDocument::get(Invoice::first()));
    }

    /** @test **/
    public function it_converts_html_to_a_pdf_in_specified_storage()
    {
        InvoiceDocument::real();

        CreateInvoice::dispatch($customer = $this->customer(), new SalesInvoice($this->products()));

        $this->assertEquals('application/pdf', InvoiceDocument::mimeType(Invoice::first()));
    }

    /** @test **/
    public function it_dispatches_event_when_invoice_is_created()
    {
        Event::fake();

        CreateInvoice::dispatch($customer = $this->customer(), new SalesInvoice($this->products()));

        Event::assertDispatched(InvoiceCreated::class, function ($event) {
            return $event->invoice instanceof Invoice;
        });
    }

    /** @test **/
    public function it_attaches_tags_on_creation()
    {
        CreateInvoice::dispatch($customer = $this->customer(), new SalesInvoice($this->products()));

        $this->assertCount(2, Invoice::first()->tags);
        $this->assertCount(2, Invoice::first()->related(Product::class)->get());
    }

    /** @test **/
    public function it_queries_related_invoices()
    {
        CreateInvoice::dispatch($customer = $this->customer(), new SalesInvoice($this->products()));

        $this->assertCount(1, Product::first()->relatedInvoices);
    }

    /** @test **/
    public function invoices_has_an_invoiceable()
    {
        CreateInvoice::dispatch($customer = $this->customer(), new SalesInvoice($this->products()));

        $this->assertEquals($customer->id, Invoice::first()->invoiceable->id);
    }

    /** @test **/
    public function invoices_can_be_queried_from_invoiceables()
    {
        CreateInvoice::dispatch($customer = $this->customer(), new SalesInvoice($this->products()));

        $this->assertCount(1, Customer::first()->invoices);
    }
}
