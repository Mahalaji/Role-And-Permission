@extends('Backend.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<main id="main" class="main">
    <h1 class="header">Edit Test</h1>
    <form class="simple" method="post" action="/Test/update" enctype="multipart/form-data">
        <div class="form1">
            @csrf
            @method('POST')
            <input type="hidden" name="tablename" value="test">
            <input type='hidden' name='id' value='{{ $text->id }}' />
                <div class='input-group'>
                    <label>Name</label><br>
                    <select class='form-control select2' name='name' id='name'>
                        <option value=''>Select Name</option>
                        @foreach(explode(',', 'blogc,newsc,domanc') as $index => $key)
                            @php
                                $value = explode(',', 'blogCategory,newscategory,doman')[$index];
                            @endphp
                            <option value='{{ $key }}' {{ isset($text) && $text->name == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                      @error('name')
                            <span class='text-danger'>{{ $message }}</span>
                        @enderror
                </div>
                    <div class='input-group'>
                        <label>Title</label><br>
                        <input type='text' name='title' value='{{ old('title', $text->title) }}' />
                        @error('title')
                            <span class='text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class='mb-3'>
                      <label class='form-label fw-bold'>Image</label><br>
                      <div class='d-flex flex-column align-items-center'>
                          <img src='{{ asset($text->image) }}' alt='Uploaded Image' class='img-thumbnail mb-2' height='100' width='100'>
                          <div class='input-group'>
                              <input type='text' id='image_label' class='form-control' name='image'
                                  placeholder='Select an image...' aria-label='Image'>
                              <button class='btn btn-outline-secondary' type='button' id='button-image'>Select</button>
                          </div>
                             @error('image')
                            <span class='text-danger'>{{ $message }}</span>
                        @enderror
                      </div>
                  </div>
                    <div class='input-group'>
                        <label>Updated_at</label><br>
                        <input type='date' name='updated_at' value='{{ old('updated_at', $text->updated_at) }}' />
                        @error('updated_at')
                            <span class='text-danger'>{{ $message }}</span>
                        @enderror
                    </div>
            <button type="submit" class="btn btn-primary">Update</button>
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