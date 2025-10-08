@extends('layout.app')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">Create New User</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Users
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">User Information</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST" novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Full name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="email@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="form-select @error('role') is-invalid @enderror">
                            <option value="">Select a role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="surgeon" {{ old('role') == 'surgeon' ? 'selected' : '' }}>Surgeon</option>
                            <option value="nurse" {{ old('role') == 'nurse' ? 'selected' : '' }}>Nurse</option>
                            <!-- <option value="auditor" {{ old('role') == 'auditor' ? 'selected' : '' }}>Auditor</option> -->
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimum 8 characters">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimum 8 characters</div>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Repeat password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="alert alert-info">
                    <h6 class="mb-2">Default Settings</h6>
                    <ul class="mb-0 small">
                        <li>User will be created as <strong>Active</strong>.</li>
                        <li>Email will be automatically <strong>Verified</strong>.</li>
                        <li>User can log in immediately after creation.</li>
                    </ul>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
