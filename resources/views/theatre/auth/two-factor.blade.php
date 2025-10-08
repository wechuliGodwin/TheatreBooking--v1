<!-- views/theatre/auth/two-factor.blade.php (new blade for 2FA) -->
@extends('layout.app')

@section('title','Two-Factor Authentication')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">Enter 2FA Code</div>
                <div class="card-body">
                    <p>A verification code has been sent to your email.</p>
                    <form method="POST" action="{{ route('2fa.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Code</label>
                            <input name="code" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection