<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@extends('layouts.app')
@section('title', 'news')
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>News List</h2>
        <div class="view">
            <a href="{{ asset('/dashboard') }}">Frontend</a>
        </div>
        <form class="left" method="post">
            <a href="{{ asset('/news/add') }}">Add-News</a>
        </form>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="filter-container">
            <h4>Filter</h4>
            <div class="filter">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate">
                <button id="filterButton">Filter</button>
            </div>
        </div>

        <table id="Table" class="table table-bordered table-striped" style="width: 1100px;">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <!-- <th>Name</th> -->
                    <th>Title</th>
                    <th>Category Id</th>
                    <th>Domain</th>
                    <th>Language</th>
                    <th id="status">Status</th>
                    <th>Create Date</th>
                    <!-- <th>Update Date</th> -->
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

        const table = $('#Table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/getNewsAjax',
                type: 'POST',
                data: function (d) {

                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                }
            },
            pageLength: 5,
            columns: [{ data: 's.no', name: 's.no' },

            // {
            //     data: 'name',
            //     name: 'name'
            // },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'categories.title',
                name: 'categories.title'
            },
            {
                data: 'domain.domainname',
                name: 'domain.domainname'
            },
            {
                data: 'language.languagename',
                name: 'language.languagename'
            },
            {
                data: 'status_dropdown',
                name: 'status_dropdown',
                orderable: false,
                searchable: false
            },
            {
                data: 'created_at',
                name: 'created_at',
                render: function (data, type, row) {
                    return row.time_ago || data;
                }
            },
            // {
            //     data: 'updated_at',
            //     name: 'updated_at',
            //     render: function(data, type, row) {
            //         return row.time_update_ago || data;
            //     }
            // },

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
        });

        $('#filterButton').on('click', function () {
            table.ajax.reload();
        });
        $('#Table').on('change', '.status-dropdown', function () {
            const newsId = $(this).data('id');
            const newStatusId = $(this).val();

            $.ajax({
                url: '/updateNewsStatus',
                type: 'POST',
                data: {
                    news_id: newsId,
                    status_id: newStatusId
                },
                success: function (response) {
                    table.ajax.reload(null, false);
                    alert(response.message);
                },
                error: function (xhr) {
                    alert('Failed to update status.');
                }
            });
        });
    });
</script>
@endsection