@extends('Backend.layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">

    <link href="{{ asset('vendor/file-manager/css/file-manager.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

</head>

<body>
<div class="info" style="background: white;">

    <div class="container">

        <h2>File Manager </h2>

        <div class="row">

            <div class="col-md-12" id="fm-main-block">

                <div id="fm"></div>

            </div>

        </div>

    </div>
</div>
    @endsection
    @section('scripts')
  

    <!-- File manager -->

    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

    <script>

      document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');

  

        fm.$store.commit('fm/setFileCallBack', function(fileUrl) {

          window.opener.fmSetLink(fileUrl);

          window.close();

        });

      });

    </script>
   @endsection