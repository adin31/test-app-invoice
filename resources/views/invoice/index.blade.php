@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="flash-message">
                @if (session('danger'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('danger') }}
                    </div>
                @endif 
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="card">
                <div class="card-header">
                    <font class="fw-bold text-uppercase" style="font-size: 1.25rem;">{{ __('Invoice List') }}</font>

                    <div class="panel-toolbar float-right float-end">
                        <a class="btn btn-sm btn-action text-white text-uppercase bg-primary fw-bold" title="Add" href="{{ URL::to('invoice/create') }}">
                            <i class="fa fa-plus-circle"></i> Add
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table id="invoice-list" class="table table-strip table-hover table-bordered">
                        <thead class="bg-secondary text-white text-uppercase fw-bold">
                            <th style="width:20%">Invoice ID</th>
                            <th>Subject</th>
                            <th style="width:20%">Action</th>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->subject }}</td>
                                <td>
                                    <center>
                                        <a class="btn btn-sm btn-info" href="{{ URL::to('invoice/'.$invoice->id) }}">
                                            <i class="fa fa-file"></i> Detail
                                        </a>
                                        &nbsp;
                                        <a class="btn btn-sm btn-warning" href="{{ URL::to('invoice/'.$invoice->id.'/edit') }}">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        &nbsp;
                                        <a class="btn btn-sm btn-danger" href="{{ URL::to('invoice/'.$invoice->id.'/delete') }}">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </center>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4"><center>{{ __('No record') }}</center></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
