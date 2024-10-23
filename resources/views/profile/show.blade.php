@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Name</h5>
            <p class="card-text">{{ $user->name }}</p>

            <h5 class="card-title">Email</h5>
            <p class="card-text">{{ $user->email }}</p>

            <h5 class="card-title">Member Since</h5>
            <p class="card-text">{{ $user->created_at->format('d M Y') }}</p>

            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">Edit Profile</a>
        </div>
    </div>
@endsection
