@extends('surgeries.layout')
@section('title','Reset Password')
@section('content')
<div class="container">
  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-3"><label>Email</label><input name="email" class="form-control" required></div>
    <button class="btn btn-primary">Send reset link</button>
  </form>
</div>
@endsection
