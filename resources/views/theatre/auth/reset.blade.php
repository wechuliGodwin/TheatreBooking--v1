<!-- views/theatre/auth/reset.blade.php -->
@extends('layout.app')
@section('title','Set New Password')
@section('content')
<div class="container">
  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="mb-3"><label>Email</label><input name="email" value="{{ $email ?? old('email') }}" class="form-control" required></div>
    <div class="mb-3"><label>Password</label><input name="password" class="form-control" required></div>
    <div class="mb-3"><label>Confirm</label><input name="password_confirmation" class="form-control" required></div>
    <button class="btn btn-success">Reset Password</button>
  </form>
</div>
@endsection