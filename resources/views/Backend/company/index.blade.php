<link rel="stylesheet" href="{{ asset('css/Backend/company.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@extends('Backend.layouts.app')
@section('title', 'Company List')
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Company List</h2>
        <form class="left" method="post">
            <a href="{{ asset('/company/add') }}">Add Company</a>
        </form>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <table id="Table" class="table table-bordered table-striped" style="width: 1100px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Company Type</th>
                    <th>Company Email</th>
                    <th>Add Address</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="address-overlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 9999; padding: 20px;">
    <div style="background-color:var(--color); border-radius: 8px; max-width: 500px; margin: 50px auto; position: relative; overflow: hidden; display: flex; flex-direction: column; height: 90%;">
        <div style="padding: 20px; background-color: var(--color); border-bottom: 1px solid #ccc; position: sticky; top: 0; z-index: 10;">
            <h1 style="margin: 0;">Company Address Details</h1>
            <button id="close-overlay-icon"
                style="position: absolute; top: 25px;left: 435px; background-color: red; color: var(--color); border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer;">
                ×
            </button>
        </div>
        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <form id="address-form">
                <div id="form-row">
                </div>
            </form>
        </div>
        <div style="padding: 20px; background-color: var(--color); border-top: 1px solid #ccc; position: sticky; bottom: 0; z-index: 10;">
            <button type="button" id="add-overlay"
                style="background-color: green; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                Add Another Address
            </button>
            <button type="button" id="submit-over"
                style="background-color: blue; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                Submit
            </button>
        </div>
    </div>
</div>



@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const table = $('#Table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getCompanyAjax',
            type: 'POST',
        },
        pageLength: 5,
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'companyname',
                name: 'companyname'
            },
            {
                data: 'companytype',
                name: 'companytype'
            },
            {
                data: 'companyemail',
                name: 'companyemail'
            },
            {
                data: 'address',
                orderable: false,
                searchable: false
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

    // Open Address Overlay
    $(document).on('click', '.view-address-btn', function() {
        const company_id = $(this).data('company-id');
        $('#submit-over').attr('data-company-id', company_id);
        $('#address-overlay').fadeIn();

        $.ajax({
            url: '/getCompanyaddress',
            type: 'POST',
            data: {
                company_id: company_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const addressData = response.data;
                    $('#address-form').empty();
                    addressData.forEach(function(address) {
                        const newRow = `
                            <div class="form-row" data-id="${address.id}">
                                <div class="input-group">
                                    <input type="hidden" name="id[]" value="${address.id}">
                                    <label>Address</label>
                                    <input type="text" name="address[]" value="${address.address}" required>
                                    <label>Mobile</label>
                                    <input type="text" name="mobile[]" value="${address.mobile}" required>
                                    <label>Latitude</label>
                                    <input type="text" name="latitude[]" value="${address.latitude}" required>
                                    <label>Longitude</label>
                                    <input type="text" name="longitude[]" value="${address.longitude}" required>
                                    <button type="button" class="delete-address-btn">Delete</button>
                                </div>
                            </div>`;
                        $('#address-form').append(newRow);
                    });
                } else {
                    alert('No address data found');
                }
            },
            error: function() {
                alert('Error fetching address data');
            }
        });
    });

    // Add new address row
    $('#add-overlay').click(function(event) {
        event.preventDefault();
        const newForm = `
            <div class="form-row">
                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address[]" required>
                    <label>Mobile</label>
                    <input type="text" name="mobile[]" required>
                    <label>Latitude</label>
                    <input type="text" name="latitude[]" required>
                    <label>Longitude</label>
                    <input type="text" name="longitude[]" required>
                    <button type="button" class="delete-address-btn">Delete</button>
                </div>
            </div>`;
        $('#address-form').append(newForm);
    });
    // delete address

    $(document).on('click', '.delete-address-btn', function() {
        const row = $(this).closest('.form-row');
        const addressId = row.find('input[name="id[]"]').val();

        if (addressId) {

            $.ajax({
                url: '/deleteCompanyAddress',
                type: 'POST',
                data: {
                    address_id: addressId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Address deleted successfully!');
                        row.remove();
                    } else {
                        alert(response.message || 'Failed to delete address');
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the address');
                }
            });
        } else {

            row.remove();
        }
    });
    // save address
    $('#submit-over').on('click', function() {
        const companyId = $(this).data('company-id');
        const formData = $('#address-form').serializeArray();

        // Group data into arrays for each set of fields dynamically
        const data = {
            company_id: companyId,
            address: [],
            mobile: [],
            latitude: [],
            longitude: []
        };

        // Manually parse serialized data into grouped arrays
        formData.forEach(function(item, index) {
            if (item.name === "address[]") {
                data.address.push(item.value);
            }
            if (item.name === "mobile[]") {
                data.mobile.push(item.value);
            }
            if (item.name === "latitude[]") {
                data.latitude.push(item.value);
            }
            if (item.name === "longitude[]") {
                data.longitude.push(item.value);
            }
        });

        console.log("Sending data to server:", data);

        $.ajax({
            url: '/saveCompanyAddress',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Data saved successfully!');
                    $('#address-overlay').fadeOut();
                } else {
                    alert(response.message || 'Failed to save data');
                }
            },
            error: function() {
                alert('An error occurred while saving the data');
            }
        });
    });


    // Close Overlay
    $('#close-overlay').click(function() {
        $('#address-overlay').fadeOut();
    });
    // Close overlay when clicking the cross icon
$('#close-overlay-icon').click(function() {
    $('#address-overlay').fadeOut();
});

});
</script>
@endsection