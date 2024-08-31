@extends('layouts.master')

@section('title', 'Invoice | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
                <p>A Printable Invoice Format</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Invoice</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="row mb-4">
                            <div class="col-6">
                                <h2 class="page-header"><i class="fa fa-file"></i> A L I P L Y W O O D</h2>
                            </div>
                            <div class="col-6">
                                <!-- <h5 class="text-right">Date: {{$invoice->created_at->format('Y-m-d')}}</h5> -->
                                <h5 class="text-right">Date: {{$invoice->order_date->format('Y-m-d')}}</h5>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-4">From
                                <address><strong>Ali Plywood & Hardware Store</strong><br>Address<br><strong>Raja Road Sialkot</strong><br>Phone<br><strong>0305-4261666</strong><br>Email<br><strong>admin@aliplywood.com</strong></address>
                            </div>
                            <div class="col-4">To
                                 <address><strong>{{$invoice->customer->name}}</strong><br>Address<br><strong>{{$invoice->customer->address ?? 'N/A'}}</strong><br>Phone<br><strong>{{$invoice->customer->mobile ?? 'N/A'}}</strong><br>Email<br><strong>{{$invoice->customer->email ?? 'N/A'}}</strong></address>
                             </div>
                            <!-- <div class="col-4"><b>Invoice #{{1000+$invoice->id}}</b><br><br><b>Order ID:</b> 4F3S8J<br><b>Payment Due:</b> {{$invoice->created_at->format('Y-m-d')}}<br><b>Account:</b> 000-12345</div> -->
                            <div class="col-4"><b>Invoice #{{$invoice->invoice_no}}</b><br><br><b>Order ID:</b> {{$invoice->id}}<br><br><b>Payment Type:</b> {{$invoice->payment_type}}</div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <!-- <th>Discount %</th> -->
                                        <th>Amount</th>
                                     </tr>
                                    </thead>
                                    <tbody>
                                    <div style="display: none">
                                        {{$total=0}}
                                    </div>
                                    @foreach($sales as $index => $sale)
                                    <tr>
                                        <td>{{ ($index + 1) }}</td>
                                        <td>{{$sale->product->name}}</td>
                                        <td>{{$sale->quantity}}</td>
                                        <td>{{$sale->unitcost}}</td>
                                        <!-- <td>{{$sale->dis}}%</td> -->
                                        <td>{{$sale->total}}</td>
                                        <div style="display: none">
                                            {{$total +=$sale->unitcost}}
                                        </div>
                                     </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr><td colspan="5"></td></tr>
                                    <!-- <tr>
                                        <td colspan="4" class="text-right"><b>Sub Total</b></td>
                                        <td class="text-center"><b>{{ number_format($invoice->sub_total, 2) }}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Discount %</b></td>
                                        <td class="text-center"><b>{{ number_format($invoice->discount, 2) }}</b></td>
                                    </tr> -->
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Total</b></td>
                                        <td class="text-center"><b>{{ number_format($invoice->total, 2) }}</b></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:void(0);" onclick="printInvoice();"><i class="fa fa-print"></i> Print</a></div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>


    <script>
    function printInvoice() {
        window.print();
    }
    </script>

@endsection
@push('js')
@endpush





