<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Supplier;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
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
            // 'name' => 'required|min:3|unique:customers|regex:/^[a-zA-Z ]+$/',
            'name' => 'required|min:3|max:50',
            'email' => 'nullable|email|max:50|unique:customers,email',
            'mobile' => 'nullable|min:3|digits:11|unique:customers,mobile',
            'address' => 'nullable|string|min:3|max:100',
            'photo' => 'image|file|max:2048',
            'details' => 'nullable|min:3',
            // 'previous_balance' => 'nullable|min:3',

        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->address = $request->address;
        $customer->details = $request->details;
        // $customer->previous_balance = $request->previous_balance;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();        
            $image->move(public_path('images/customer/'), $imageName);
            $customer->photo = $imageName;
        }

        $customer->save();

        return redirect()->back()->with('message', 'New customer has been created!');
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
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
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
            'email' => 'nullable|email|max:50|unique:customers,email,'.$id,
            'mobile' => 'nullable|min:3|digits:11|unique:customers,mobile,'.$id,
            'address' => 'nullable|string|min:3|max:100',
            'photo' => 'image|file|max:2048',
            'details' => 'nullable|min:3',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->mobile = $request->mobile;
        $customer->details = $request->details;
        // $customer->previous_balance = $request->previous_balance;
        // $image = $customer->photo;
        if ($request->hasFile('photo')) {
            if ($customer->photo) {
                unlink(public_path('images/customer/') . $customer->photo);
            }
            $imageNew = $request->file('photo');
            $imageName = time() . '_' . uniqid() . '.' . $imageNew->getClientOriginalExtension();        
            $imageNew->move(public_path('images/customer/'), $imageName);
            $customer->photo = $imageName;
        }
        $customer->save();

        return redirect()->back()->with('message', 'Customer has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer->photo) {
            unlink(public_path('images/customer/') . $customer->photo);
        }
        $customer->delete();
        return redirect()->back()->with('message', 'Customer has been deleted!');

    }
}
