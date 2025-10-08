@extends('layout.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">Edit User</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Users
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- User Details Card --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">User Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $user->name) }}" 
                               class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role *</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="surgeon" {{ $user->role == 'surgeon' ? 'selected' : '' }}>Surgeon</option>
                            <option value="nurse" {{ $user->role == 'nurse' ? 'selected' : '' }}>Nurse</option>
                            <!-- <option value="auditor" {{ $user->role == 'auditor' ? 'selected' : '' }}>Auditor</option> -->
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <p>
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Created At</label>
                        <p class="text-muted">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email Verified</label>
                        <p>
                            @if($user->email_verified_at)
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i> Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-times-circle me-1"></i> Not Verified
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Password Card --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Change Password</h5>
            <button type="button" class="btn btn-sm btn-secondary" id="togglePasswordForm">
                <i class="fas fa-key me-1"></i> Change Password
            </button>
        </div>
        <div class="card-body" id="passwordForm" style="display: none;">
            <form action="{{ route('users.update-password', $user) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">New Password *</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <div class="form-text">Minimum 8 characters</div>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-key me-2"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- User Actions Card --}}
    @if(Auth::id() !== $user->id)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">User Actions</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                {{-- Toggle Status --}}
                <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                        @if($user->is_active)
                            <i class="fas fa-ban me-2"></i> Deactivate User
                        @else
                            <i class="fas fa-check-circle me-2"></i> Activate User
                        @endif
                    </button>
                </form>

                {{-- Delete User --}}
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i> Delete User
                    </button>
                </form>
            </div>
            <p class="text-muted small mt-3">
                <strong>Note:</strong> Deactivating a user prevents login. Deleting permanently removes their data.
            </p>
        </div>
    </div>
    @endif
</div>

{{-- JS to toggle password form --}}
<script>
    document.getElementById('togglePasswordForm').addEventListener('click', function() {
        let form = document.getElementById('passwordForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
</script>
@endsection
