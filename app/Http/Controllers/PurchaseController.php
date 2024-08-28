<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\Purchase;
use App\Sale;
use App\Supplier;
use App\Category;
use App\Invoice;
use App\PurchaseDetail;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class PurchaseController extends Controller
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
        $purchase = Purchase::all();
        return view('purchase.index', compact('purchase'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::select(['id', 'name'])->get();
        $categories = Category::select(['id', 'name'])->get();
        $products = Product::all();
        return view('purchase.create', compact('suppliers','categories','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            // 'supplier_id' => 'required|exists:suppliers,id',
            // 'date' => 'required|date',
            // 'product_id.*' => 'required|exists:products,id',
            // 'qty.*' => 'required|numeric|min:1',
            // 'price.*' => 'required|numeric|min:0',
            // 'dis.*' => 'required|numeric|min:0|max:100',
            // 'amount.*' => 'required|numeric|min:0',
            'supplier_id'   => 'required',
            'date'          => 'required|string',
            'total_amount'  => 'required|numeric',
        ]);

        // Create a new purchase
        $purchase = new Purchase();
        $purchase->supplier_id = $request->supplier_id;
        $purchase->purchase_no = IdGenerator::generate([
            'table' => 'purchases',
            'field' => 'purchase_no',
            'length' => 10,
            'prefix' => 'PRS-'
        ]);
        $purchase->date = $request->date;
        $purchase->total_amount = $request->total_amount;
        $purchase->status = 1;

        // Save the purchase
        $purchase->save();

        // // Store purchase details
        // foreach ($request->product_id as $key => $productId) {
        //     $purchase->purchaseDetails()->create([
        //         'supplier_id' => $request->supplier_id, // Include supplier_id
        //         'product_id' => $productId,
        //         'qty' => $request->qty[$key],
        //         'price' => $request->price[$key],
        //         'discount' => $request->dis[$key],
        //         'amount' => $request->amount[$key],
        //         // Add other details if needed
        //     ]);
        // }

        foreach ($request->product_id as $key => $productId) {

            $pDetails['purchase_id']    = $purchase->id;
            $pDetails['product_id']     = $request->product_id[$key];
            $pDetails['quantity']       = $request->qty[$key];
            $pDetails['unitcost']       = intval($request->price[$key]);
            $pDetails['total']          = $request->amount[$key];
            $pDetails['created_at']     = Carbon::now();

            $purchase->details()->insert($pDetails);
        }

        // if (! $request->invoiceProducts == null)
        // {
        //     $pDetails = [];

        //     foreach ($request->invoiceProducts as $product)
        //     {
        //         $pDetails['purchase_id']    = $purchase['id'];
        //         $pDetails['product_id']     = $product['product_id'];
        //         $pDetails['quantity']       = $product['quantity'];
        //         $pDetails['unitcost']       = intval($product['unitcost']);
        //         $pDetails['total']          = $product['total'];
        //         $pDetails['created_at']     = Carbon::now();

        //         //PurchaseDetails::insert($pDetails);
        //         $purchase->details()->insert($pDetails);
        //     }
        // }


        return redirect()->route('purchase.index')->with('success', 'Purchase has been created!');
    }

    public function findPrice(Request $request){
        $data = DB::table('products')->select('sales_price')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function findPricePurchase(Request $request) {
        // $data = DB::table('product_suppliers')
        //         ->select('price')
        //         ->where('product_id', $request->id)
        //         ->where('supplier_id', $request->supplier_id) // Assuming you pass supplier_id from the frontend
        //         ->first();
    
        // return response()->json($data);
        $data = DB::table('products')->select('buying_price')->where('id', $request->id)->first();
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
        $invoice = Invoice::findOrFail($id);
        $sales = Sale::where('invoice_id', $id)->get();
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
        $invoice = Invoice::findOrFail($id);
        $sales = Sale::where('invoice_id', $id)->get();
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
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'dis' => 'required',
            'amount' => 'required',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->customer_id = $request->customer_id;
        $invoice->total = 1000;
        $invoice->save();

        Sale::where('invoice_id', $id)->delete();

        foreach ( $request->product_id as $key => $product_id){
            $sale = new Sale();
            $sale->qty = $request->qty[$key];
            $sale->price = $request->price[$key];
            $sale->dis = $request->dis[$key];
            $sale->amount = $request->amount[$key];
            $sale->product_id = $request->product_id[$key];
            $sale->invoice_id = $invoice->id;
            $sale->save();


        }

        return redirect('invoice/'.$invoice->id)->with('message','created Successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return redirect()->back();

    }
}
