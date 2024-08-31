@extends('layouts.master')

@section('title', 'Purchase | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Create Purchase</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Purchase</li>
                <li class="breadcrumb-item"><a href="#">Create</a></li>
            </ul>
        </div>


         <div class="row">
             <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Purchase</h3>
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
                        <form  method="POST" action="{{route('purchase.store')}}">
                            @csrf
                            <div class="form-group col-md-3">
                                <label class="control-label">Supplier</label>
                                <select name="supplier_id" class="form-control select2" style="width: 100%">
                                    <option selected disabled>Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}" @if(old('supplier_id') == $supplier->id) selected="selected" @endif>{{$supplier->name}} </option>
                                    @endforeach
                                </select>
                           </div>
                            <div class="form-group col-md-3">
                                <label class="control-label">Purchase Date</label>
                                <input name="date" class="form-control datepicker @error('date') is-invalid @enderror" value="{{ old('date') ?? now()->format('Y-m-d') }}" type="date" placeholder="Date">
                            </div>



                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Total</th>
                                <th scope="col"><a class="addRow badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-control productname select2" style="width: 100%">
                                        <option value="0" selected disabled>Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" min="0" disabled name="qty[]" class="form-control qty" ></td>
                                <td><input type="number" min="0" disabled name="price[]" class="form-control price" ></td>
                                <!-- <td><input type="number" min="0" disabled name="dis[]" class="form-control dis" ></td> -->
                                <td><input type="number" min="0" disabled name="amount[]" class="form-control amount" ></td>
                                <td><a class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>
                             </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><b>Total Product</b></td>
                                <td class="text-center">
                                    <b class="total_products">{{ old('total_products') }}</b>
                                    <input type="hidden" name="total_products" class="totalProductsInput" value="{{ old('total_products') }}">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right"><b>Total</b></td>
                                <td class="text-center">
                                    <b class="total">{{ old('total') }}</b>
                                    <input type="hidden" name="total" class="totalInput" value="{{ old('total') }}">
                                </td>
                            </tr>

                            </tfoot>

                        </table>

                            <div >
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                     </form>
                    </div>
                </div>


                </div>
            </div>







    </main>

@endsection
@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script src="{{ asset('/js/multifield/jquery.multifield.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/select2.min.js') }}"></script>




    <script type="text/javascript">
        $(document).ready(function(){

            $('.select2').select2();

            $('tbody').delegate('.productname', 'change', function () {

                var  tr = $(this).parent().parent();
                tr.find('.qty').focus();
                tr.find('.qty').prop('disabled', false);
                tr.find('.price').prop('disabled', false);
                tr.find('.amount').prop('disabled', false);

            })

            // $('tbody').delegate('.productname', 'change', function () {

            //     var tr =$(this).parent().parent();
            //     var id = tr.find('.productname').val();
            //     var dataId = {'id':id};
            //     $.ajax({
            //         type    : 'GET',
            //         url     :'{!! URL::route('findPricePurchase') !!}',

            //         dataType: 'json',
            //         data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'id':id},
            //         success:function (data) {
            //             tr.find('.price').val(data.price);
            //         }
            //     });
            // });

            $('tbody').delegate('.productname', 'change', function () {
                var tr = $(this).parent().parent();
                var productId = tr.find('.productname').val();
                // var supplierId = $('select[name="supplier_id"]').val(); // Get the selected supplier ID

                $.ajax({
                    type: 'GET',
                    url: '{{ route('findPricePurchase') }}',
                    dataType: 'json',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        'id': productId,
                        // 'supplier_id': supplierId // Pass supplier_id to backend
                    },
                    success: function (data) {
                        tr.find('.price').val(data.buying_price);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });



            $('tbody').delegate('.qty,.price', 'keyup', function () {

                var tr = $(this).parent().parent();
                var qty = tr.find('.qty').val();
                var price = tr.find('.price').val();
                // var dis = tr.find('.dis').val();
                // var amount = (qty * price)-(qty * price * dis)/100;
                var amount = (qty * price);
                tr.find('.amount').val(amount);
                total();
            });
            function total(){
                var total_products = 0;
                var total = 0;
                $('.amount').each(function (i,e) {
                    var amount =$(this).val()-0;
                    total += amount;
                    total_products += 1;
                })
                $('.total_products').html(total_products);
                $('.totalProductsInput').val(total_products);
                $('.total').html(total);
                $('.totalInput').val(total);
            }

            $('.addRow').on('click', function () {
                addRow();

            });

            function addRow() {
                var addRow = '<tr>\n' +
                    '         <td><select name="product_id[]" class="form-control productname select2" style="width: 100%" >\n' +
                    '         <option value="0" selected="true" disabled="true">Select Product</option>\n' +
'                                        @foreach($products as $product)\n' +
'                                            <option value="{{$product->id}}">{{$product->name}}</option>\n' +
'                                        @endforeach\n' +
                    '               </select></td>\n' +
'                                <td><input type="number" min="0" disabled name="qty[]" class="form-control qty" ></td>\n' +
'                                <td><input type="number" min="0" disabled name="price[]" class="form-control price" ></td>\n' +
// '                                <td><input type="number" min="0" disabled name="dis[]" class="form-control dis" ></td>\n' +
'                                <td><input type="number" min="0" disabled name="amount[]" class="form-control amount" ></td>\n' +
'                                <td><a class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>\n' +
'                             </tr>';
                $('tbody').append(addRow);
                $('.select2').select2();
            };


            $('.remove').live('click', function () {
                var l =$('tbody tr').length;
                if(l==1){
                    alert('you cant delete last one')
                }else{

                    $(this).parent().parent().remove();
                    total();

                }

            });
        });


    </script>

@endpush



