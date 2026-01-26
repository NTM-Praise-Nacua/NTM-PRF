@extends('layouts.app')

@push('css')
    <style>
        .body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #003A69, #176ECF);
            /*background: #003A77;*/
            position: relative;
        }

        .wave-1 {
            width: 125%;
            height: 125%;
            background: radial-gradient(circle at 90% 80%, #1c92ff, #003A77);
            position: absolute;
            z-index: 5;
        }

        .wave-2 {
            width: 145%;
            height: 125%;
            border-radius: 50%;
            background: radial-gradient(circle at 95% 95%, #128dff, #003063);
            position: absolute;
            top: -50%;
            z-index: 4;
        }

        .wave-3 {
            width: 155%;
            height: 125%;
            border-radius: 50%;
            background: radial-gradient(circle at 87% 45%, #0a89ff, #002b59);
            position: absolute;
            top: -40%;
            z-index: 3;
        }

        .wave-4 {
            width: 165%;
            height: 125%;
            border-radius: 50%;
            background: radial-gradient(circle at 76% 62%, #0A89F1, #011A41);
            position: absolute;
            top: -35%;
            left: -25%;
            z-index: 2;
        }
    </style>
@endpush

@section('content')
<div class="container" style="z-index: 10;">
    <div class="row justify-content-center">
        <div class="col-md-5 card-wrapper">
            <div class="card rounded-4">
                {{-- <div class="card-header"></div> --}}
                <div class="d-flex justify-content-center align-items-end position-relative" style="height: 70px">
                    <h4 class="text-center">{{ __('Login') }}</h4>
                    <div class="border position-absolute bottom-0" style="width: 90%"></div>
                </div>

                <div class="card-body pb-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row" style="margin-bottom: {{ $errors->has('email') ? '2rem' : '0.5rem' }} !important; height: 40px;">
                            <div class="position-relative px-0 h-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="position-absolute left-0 ms-2 top-50" style="width: 20px; height: 20px; transform: translate(0, -50%);"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M320 312C386.3 312 440 258.3 440 192C440 125.7 386.3 72 320 72C253.7 72 200 125.7 200 192C200 258.3 253.7 312 320 312zM290.3 368C191.8 368 112 447.8 112 546.3C112 562.7 125.3 576 141.7 576L498.3 576C514.7 576 528 562.7 528 546.3C528 447.8 448.2 368 349.7 368L290.3 368z"/></svg>
                                <input type="email" class="form-control h-100 @error('email') is-invalid @enderror" style="padding-left: 2rem !important;" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: {{ $errors->has('password') ? '2rem' : '1rem' }} !important; height: 40px;">
                            <div class="position-relative px-0 h-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="position-absolute left-0 ms-2 top-50" style="width: 20px; height: 20px; transform: translate(0, -50%);"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M256 160L256 224L384 224L384 160C384 124.7 355.3 96 320 96C284.7 96 256 124.7 256 160zM192 224L192 160C192 89.3 249.3 32 320 32C390.7 32 448 89.3 448 160L448 224C483.3 224 512 252.7 512 288L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 288C128 252.7 156.7 224 192 224z"/></svg>
                                <input id="password" type="password" class="form-control h-100 @error('password') is-invalid @enderror" style="padding-left: 2rem !important;" placeholder="Password" name="password" required autocomplete="current-password">
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                            {{-- <div class="col-md-8"> --}}

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
