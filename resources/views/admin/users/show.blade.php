@extends('layouts.master')

@section('content')
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Details</li>
            </ol>
        </nav>
        <h5 class="mb-2">User Details</h5>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $user->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Upazila</th>
                            <td>{{ $user->upazila ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $user->city ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email Verified At</th>
                            <td>
                                @if($user->email_verified_at)
                                    {{ $user->email_verified_at->format('M d, Y H:i:s') }}
                                @else
                                    Not Verified
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>OTP Code</th>
                            <td>{{ $user->otp_code ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>OTP Expires At</th>
                            <td>
                                @if($user->otp_expires_at)
                                    {{ \Carbon\Carbon::parse($user->otp_expires_at)->format('M d, Y H:i:s') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Roles</th>
                            <td>
                                @if($user->hasRole('super_admin'))
                                    <span class="badge bg-danger">Super Admin</span>
                                @endif
                                @foreach($user->getRoleNames() as $role)
                                    @if($role !== 'super_admin')
                                        <span class="badge bg-info">{{ $role }}</span>
                                    @endif
                                @endforeach
                                @if(!$user->hasAnyRole($user->getRoleNames()->toArray()))
                                    <span class="badge bg-secondary">No Roles</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $user->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $user->updated_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
            </div>
        </div>
    </div>
@endsection
