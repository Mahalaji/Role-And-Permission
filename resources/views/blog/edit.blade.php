<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@extends('layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Blog Edit</h1>
<form class="simple" method="post" action="/update" enctype="multipart/form-data">
    <div class="form1">
        @csrf
        <div id="first">
        <div class="input-group">
            <label>Title</label><br>
            <input type="text" id="title" name="title" value="{{ old('title', $blog->title) }}">
        </div>
        <p>@error('title'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Author_Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $blog->name) }}" onkeyup="lettersOnly(this)">
        </div>
        <p>@error('name'){{$message}}@enderror</p>
    </div>
        <div id="second">
        <div class="input-group">
            <label>Seo Title</label>
            <input type="text" id="seo_title" name="seo_title" value="{{ old('seo_title', $blog->seo_title) }}">
        </div>
        <p>@error('seo_title'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Meta Keyword</label>
            <input type="text" id="meta_keyword" name="meta_keyword"
                value="{{ old('meta_keyword', $blog->meta_keyword) }}">
        </div>
        <p>@error('meta_keyword'){{$message}}@enderror</p>
        </div>
        <div id="third">
        <div class="input-group">
            <label>Meta Description</label>
            <input type="text" id="meta_description" name="meta_description"
                value="{{ old('meta_description', $blog->meta_description) }}">
        </div>
        <p>@error('meta_description'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Seo Robat</label>
            <input type="text" id="seo_robat" name="seo_robat" value="{{ old('seo_robat', $blog->seo_robat) }}">
        </div>
        <p>@error('seo_robat'){{$message}}@enderror</p>
    </div>
        <div class="input-group">

            <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $blog->created_at) }}"
                readonly>
            <input type="hidden" id="updated_at" name="updated_at" readonly>
            <input type="hidden" id="id" name="id" value="{{ old('id', $blog->id) }}" readonly>

        </div>
        <div class="input-group">
            <label>Blog Category</label>
            <select id="category_id" name="category_id">
                <option value="">Select blog Category</option>
                @foreach($titles as $title)
                <option value="{{ $title->id }}">{{ $title->title }}
                </option>
                @endforeach
            </select>
        </div>
        <p>@error('category_id'){{$message}}@enderror</p>
        <div class="input-group">
            <label>Language</label>
            <select id="language" name="language">
                <option value="">Select Language</option>
                @foreach($language as $languages)
                <option value="{{ $languages->id }}">{{ $languages->languagename }}
                </option>
                @endforeach
            </select>
        </div>
        <p>@error('language'){{$message}}@enderror</p>
        <div class="input-group">
            <label>Domain</label>
            <select id="domain" name="domain">
                <option value="">Select Domain</option>
                @foreach($domain as $domains)
                <option value="{{ $domains->id }}">{{ $domains->domainname }}
                </option>
                @endforeach
            </select>
        </div>
        <p>@error('domain'){{$message}}@enderror</p>
        <div class="input-group">
            <label>Upload Image:</label><br>
            <img src="{{ asset( $blog->image) }}" alt="" height="100" width="100">
            <input type="file" name="image" id="image" />
        </div>
        <p>@error('image'){{$message}}@enderror</p>
        <div class="input-group">
            <label for="editor">Description</label>
            <textarea id="editor" name="description">{{ old('description', $blog->description) }}</textarea>
        </div>

        <p>@error('description'){{$message}}@enderror</p>
        <div class="submit">
            <button type="submit" class="btn" name="update">Update Blog</button>
        </div>
    </div>
</form>
</main>
@endsection
@section('scripts')
<script>
function lettersOnly(input) {
    var regex = /[^a-z ]/gi;
    input.value = input.value.replace(regex, "");
}
</script>
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });
editor.resize(300, 500);
</script>
<script>
CKEDITOR.replace('editor')
</script>
@endsection