<!-- views/theatre/auth/forgot.blade.php (new blade for forgot password) -->
@extends('layout.app')

@section('title','Forgot Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">Reset Password</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Email</label>
                            <input name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">Send Password Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection