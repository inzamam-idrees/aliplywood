

@extends('layouts.master')

@section('titel', 'Purchase | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Purchase List</h1>
                <!-- <p>Table to display analytical data effectively</p> -->
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Purchase</li>
                <li class="breadcrumb-item active"><a href="#">Manage Purchase</a></li>
            </ul>
        </div>
        <div class="">
            <a class="btn btn-primary" href="{{route('purchase.create')}}"><i class="fa fa-plus"></i> Create New Purchase</a>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Purchase No. </th>
                                <th>Supplier Name </th>
                                <th>Date </th>
                                <th>Total </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                             <tbody>

                             @foreach($purchases as $purchase)
                                 <tr>
                                     <td>{{$purchase->purchase_no}}</td>
                                     <td>{{$purchase->supplier->name}}</td>
                                     <td>{{$purchase->date->format('Y-m-d')}}</td>
                                     <td>{{$purchase->total}}</td>
                                     <td>
                                         <!-- <a class="btn btn-primary btn-sm" href="{{route('purchase.show', $purchase->id)}}"><i class="fa fa-eye" ></i></a> -->
                                         <a class="btn btn-info btn-sm" href="{{route('purchase.edit', $purchase->id)}}"><i class="fa fa-edit" ></i></a>

                                         <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $purchase->id }})">
                                             <i class="fa fa-trash"></i>
                                         </button>
                                         <form id="delete-form-{{ $purchase->id }}" action="{{ route('purchase.destroy',$purchase->id) }}" method="POST" style="display: none;">
                                             @csrf
                                             @method('DELETE')
                                         </form>
                                     </td>
                                 </tr>
                             @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>



@endsection

@push('js')
    <script type="text/javascript" src="{{asset('/')}}js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('/')}}js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
    <script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
    <script type="text/javascript">
        function deleteTag(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swal(
                        'Cancelled',
                        'Your data is safe :)',
                        'error'
                    )
                }
            })
        }
    </script>
@endpush
