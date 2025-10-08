@extends('layout.app')

@section('title','Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Name</label>
                            <input name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input name="password_confirmation" type="password" class="form-control" required>
                        </div>
                        <button class="btn btn-success">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
