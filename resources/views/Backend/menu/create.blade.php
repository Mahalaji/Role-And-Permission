@extends('Backend.layouts.app')
<link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
@section('content')
<script src="http://127.0.0.1:8000/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"></script>
<script src="http://127.0.0.1:8000/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>
<script src="http://127.0.0.1:8000/bootstrap-iconpicker/js/jquery-menu-editor.min.js"></script>

<h1 style="margin-left: 2%;text-align: center; background-color: #3586ff; padding: 10px; border-radius: 10px;">Menu Editor</h1>

<div class="row">
    <!-- Menu List Section -->
    <div class="col-md-6">
        <ul id="myEditor"></ul>
    </div>

    <!-- Edit Item Section -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Item</div>
            <div class="card-body">
                <form id="frmEdit" class="form-horizontal">
                    <div class="form-group">
                        <label for="text">Text</label>
                        <div class="input-group">
                            <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Text">
                            <div class="input-group-append">
                                <button type="button" id="myEditor_icon" class="btn btn-outline-secondary"></button>
                            </div>
                        </div>
                        <input type="hidden" name="icon" class="item-menu">
                    </div>
                    <div class="form-group">
                        <label for="href">URL</label>
                        <input type="text" class="form-control item-menu" id="href" name="href" placeholder="URL">
                    </div>
                    <div class="form-group">
                        <label for="target">Target</label>
                        <select name="target" id="target" class="form-control item-menu">
                            <option value="_self">Self</option>
                            <option value="_blank">Blank</option>
                            <option value="_top">Top</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control item-menu" id="title" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <label for="Permission">Permission</label>
                        <input type="text" name="Permission" class="form-control item-menu" id="Permission" placeholder="Permission">
                    </div>
                        <input type="hidden" name="modulesname" class="form-control item-menu" id="modulesname">
                        <input type="hidden" name="moduleid" class="form-control item-menu" id="moduleid">
                        <input type="hidden" name="deletestatus" class="form-control item-menu" id="deletestatus">
                    <div class="card-footer">
                        <button type="button" id="Saveoutput" onclick="event.preventDefault();
                            document.querySelector('.json-form').submit();" class="btn btn-success">Save</button>
                        <button type="button" id="btnUpdate" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Update</button>
                        <button type="button" id="btnAdd" class="btn btn-success"><i class="fas fa-plus"></i> Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Output Section -->
<div class="output-section mt-3">
    <form action="/updatejsondata" method="POST" class="json-form">
        @csrf
        <textarea id="myTextarea" class="form-control" rows="8" name="json_output" style="display:none;" required></textarea>
        <input type="hidden" name="id" value="{{ $finalmenu_output['id'] }}">
        <input type="hidden" value="Save" id="Save" class="btn btn-primary m-2 width-100">
    </form>
</div>
@endsection

@section('scripts')
<script>
    var iconPickerOptions = { searchText: "Search...", labelHeader: "{0}/{1}" };
    var sortableListOptions = { placeholderCss: { 'background-color': "#cccccc" } };
    var arrayjson = @json($finalmenu_output['json_output']);

    var editor = new MenuEditor('myEditor', {
        listOptions: sortableListOptions,
        iconPicker: iconPickerOptions,
        maxLevel: 2,
        formOptions: {
            icon: 'input[name="icon"]',
            text: '#text',
            href: '#href',
            target: '#target',
            title: '#title',
            modulesname: '#modulesname',
            moduleid: '#moduleid',
            deletestatus: '#deletestatus'
        }
    });

    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));
    editor.setData(arrayjson);

    let moduleIdCounter = 1; // Reset the counter for module IDs on each update

    // Function to update the modules data and increment moduleid
    function updateModulesData(item) {
        if (!item.text) return;
        item.modulesname = toCamelCase(item.text);
        item.moduleid = moduleIdCounter++; // Assign the current counter value and then increment it
        item.deletestatus = '1';
        if (item.children && item.children.length > 0) {
            item.children.forEach(updateModulesData); // Recursively update children if they exist
        }
    }

    // Function to convert string to camelCase
    function toCamelCase(str) {
        return str
            .replace(/[-_\s\/]+(.)?/g, (match, chr) => (chr ? chr.toUpperCase() : '')) // Remove spaces, slashes, underscores
            .replace(/^(.)/, (match, chr) => chr.toLowerCase()); // Ensure first character is lowercase
    }

    $('#btnAdd').click(function () {
        editor.add();
        updateTextarea();
    });

    $('#btnUpdate').click(function () {
        editor.update();
        updateTextarea();
    });

    $('#Saveoutput').click(function (event) {
        updateTextarea();
        document.querySelector('.json-form').submit();
    });

    function updateTextarea() {
        var jsonString = editor.getString();
        var jsonData = JSON.parse(jsonString);
        moduleIdCounter = 1; // Reset module ID counter before updating
        jsonData.forEach(updateModulesData);
        $('#myTextarea').val(JSON.stringify(jsonData, null, 2));
    }

    $('#myEditor_icon').iconpicker({
        placement: 'bottomLeft',
        animation: true
    }).on('iconpickerSelected', function (event) {
        $('input[name="icon"]').val(event.iconpickerValue);
    });
</script>
@endsection
