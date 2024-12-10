@extends('layouts.app')

@section('content')
<div class="container-fluid" style="height:100vh;">
    <div class="row justify-content-center align-items-center" style="height:100%;">
        <div class="col-8" style="height:60vh;">
            <div class="row justify-content-center align-items-center" >
                <div class="col-3">
                    <div class="row justify-content-center align-items-center" style="height:60vh; background-color:#0798C1;border-radius: 10px 0px 0px 10px;">
                        <img src="{{asset('assets/logo/logo.png')}}" alt="">
                    </div>
                </div>
                <div class="col-6" style="height:60vh;border: 1px solid #d1d1d1;border-radius: 0px 10px 10px 0px;">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <form method="POST" class="col-10" action="{{ route('login') }}">
                            @csrf
                            <center><a href="#" style="pointer-events: none;color:black;font-size: 40px;">Ant<b>Tendance</b></a></center>
                            <p class="login-box-msg text-center">Login in to start your session</p>
                            
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
                            
                            <div class="form-group mb-4">
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="row mb-4">
                                <button type="submit" class="btn btn-block" style="background-color:#0798C2;color:white">Login</button>
                            </div>

                            <center>Not a member? <a href="wa.me/6285765322281">Contact Us</a></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
