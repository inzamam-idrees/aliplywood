<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\Sale;
use App\Sales;
use App\Supplier;
use App\Invoice;
use App\Order;
use App\OrderDetails;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        // $invoices = Invoice::all();
        $invoices = Order::all();
        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::get(['id', 'name']);
        $products = Product::with(['category', 'unit'])->get();
        return view('invoice.create', compact('customers','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
            'total' => 'required',
            'product_id.*' => 'required|exists:products,id',
            'qty.*' => 'required|numeric|min:1',
            'price.*' => 'required|numeric|min:0',
            // 'dis.*' => 'required|numeric|min:0|max:100',
            'amount.*' => 'required|numeric|min:0',
        ]);

        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->payment_type = "HandCash";
        $order->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
        $order->order_status = 1;
        $order->pay = $request->total;
        $order->total_products = $request->total_products;
        // $order->sub_total = $request->sub_total;
        // $order->discount = $request->discount;
        $order->total = $request->total;
        $order->due = 0;
        $order->bill_no = $request->bill_no;
        $order->employee = $request->employee;
        $order->invoice_no = IdGenerator::generate([
            'table' => 'orders',
            'field' => 'invoice_no',
            'length' => 10,
            'prefix' => 'INV-'
        ]);
        $order->save();

        foreach ( $request->product_id as $key => $product_id){
            $sale = new OrderDetails();
            $sale->quantity = $request->qty[$key];
            $sale->unitcost = $request->price[$key];
            // $sale->dis = $request->dis[$key];
            $sale->total = $request->amount[$key];
            $sale->product_id = $request->product_id[$key];
            $sale->order_id = $order->id;
            $sale->save();

            $product = $sale->product;
            $product->quantity -= $sale->quantity;
            $product->save();
        }

        return redirect('invoice/'.$order->id)->with('message','Invoice has been created!');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([

    //         'customer_id' => 'required',
    //         'product_id' => 'required',
    //         'qty' => 'required',
    //         'price' => 'required',
    //         // 'dis' => 'required',
    //         'amount' => 'required',
    //     ]);

    //     $invoice = new Invoice();
    //     $invoice->customer_id = $request->customer_id;
    //     $invoice->total = 1000;
    //     $invoice->save();

    //     foreach ( $request->product_id as $key => $product_id){
    //         $sale = new Sale();
    //         $sale->qty = $request->qty[$key];
    //         $sale->price = $request->price[$key];
    //         $sale->dis = $request->dis[$key];
    //         $sale->amount = $request->amount[$key];
    //         $sale->product_id = $request->product_id[$key];
    //         $sale->invoice_id = $invoice->id;
    //         $sale->save();
    //     }

    //     return redirect('invoice/'.$invoice->id)->with('message','Invoice created Successfully');
    // }

    public function findPrice(Request $request){
        // $data = DB::table('products')->select('sales_price')->where('id', $request->id)->first();
        $data = DB::table('products')->select('selling_price')->where('id', $request->id)->first();
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $invoice = Invoice::findOrFail($id);
        // $sales = Sale::where('invoice_id', $id)->get();
        $invoice = Order::findOrFail($id);
        $sales = OrderDetails::where('order_id', $id)->get();
        return view('invoice.show', compact('invoice','sales'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers = Customer::all();
        $products = Product::orderBy('id', 'DESC')->get();
        // $invoice = Invoice::findOrFail($id);
        // $sales = Sale::where('invoice_id', $id)->get();
        // return view('invoice.edit', compact('customers','products','invoice','sales'));
        $invoice = Order::findOrFail($id);
        $sales = OrderDetails::where('order_id', $id)->get();
        return view('invoice.edit', compact('customers','products','invoice','sales'));
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
        $request->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
            'total' => 'required',
            'product_id.*' => 'required|exists:products,id',
            'qty.*' => 'required|numeric|min:1',
            'price.*' => 'required|numeric|min:0',
            // 'dis.*' => 'required|numeric|min:0|max:100',
            'amount.*' => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->customer_id = $request->customer_id;
        // $order->payment_type = "HandCash";
        $order->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
        // $order->order_status = 1;
        // $order->pay = $request->total;
        $order->total_products = $request->total_products;
        // $order->sub_total = $request->sub_total;
        // $order->discount = $request->discount;
        $order->total = $request->total;
        // $order->due = 0;
        $order->bill_no = $request->bill_no;
        $order->employee = $request->employee;
        $order->save();

        // $invoice = Invoice::findOrFail($id);
        // $invoice->customer_id = $request->customer_id;
        // $invoice->total = 1000;
        // $invoice->save();

        // Sale::where('invoice_id', $id)->delete();
        foreach ($order->details as $detail) {
            $product = $detail->product;
            $product->quantity += $detail->quantity;
            $product->save();
        }
        OrderDetails::where('order_id', $id)->delete();

        foreach ( $request->product_id as $key => $product_id) {
            // $sale = new Sale();
            // $sale->qty = $request->qty[$key];
            // $sale->price = $request->price[$key];
            // $sale->dis = $request->dis[$key];
            // $sale->amount = $request->amount[$key];
            // $sale->product_id = $request->product_id[$key];
            // $sale->invoice_id = $invoice->id;
            // $sale->save();
            
            $sale = new OrderDetails();
            $sale->quantity = $request->qty[$key];
            $sale->unitcost = $request->price[$key];
            // $sale->dis = $request->dis[$key];
            $sale->total = $request->amount[$key];
            $sale->product_id = $request->product_id[$key];
            $sale->order_id = $order->id;
            $sale->save();

            $product = $sale->product;
            $product->quantity -= $sale->quantity;
            $product->save();

        }

         return redirect('invoice/'.$order->id)->with('message','Invoice has been updated!');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        // Sales::where('invoice_id', $id)->delete();
        // $invoice = Invoice::findOrFail($id);
        $invoice = Order::findOrFail($id);
        foreach ($invoice->details as $detail) {
            $product = $detail->product;
            $product->quantity += $detail->quantity;
            $product->save();
        }
        OrderDetails::where('order_id', $id)->delete();
        $invoice->delete();
        return redirect()->back()->with('message', 'Invoice has been deleted!');

    }

    public function downloadInvoice($id)
    {
        $order = Order::with(['customer', 'details'])->firstOrFail($id);
        return view('invoice.print-invoice', ['order' => $order]);
    }
}
