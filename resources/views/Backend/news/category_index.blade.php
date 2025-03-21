<link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@extends('Backend.layouts.app')
@section('title', 'news category')
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>News Category List</h2>
        <div class="left">
            <a href="{{ asset('/newscategory/add') }}">Add-Category</a>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <table id="Table" class="table table-bordered table-striped" style="width: 1070px;">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#Table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/getNewsCategoryAjax',
                type: 'POST',
            },
            pageLength: 5,
            columns: [{ data: 's.no', name: 's.no' },

            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'edit',
                orderable: false,
                searchable: false
            },
            {
                data: 'delete',
                orderable: false,
                searchable: false
            },
            ],
            lengthMenu:[5,10,25,50,100]
        });
    });
</script>
@endsection