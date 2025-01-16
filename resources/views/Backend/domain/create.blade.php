<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Domain Add</h1>
<form class="simple" method="post" action="/adddomain" enctype="multipart/form-data">
    <div class="form1">
        @csrf
        <div id="first">
            <div class="input-group">
                <label>Domain Name</label><br>
                <input type="text" id="domainname" name="domainname">
            </div>
            <p>@error('domainname'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Company</label>
                <input type="text" id="companyname" name="companyname" onkeyup="lettersOnly(this)">
            </div>
            <p>@error('companyname'){{$message}}@enderror</p>
        </div>


        <div class="input-group">
            <label>Mail Header</label><br><br>
            <textarea id="mailheader" name="mailheader">
         </textarea>
        </div>
        <p>@error('mailheader'){{$message}}@enderror</p>
        <div class="input-group">
            <label>Mail Footer</label><br><br>
            <textarea id="mailfooter" name="mailfooter">
         </textarea>
        </div>
        <p>@error('mailfooter'){{$message}}@enderror</p>
        <div id="second">
            <div class="input-group">
                <label>Server Address</label>
                <input type="text" id="serveraddress" name="serveraddress">
            </div>
            <p>@error('serveraddress'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Port</label>
                <input type="text" id="port" name="port">
            </div>
            <p>@error('port'){{$message}}@enderror</p>
        </div>
        <div id="third">
            <div class="input-group">
                <label>Authenticate</label>
                <input type="text" id="authentication" name="authentication">
            </div>
            <p>@error('authentication'){{$message}}@enderror</p>
            <div class="input-group">
                <label>User Name</label>
                <input type="text" id="username" name="username">
            </div>
            <p>@error('username'){{$message}}@enderror</p>
        </div>

        <div id="fourth">
            <div class="input-group">
                <label>Password</label>
                <input type="text" id="password" name="password">
            </div>
            <p>@error('password'){{$message}}@enderror</p>
        <div class="input-group">
            <label>To Mail Id</label>
            <input type="email" id="tomailid" name="tomailid">
        </div>
        <p>@error('tomailid'){{$message}}@enderror</p>
    </div>
    <div class="submit">
        <button type="submit" class="btn" name="update">Add Domain</button>
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
<script>
ClassicEditor
    .create(document.querySelector('#mailheader'))
    .catch(error => {
        console.error(error);
    });
editor.resize(300, 500);
</script>
<script>
CKEDITOR.replace('mailheader')
</script>
<script>
ClassicEditor
    .create(document.querySelector('#mailfooter'))
    .catch(error => {
        console.error(error);
    });
editor.resize(300, 600);
</script>
<script>
CKEDITOR.replace('mailfooter')
</script>
@endsection