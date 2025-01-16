@extends('Backend.layouts.app')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .error-container {
        text-align: center;
        background: #fff;
        padding: 40px;
        margin: 250px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
    }
    .error-container h1 {
        font-size: 72px;
        color: #dc3545;
        margin: 0;
    }
    .error-container p {
        font-size: 18px;
        color: #6c757d;
        margin: 10px 0 20px;
    }

</style>

<div class="error-container">
    <h1>403</h1>
    <p>Forbidden: You do not have permission to access this page.</p>
</div>
@endsection
