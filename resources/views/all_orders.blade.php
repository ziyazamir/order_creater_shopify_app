@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
@endsection
@section('content')
    <!-- You are: (shop domain name) -->

    <div class="container">
        {{-- @php
            print_r($orders);
        @endphp --}}
        <button class="btn p-2 bg-danger"
            onclick="window.parent.location.href = 'https://test-theme-devlopment.myshopify.com/admin/apps/order-creator-4'">
            <- back</button>
                <h1 class="m-5">All Orders</h1>
                <table id="table_id" class="table">
                    <thead>
                        <tr>
                            <th>S. no</th>
                            <th>Order Id</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Ip</th>
                            <th>Status</th>
                            <th>Error</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $item)
                            <tr>
                                <td> 1 </td>
                                <td>{{ $item->order_id }}</td>
                                <td>{{ $item->customer_name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->ip }}</td>
                                @if ($item->status == '1')
                                    <td><span class="p-2 bg-success">Success</span></td>
                                @else
                                    <td><span class="p-2 bg-danger">Failed</span></td>
                                @endif
                                <td> {{ $item->error }}</td>
                                <td> {{ $item->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                "ordering": false,
                "pageLength": 100
            });
        });
    </script>
@endsection
