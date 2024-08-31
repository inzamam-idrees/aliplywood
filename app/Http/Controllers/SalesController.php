<?php


namespace App\Http\Controllers;

use App\Sales;
use App\OrderDetails;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        // $sales = Sales::with('product')->get(); // Include products related to sales

        // return view('sales.index', compact('sales'));
        
        $sales = OrderDetails::with('product')->get();
        return view('sales.index', compact('sales'));
    }
}
