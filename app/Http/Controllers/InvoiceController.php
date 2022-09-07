<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with('invoice_item')->get();
        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dt_clients = Client::get();
        $clients    = ['' => '--Select Client--'];
        if (!$dt_clients->isEmpty()) {
            foreach ($dt_clients as $client) {
                $clients[$client->id] = $client->name;
            }
        }
        
        $dt_items   = Item::get();
        $items      = ['' => '--Select Item--'];
        if (!$dt_items->isEmpty()) {
            foreach ($dt_items as $item) {
                $items[$item->id] = $item->name;
            }
        }
        return view('invoice.add', compact('clients', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = false;
        $class = 'danger';
        $message = 'Add new record failed';
        $invoice = Invoice::create([
            'subject' => $request->subject,
            'due_date' => $request->due_date,
            'invoice_from_id' => $request->invoice_from_id,
            'invoice_for_id' => $request->invoice_for_id,
        ]);
        if ($invoice) {
            if (!empty($request->item_qty)) {
                $_amt_total = 0;
                foreach ($request->item_qty as $id_item => $qty_item) {
                    $_item = Item::find($id_item);
                    $item_price = 0;
                    if (!is_null($_item)) {
                        $item_price = $_item->unit_price * $qty_item;
                        $_amt_total += $item_price;
                    }
                    $invoice_item = InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_id' => $_item->id,
                        'quantity' => $qty_item,
                    ]);
                }
                $invoice->amount_total  = $_amt_total;
                $invoice->amount_tax    = $_amt_total * 0.1;
                $invoice->amount_paid   = $request->amount_paid;
                $invoice->amount_due    = ($invoice->amount_total + $invoice->amount_tax) - $invoice->amount_paid;
                $invoice->save();

                $result = true;
            }
        }
        if ($result) {
            $class = 'success';
            $message = 'Add new record success';
        }
        return redirect()->route('invoice.index')->with($class, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dt_clients = Client::get();
        $clients    = ['' => '--Select Client--'];
        if (!$dt_clients->isEmpty()) {
            foreach ($dt_clients as $client) {
                $clients[$client->id] = $client->name;
            }
        }
        
        $dt_items   = Item::get();
        $items      = ['' => '--Select Item--'];
        if (!$dt_items->isEmpty()) {
            foreach ($dt_items as $item) {
                $items[$item->id] = $item->name;
            }
        }
        $invoice = Invoice::find($id);
        return view('invoice.detail', compact('clients', 'items', 'invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dt_clients = Client::get();
        $clients    = ['' => '--Select Client--'];
        if (!$dt_clients->isEmpty()) {
            foreach ($dt_clients as $client) {
                $clients[$client->id] = $client->name;
            }
        }
        
        $dt_items   = Item::get();
        $items      = ['' => '--Select Item--'];
        if (!$dt_items->isEmpty()) {
            foreach ($dt_items as $item) {
                $items[$item->id] = $item->name;
            }
        }
        $invoice = Invoice::find($id);
        return view('invoice.edit', compact('clients', 'items', 'invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = false;
        $class = 'danger';
        $message = 'Edit record failed';
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice->subject   = $request->subject;
            $invoice->due_date  = $request->due_date;
            if (!empty($request->item_qty)) {
                InvoiceItem::where('invoice_id', $id)->delete();
                
                $_amt_total = 0;
                foreach ($request->item_qty as $id_item => $qty_item) {
                    $_item = Item::find($id_item);
                    $item_price = 0;
                    if (!is_null($_item)) {
                        $item_price = $_item->unit_price * $qty_item;
                        $_amt_total += $item_price;
                    }
                    $invoice_item = InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_id' => $_item->id,
                        'quantity' => $qty_item,
                    ]);
                }
                $invoice->amount_total  = $_amt_total;
                $invoice->amount_tax    = $_amt_total * 0.1;
                $invoice->amount_paid   = $request->amount_paid;
                $invoice->amount_due    = ($invoice->amount_total + $invoice->amount_tax) - $invoice->amount_paid;
                $result = true;
            }
            if($invoice->save()) {
                $result = true;
            }
        }
        if ($result) {
            $class = 'success';
            $message = 'Edit record success';
        }
        return redirect()->route('invoice.index')->with($class, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $result = false;
        $class = 'danger';
        $message = 'Delete record failed';
        if ($invoice) {
            InvoiceItem::where('invoice_id', $id)->delete();
            $invoice->delete();
            $result = true;
        }
        if ($result) {
            $class = 'success';
            $message = 'Delete record success';
        }
        return redirect()->route('invoice.index')->with($class, $message);
    }

    /**
     * Fetch a record detail.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch_item(Request $request)
    {
        $item = [];
        $data = Item::select('id', 'name', 'type', 'description', 'unit_price')->where('id', $request->item_id)->first();
        if (!is_null($data)) {
            $item = $data->toArray();
            $item['quantity']   = $request->quantity;
            $item['price_raw']  = $request->quantity * $item['unit_price'];
            $item['price']      = number_format(($request->quantity * $item['unit_price']), 2);
        } 
        return response()->json($item);
    }    

    /**
     * Fetch list of invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice_list()
    {
        $response   = [];
        $invoice    = Invoice::with('invoice_item')->get();
        if (!$invoice->isEmpty()) {
            foreach ($invoice as $val) {
                $row = [];
                $row['id'] = $val->id;
                $row['subject'] = $val->subject;
                $row['due_date'] = $val->due_date;
                $row['amount_total'] = $val->amount_total;
                $row['amount_tax'] = $val->amount_tax;
                $row['amount_paid'] = $val->amount_paid;
                $row['amount_due'] = $val->amount_due;
                $list_item = [];
                if (!empty($val->invoice_item)) {
                    foreach ($val->invoice_item as $child) {
                        $list_item[] = [
                            'item_name' => $child->item->name,
                            'item_type' => $child->item->type,
                            'item_description' => $child->item->description,
                            'item_unit_price' => $child->item->unit_price,
                            'item_quantity' => $child->quantity,
                        ];
                    }
                }
                $row['invoice_item'] = $list_item;
                $response[] = $row;
            }
        }
        return response()->json($response);
    }

    /**
     * Fetch list of invoice
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoice_detail($id)
    {
        $invoice = Invoice::find($id);
        if (!is_null($invoice)) {        
            $row = [];
            $row['id'] = $invoice->id;
            $row['subject'] = $invoice->subject;
            $row['due_date'] = $invoice->due_date;
            $row['amount_total'] = $invoice->amount_total;
            $row['amount_tax'] = $invoice->amount_tax;
            $row['amount_paid'] = $invoice->amount_paid;
            $row['amount_due'] = $invoice->amount_due;
            $list_item = [];
            if (!empty($invoice->invoice_item)) {
                foreach ($invoice->invoice_item as $child) {
                    $list_item[] = [
                        'item_name' => $child->item->name,
                        'item_type' => $child->item->type,
                        'item_description' => $child->item->description,
                        'item_unit_price' => $child->item->unit_price,
                        'item_quantity' => $child->quantity,
                    ];
                }
            }
            $row['invoice_item'] = $list_item;
            $response = $row;
        }
        return response()->json($response);
    }
}
