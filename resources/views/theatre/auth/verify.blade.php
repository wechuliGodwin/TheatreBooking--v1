<!-- views/theatre/auth/verify.blade.php -->
@extends('surgeries.layout')

@section('title','Verify Email')

@section('content')
<div class="container">
  <div class="alert alert-info">Please verify your email address. Check your inbox for the verification link.</div>
  <form method="POST" action="{{ route('verification.resend') }}">
      @csrf
      <button class="btn btn-primary">Resend Verification Email</button>
  </form>
</div>
@endsection