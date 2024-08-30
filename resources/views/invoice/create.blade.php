@extends('layouts.master')

@section('title', 'Invoice | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Create Invoice</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Invoices</li>
                <li class="breadcrumb-item"><a href="#">Create</a></li>
            </ul>
        </div>


         <div class="row">
             <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Invoice</h3>
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
                        <form  method="POST" action="{{route('invoice.store')}}">
                            @csrf
                            <div class="form-group col-md-3">
                                <label class="control-label">Customer Name <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-control">
                                    <option selected disabled>Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}" @if(old('customer_id') == $customer->id) selected="selected" @endif>{{$customer->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="control-label">Date <span class="text-danger">*</span></label>
                                <input name="order_date" class="form-control datepicker"  value="{{ old('order_date') ?? now()->format('Y-m-d') }}" type="date" placeholder="Date">
                            </div>



                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Discount %</th>
                                <th scope="col">Amount</th>
                                <th scope="col"><a class="addRow badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-control productname" >
                                        <option value="0" selected="true" disabled="true">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" min="0" disabled name="qty[]" class="form-control qty" ></td>
                                <td><input type="number" min="0" disabled name="price[]" class="form-control price" ></td>
                                <td><input type="number" min="0" disabled name="dis[]" class="form-control dis" ></td>
                                <td><input type="number" min="0" disabled name="amount[]" class="form-control amount" ></td>
                                <td><a class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>
                             </tr>
                            </tbody>
                            <tfoot>
                            <!-- <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Total</b></td>
                                <td><b class="total"></b></td>
                                <td></td>
                            </tr> -->

                            <tr>
                                <td colspan="5" class="text-right"><b>Total Product</b></td>
                                <td class="text-center">
                                    <b class="total_products"></b>
                                    <input type="hidden" name="total_products" class="totalProductsInput" value="">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>Sub Total</b></td>
                                <td class="text-center">
                                    <b class="subtotal"></b>
                                    <input type="hidden" name="sub_total" class="subtotalInput" value="">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>Discount %</b></td>
                                <td class="text-center">
                                    <b class="discount"></b>
                                    <input type="hidden" name="discount" class="discountInput" value="">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>Total</b></td>
                                <td class="text-center">
                                    <b class="total"></b>
                                    <input type="hidden" name="total" class="totalInput" value="">
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
     <script src="{{asset('/')}}js/multifield/jquery.multifield.min.js"></script>




    <script type="text/javascript">
        $(document).ready(function(){



            $('tbody').delegate('.productname', 'change', function () {

                var  tr = $(this).parent().parent();
                tr.find('.qty').focus();
                tr.find('.qty').prop('disabled', false);
                tr.find('.price').prop('disabled', false);
                tr.find('.dis').prop('disabled', false);
                tr.find('.amount').prop('disabled', false);

            })

            $('tbody').delegate('.productname', 'change', function () {

                var tr =$(this).parent().parent();
                var id = tr.find('.productname').val();
                var dataId = {'id':id};
                $.ajax({
                    type    : 'GET',
                    url     :'{!! URL::route('findPrice') !!}',

                    dataType: 'json',
                    data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'id':id},
                    success:function (data) {
                        // tr.find('.price').val(data.sales_price);
                        tr.find('.price').val(data.selling_price);
                    }
                });
            });

            $('tbody').delegate('.qty,.price,.dis', 'keyup', function () {

                var tr = $(this).parent().parent();
                var qty = tr.find('.qty').val();
                var price = tr.find('.price').val();
                var dis = tr.find('.dis').val();
                var amount = (qty * price)-(qty * price * dis)/100;
                tr.find('.amount').val(amount);
                total();
            });
            function total(){
                var total_products = 0;
                var quantity = 0;
                var unitcost = 0;
                var subtotal = 0;
                var discount = 0;
                var total = 0;
                $('.price').each(function (i,e) {
                    var price =$(this).val()-0;
                    unitcost += price;
                    total_products += 1;
                })
                $('.qty').each(function (i,e) {
                    var qty =$(this).val()-0;
                    quantity += qty;
                })
                $('.dis').each(function (i,e) {
                    var dis =$(this).val()-0;
                    discount += dis;
                })
                $('.amount').each(function (i,e) {
                    var amount =$(this).val()-0;
                    total += amount;
                })
                $('.total_products').html(total_products);
                $('.totalProductsInput').val(total_products);
                subtotal = unitcost * quantity;
                $('.subtotal').html(subtotal);
                $('.subtotalInput').val(subtotal);
                $('.discount').html(discount);
                $('.discountInput').val(discount);
                $('.total').html(total);
                $('.totalInput').val(total);
            }

            $('.addRow').on('click', function () {
                addRow();

            });

            function addRow() {
                var addRow = '<tr>\n' +
                    '         <td><select name="product_id[]" class="form-control productname " >\n' +
                    '         <option value="0" selected="true" disabled="true">Select Product</option>\n' +
'                                        @foreach($products as $product)\n' +
'                                            <option value="{{$product->id}}">{{$product->name}}</option>\n' +
'                                        @endforeach\n' +
                    '               </select></td>\n' +
'                                <td><input type="number" min="0" disabled name="qty[]" class="form-control qty" ></td>\n' +
'                                <td><input type="number" min="0" disabled name="price[]" class="form-control price" ></td>\n' +
'                                <td><input type="number" min="0" disabled name="dis[]" class="form-control dis" ></td>\n' +
'                                <td><input type="number" min="0" disabled name="amount[]" class="form-control amount" ></td>\n' +
'                                <td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>\n' +
'                             </tr>';
                $('tbody').append(addRow);
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



