@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Users List</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Unread Notifications</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <a href="{{ route('user.impersonate', $user->id) }}">{{ $user->name }} </a>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->notifications_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
