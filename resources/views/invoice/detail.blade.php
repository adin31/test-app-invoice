@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <font class="fw-bold text-uppercase" style="font-size: 1.25rem;">{{ __('Detail Invoice') }}</font>

                    <div class="panel-toolbar float-end">
                        <a class="btn btn-sm btn-action text-white text-uppercase bg-secondary fw-bold" title="Add" href="{{ URL::to('invoice') }}">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">{{ __('Invoice ID') }}</div>
                                <div class="col-md-8">{{ $invoice->id }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">{{ __('Issue Date') }}</div>
                                <div class="col-md-8">{{ $invoice->created_at }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">{{ __('Due Date') }}</div>
                                <div class="col-md-8">{{ $invoice->due_date }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">{{ __('Subject') }}</div>
                                <div class="col-md-8">{{ $invoice->subject }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="float-end">
                            <div class="row">
                                <div class="col-md-4 text-end">{{ __('From') }}</div>
                                <div class="col-md-6">
                                    {{ $invoice->invoice_from->name }}
                                    <br/>
                                    {{ $invoice->invoice_from->address }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-end">{{ __('For') }}</div>
                                <div class="col-md-6">
                                    {{ $invoice->invoice_for->name }}
                                    <br/>
                                    {{ $invoice->invoice_for->address }}
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered" id="item-list">
                                <thead>
                                    <th style="width: 10%">Item Type</th>
                                    <th>Description</th>
                                    <th style="width: 10%; text-align: right;">Quantity</th>
                                    <th style="width: 10%; text-align: right;">Unit Price</th>
                                    <th style="width: 10%; text-align: right;">Amount</th>
                                </thead>
                                <tbody>
                                    @forelse($invoice->invoice_item as $val)
                                        @php
                                            $price_raw = $val->quantity * $val->item->unit_price;
                                        @endphp
                                        <tr id="row-{{ $loop->index +1 }}" raw-price="{{ $price_raw }}">
                                            <td>{{ $val->item->type }}</td>
                                            <td>{{ $val->item->description }}</td>
                                            <td class="text-end">{{ $val->quantity }}</td>
                                            <td class="text-end">{{ $val->item->unit_price }}</td>
                                            <td class="text-end">{{ number_format($price_raw, 2) }}</td>
                                            <input type="hidden" name="item_qty[{{ $val->item->id }}]" value="{{ $val->quantity }}">
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                            <span id="item_list-error" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3 float-end">
                                <div class="row">
                                    <div class="col-md-7 text-end">{{ __('Subtotal') }}</div>
                                    <div class="col-md-5 text-end fw-bold" style="padding-right: 1.25rem;">{{ number_format($invoice->amount_total, 2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-end">{{ __('Tax (10%)') }}</div>
                                    <div class="col-md-5 text-end fw-bold" style="padding-right: 1.25rem;">{{ number_format($invoice->amount_tax, 2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-end">{{ __('Payments') }}</div>
                                    <div class="col-md-5 text-end fw-bold" style="padding-right: 1.25rem;">{{ number_format($invoice->amount_paid, 2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-end">{{ __('Amount Due') }}</div>
                                    <div class="col-md-5 text-end fw-bold" style="padding-right: 1.25rem;">{{ number_format($invoice->amount_due, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
@includeIf('invoice.js')
