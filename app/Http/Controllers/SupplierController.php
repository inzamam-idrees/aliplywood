<?php

namespace App\Http\Controllers;

use App\Supplier;
use App\Unit;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.create');
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
            // 'name' => 'required|min:3|unique:suppliers|regex:/^[a-zA-Z ]+$/',
            'name' => 'required|min:3|max:50',
            'email' => 'nullable|email|max:50|unique:suppliers',
            'mobile' => 'nullable|min:3|digits:11',
            'address' => 'nullable|string|min:3|max:100',
            'photo' => 'image|file|max:2048',
            'details' => 'nullable|min:3',
        ]);

        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->mobile = $request->mobile;
        $supplier->address = $request->address;
        $supplier->details = $request->details;
        // $supplier->previous_balance = $request->previous_balance;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();        
            $image->move(public_path('images/supplier/'), $imageName);
            $supplier->photo = $imageName;
        }
        $supplier->save();

        return redirect()->back()->with('message', 'New supplier has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
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
            'name' => 'required|min:3|max:50',
            'email' => 'nullable|email|max:50|unique:suppliers',
            'mobile' => 'nullable|min:3|digits:11',
            'address' => 'nullable|string|min:3|max:100',
            'photo' => 'image|file|max:2048',
            'details' => 'nullable|min:3',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->mobile = $request->mobile;
        $supplier->address = $request->address;
        $supplier->details = $request->details;
        // $supplier->previous_balance = $request->previous_balance;
        if ($request->hasFile('photo')) {
            if ($supplier->photo) {
                unlink(public_path('images/supplier/') . $supplier->photo);
            }
            $imageNew = $request->file('photo');
            $imageName = time() . '_' . uniqid() . '.' . $imageNew->getClientOriginalExtension();        
            $imageNew->move(public_path('images/supplier/'), $imageName);
            $supplier->photo = $imageName;
        }
        $supplier->save();

        return redirect()->back()->with('message', 'Supplier has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier->photo) {
            unlink(public_path('images/supplier/') . $supplier->photo);
        }
        $supplier->delete();
        return redirect()->back()->with('message', 'Supplier has been deleted!');

    }
}
