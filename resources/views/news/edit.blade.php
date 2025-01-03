<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@extends('layouts.app')
@section('content')
<main id="main" class="main">
    <h1 class="header">News Edit</h1>
    <div class="form1">
        <form class="simple" method="post" action="/updatenews" enctype="multipart/form-data">
            @csrf
            <div id="first">
            <div class="input-group">
                <label>Title</label><br>
                <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}">
            </div>
            <p>@error('title'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Author_Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $news->name) }}"
                    onkeyup="lettersOnly(this)">
            </div>
            <p>@error('name'){{$message}}@enderror</p>
            </div>
            <div id="second">
            <div class="input-group">
                <label>Seo Title</label>
                <input type="text" id="seo_title" name="seo_title" value="{{ old('seo_title', $news->seo_title) }}">
            </div>
            <p>@error('seo_title'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Meta Keyword</label>
                <input type="text" id="meta_keyword" name="meta_keyword"
                    value="{{ old('meta_keyword', $news->meta_keyword) }}">
            </div>
            <p>@error('meta_keyword'){{$message}}@enderror</p>
            </div>
            <div id="third">
            <div class="input-group">
                <label>Meta Description</label>
                <input type="text" id="meta_description" name="meta_description"
                    value="{{ old('meta_description', $news->meta_description) }}">
            </div>
            <p>@error('meta_description'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Seo Robat</label>
                <input type="text" id="seo_robat" name="seo_robat" value="{{ old('seo_robat', $news->seo_robat) }}">
            </div>
            <p>@error('seo_robat'){{$message}}@enderror</p>
            </div>
            <div class="input-group">

                <input type="hidden" id="created_at" name="created_at"
                    value="{{ old('created_at', $news->created_at) }}" readonly>
                <input type="hidden" id="updated_at" name="updated_at"
                    value="{{ old('updated_at', $news->updated_at) }}" readonly>
                <input type="hidden" id="id" name="id" value="{{ old('id', $news->id) }}" readonly>

            </div>
            <div class="input-group">
                <label>News Category</label>
                <select id="category_id" name="category_id" value="{{ old('category_id', $news->category_id) }}">
                    <option value="">Select News Category</option>
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
                <label>Email</label>
                <input type="text" id="email" name="email" value="{{ old('email', $news->email) }}">
            </div>
            <p>@error('email'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Upload Image:</label><br>
                <img src="{{ asset( $news->news_image) }}" alt="" height="100" width="100">
                <input type="file" name="news_image" id="news_image"
                    value="{{ old('news_image', $news->news_image) }}" />
            </div>
            <p>@error('news_image'){{$message}}@enderror</p>
            <div class="input-group">
                <label>Description</label>
                <textarea id="editor" name="description">{{ old('description', $news->description) }}
         </textarea>
            </div>
            <p>@error('description'){{$message}}@enderror</p>
            <div class="submit">
                <button type="submit" class="btn" name="update">Update News</button>
            </div>
        </form>
    </div>
</main>
@endsection
@section('scripts')
<script>
function lettersOnly(input) {
    var regex = /[^a-z ]/gi;
    input.value = input.value.replace(regex, "");
}
</script>
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