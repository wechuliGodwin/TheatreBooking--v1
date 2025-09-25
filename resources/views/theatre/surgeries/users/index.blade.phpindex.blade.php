@extends('surgeries.layout')
@section('title','Users')
@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Users</h3>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="table-responsive card">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @foreach($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->role }}</td>
          <td>
            <a class="btn btn-sm btn-secondary" href="{{ route('users.edit', $u) }}">Edit</a>
            <form method="POST" action="{{ route('users.destroy', $u) }}" style="display:inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection
