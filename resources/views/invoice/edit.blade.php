@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <font class="fw-bold text-uppercase" style="font-size: 1.25rem;">{{ __('Edit Invoice') }}</font>

                    <div class="panel-toolbar float-end">
                        <a class="btn btn-sm btn-action text-white text-uppercase bg-secondary fw-bold" title="Add" href="{{ URL::to('invoice') }}">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                {!! Form::open(['id' => 'form-input', 'route' => ['invoice.update', $invoice->id], 'class' => 'form', 'method' => 'put']) !!}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                {!! Form::label('invoice_from_id', 'From', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::select('invoice_from_id', $clients, $invoice->invoice_from_id, ['id' => 'invoice_from_id', 'class' => 'form-control']) !!}
                                <span id="invoice_from_id-error" class="invalid-feedback"></span>
                            </p>
                            <p>
                                {!! Form::label('invoice_for_id', 'For', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::select('invoice_for_id', $clients, $invoice->invoice_for_id, ['id' => 'invoice_for_id', 'class' => 'form-control']) !!}
                                <span id="invoice_for_id-error" class="invalid-feedback"></span>
                            </p>
                            <p>
                                {!! Form::label('due_date', 'Due Date', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::date('due_date', $invoice->due_date, ['id' => 'due_date', 'class' => 'form-control',]) !!}
                                <span id="due_date-error" class="invalid-feedback"></span>
                            </p>
                            <p>
                                {!! Form::label('subject', 'Subject', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::text('subject', $invoice->subject, ['id' => 'subject', 'class' => 'form-control',]) !!}
                                <span id="subject-error" class="invalid-feedback"></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                {!! Form::label('item', 'Item List', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::select('item_id', $items, NULL, ['id' => 'item_id', 'class' => 'form-control']) !!}
                            </p>
                            <p>
                                {!! Form::label('quantity', 'Set Quantity', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::number('quantity', NULL, ['id' => 'quantity', 'class' => 'form-control']) !!}
                            </p>
                            <p class="float-end">
                                <button type="button" class="btn btn-success" id="item-add">Add Item</button>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered" id="item-list">
                                <thead>
                                    <th style="width: 1%"></th>
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
                                            <td><a class="btn btn-sm bg-danger text-white item-remove"><i class="fa fa-times" /></a></td>
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
                        <div class="col-md-6">
                            <p>
                                {!! Form::label('amount_total', 'Subtotal', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::number('amount_total', number_format($invoice->amount_total), ['id' => 'amount_total', 'class' => 'form-control', 'disabled']) !!}
                                <span id="amount_total-error" class="invalid-feedback"></span>
                            </p>
                            <p>
                                {!! Form::label('amount_tax', 'Tax (10%)', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::number('amount_tax', number_format($invoice->amount_tax), ['id' => 'amount_tax', 'class' => 'form-control', 'disabled']) !!}
                                <span id="amount_tax-error" class="invalid-feedback"></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                {!! Form::label('amount_paid', 'Payments', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::number('amount_paid', number_format($invoice->amount_paid), ['id' => 'amount_paid', 'class' => 'form-control',]) !!}
                                <span id="amount_paid-error" class="invalid-feedback"></span>
                            </p>
                            <p>
                                {!! Form::label('amount_due', 'Amount Due', ['class' => 'col-md-12 control-label']) !!}
                                {!! Form::number('amount_due', number_format($invoice->amount_due), ['id' => 'amount_due', 'class' => 'form-control', 'disabled']) !!}
                                <span id="amount_due-error" class="invalid-feedback"></span>
                            </p>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="row">
                    <div class="panel-toolbar">
                        <a class="btn btn-primary btn-submit float-end" title="Save">
                            <i class="fa fa-save"></i> Save
                        </a>
                    </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@includeIf('invoice.js')
