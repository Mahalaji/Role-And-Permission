@extends('Backend.layouts.app')
<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@section('content')
<main id="main" class="main">
<h1 class="header">Edit Test</h1>
<form class="simple" method="post" action="/Test/update" enctype="multipart/form-data">
<div class="form1">
    @csrf
    @method('POST')

    <input type="hidden" name="tablename" value="test">

     <input type='hidden' name='id' value='{{ $item->id }}' /> 
                  <div class='input-group'>
                      <label>Name</label><br>
                      <input type='text' name='name' value='{{ $item->name }}' />
                  </div> 
                  <div class='input-group'>
                      <label>Title</label><br>
                      <input type='text' name='title' value='{{ $item->title }}' />
                  </div>
                  <div class='mb-3'>
                      <label class='form-label fw-bold'>Image</label><br>
                      <div class='d-flex flex-column align-items-center'>
                          <img src='{{ asset($item->image) }}' alt='Uploaded Image' class='img-thumbnail mb-2' height='100' width='100'>
                          <div class='input-group'>
                              <input type='text' id='image_label' class='form-control' name='image'
                                  placeholder='Select an image...' aria-label='Image'>
                              <button class='btn btn-outline-secondary' type='button' id='button-image'>Select</button>
                          </div>
                      </div>
                  </div> 
                  <div class='input-group'>
                      <label>Updated_at</label><br>
                      <input type='date' name='updated_at' value='{{ $item->updated_at }}' />
                  </div>
    <button type="submit" class="btn btn-primary">Update</button>
</div>
</form>
</main>
@endsection