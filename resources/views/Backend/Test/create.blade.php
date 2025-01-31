@extends('Backend.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<main id="main" class="main">
    <h1 class="header">Create Test</h1>
    <form class="simple" method="post" action="/Test/store" enctype="multipart/form-data">
        <div class="form1">
            @csrf
            
                <div class='input-group'>
                    <label>Name</label><br>
                    <select class='form-control select2' name='name' id='name'>
                        <option value=''>Select Name</option>
                        @foreach($select2_name as $option)
                            <option value='{{ $option->title }}' {{ isset($text) && $text->name == $option->title ? 'selected' : '' }}>
                                {{ $option->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                    <div class='input-group'>
                        <label>Title</label><br>
                        <input type='text' name='title' />
                    </div>
                    <div class='input-group'>
                        <label>Image</label><br>
                        <input type='text' name='image' />
                    </div>
                    <div class='input-group'>
                        <label>Updated_at</label><br>
                        <input type='text' name='updated_at' />
                    </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select an option'
        });
    });
</script>
@endsection