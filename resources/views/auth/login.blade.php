@extends('layouts.app')
@section('css')
<style>
.logo-section {
    background-color: #0798C1;
    border-radius: 10px 0 0 10px;
}

.form-section {
    border: 1px solid #d1d1d1; 
    border-radius: 0 10px 10px 0;
}

@media (max-width: 576px) {
    .logo-section {
        border-radius: 10px 10px 0px 0px ; /* Remove border radius for mobile screens */
    }
    .form-section {
        border-radius: 0px 0px 10px 10px;
    }
    .responsive-padding {
        padding: 10px; /* Add margin for mobile view */
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid vh-100 " >
    <div class="row justify-content-center align-items-center h-100 ">
        <div class="col-12 col-md-10 col-lg-6 responsive-margin">
            <div class="row justify-content-center align-items-stretch responsive-padding">
                <!-- Left Logo Section -->
                <div class="col-12 col-md-4 p-0">
                    <div class="d-flex justify-content-center align-items-center h-100 logo-section">
                        <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo" class="img-fluid">
                    </div>
                </div>

                <!-- Login Form Section -->
                <div class="col-12 col-md-8 p-4 form-section">
                    <div class="d-flex flex-column justify-content-center h-100">
                        <form method="POST" action="{{ route('login') }}" class="w-100">
                            @csrf
                            <div class="text-center mb-4">
                                <a href="#" style="pointer-events: none; color: black; font-size: 40px;">Ant<b>Tendance</b></a>
                            </div>
                            <p class="text-center mb-4">Login in to start your session</p>

                            <!-- Email Input -->
                            <div class="form-group mb-4">
                                <div class="input-group">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="form-group mb-4">
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mb-4">
                                <button type="submit" class="btn btn-block text-white" style="background-color: #0798C2;">Login</button>
                            </div>

                            <div class="text-center">
                                Not a member? <a href="https://wa.me/6285765322281">Contact Us</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
