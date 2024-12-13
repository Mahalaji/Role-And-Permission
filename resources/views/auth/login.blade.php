<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>
<body>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="wrapper">
            <img src="https://www.absglobaltravel.com/public/images/footer-abs-logo.webp" height="100" style="margin-left: 80px;">

            <div class="input-box">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="input-box">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="remember-forgot">
                <label>
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember Me') }}
                </label>

                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                @endif
            </div>

            <button type="submit" class="btn">{{ __('Login') }}</button>

            <div class="register-link">
                <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
            </div>
        </div>
    </form>
</body>
</html>
