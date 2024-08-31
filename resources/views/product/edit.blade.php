@extends('layouts.master')

@section('title', 'Product | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i>Edit Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product</li>
                <li class="breadcrumb-item"><a href="#">Edit</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="">
            <a class="btn btn-primary" href="{{route('product.index')}}"><i class="fa fa-edit"></i> Manage Products</a>
        </div>
        <div class="row mt-2">

            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Product</h3>
                    <div class="tile-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{route('product.update', $product->id)}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                             <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">Product Name <span class="text-danger">*</span></label>
                                    <input value="{{ old('name', $product->name) }}" name="name" class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Product Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Code <span class="text-danger">*</span></label>
                                    <input value="{{ old('code', $product->code) }}" name="code" class="form-control @error('code') is-invalid @enderror" type="text" placeholder="Product Code">
                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <!-- <div class="form-group col-md-6">
                                    <label class="control-label">Model</label>
                                    <input value="{{$product->model}}" name="model" class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Enter Tax Name">
                                    @error('model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div> -->
                                <div class="form-group col-md-6">
                                    <label class="control-label">Category <span class="text-danger">*</span></label>

                                    <select name="category_id" class="form-control">
                                        <!-- <option value="{{$product->category->id}}">{{$product->category->name}}</option> -->
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" @if (old('category_id', $product->category_id) == $category->id) selected="selected" @endif>{{$category->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Unit <span class="text-danger">*</span></label>
                                    <select name="unit_id" class="form-control">
                                        <!-- <option value="{{$product->unit->id}}">{{$product->unit->name}}</option> -->
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}" @if (old('unit_id', $product->unit_id) == $unit->id) selected="selected" @endif>{{$unit->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Buying Price <span class="text-danger">*</span></label>
                                    <input value="{{ old('buying_price', $product->buying_price) }}" name="buying_price" class="form-control @error('buying_price') is-invalid @enderror" type="number" placeholder="Enter Buying Price">
                                    @error('buying_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Selling Price <span class="text-danger">*</span></label>
                                    <input value="{{ old('selling_price', $product->selling_price) }}" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" type="number" placeholder="Enter Selling Price">
                                    @error('selling_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Quantity <span class="text-danger">*</span></label>
                                    <input value="{{ old('quantity', $product->quantity) }}" name="quantity" class="form-control @error('quantity') is-invalid @enderror" type="number" placeholder="Enter Quantity" disabled>
                                    @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Quantity Alert <span class="text-danger">*</span></label>
                                    <input value="{{ old('quantity_alert', $product->quantity_alert) }}" name="quantity_alert" class="form-control @error('quantity_alert') is-invalid @enderror" type="number" placeholder="Enter quantity Alert">
                                    @error('quantity_alert')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Image</label>
                                    <input value="{{$product->image}}" name="image"  class="form-control @error('image') is-invalid @enderror" type="file" >
                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Notes</label>
                                    <textarea name="notes" id="notes" rows="5" class="form-control @error('notes') is-invalid @enderror">
                                        {{ old('notes', $product->notes) }}
                                    </textarea>
                                    @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <!-- <div class="form-group col-md-6">
                                    <label class="control-label">Tax </label>
                                    <select name="tax_id" class="form-control">
                                        <option value="{{-- $product->tax->id --}}">{{-- $product->tax->name --}} %</option>
                                        {{-- @foreach($taxes as $tax)
                                            <option value="{{$tax->id}}">{{$tax->name}} %</option>
                                        @endforeach --}}
                                    </select>
                                    @error('tax_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div> -->
                            </div>

                            <!-- <div class="tile ">
                                <div class="row field_wrapper">
                                     <div class="form-group col-md-4">
                                        <select name="supplier_id[]" class="form-control">
                                            <option value="{{-- $supplier_id --}}">{{-- $product->supplier->name --}} </option>
                                            {{-- @foreach($suppliers as $supplier)
                                                <option value="{{$product->supplier->id}}">{{$product->supplier->name}} </option>
                                            @endforeach --}}
                                        </select>
                                        @error('supplier_id')
                                        <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                             </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input value="{{-- $price --}}" name="supplier_price[]" class="form-control @error('supplier_price') is-invalid @enderror" type="number" placeholder="Enter Sales Price">
                                        @error('supplier_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <a href="javascript:void(0);" class="add_button btn btn-primary btn-sm" title="Add field"><i class="fa fa-plus"></i></a>
                                        <a href="javascript:void(0);" class="remove_button btn btn-danger btn-sm" title="Delete field"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            // var fieldHTML = '<div><select name="supplier_id[]" class="form-control"><option class="form-control">Select Supplier</option>{{-- @foreach($suppliers as $supplier)<option value="{{$supplier->id}}">{{$supplier->name}}</option>@endforeach --}}</select><input name="supplier_price[]" class="form-control" type="text" placeholder="Enter Sales Price"><a href="javascript:void(0);" class="remove_button btn btn-danger" title="Delete field"><i class="fa fa-minus"></i></a></div>'
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                    x++; //Increment field counter
                    // $(wrapper).append(fieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });
        });
    </script>

@endpush



