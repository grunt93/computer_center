@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">用戶管理</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>姓名</th>
                                <th>學號</th>
                                <th>電子郵件</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->student_id }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->email !== 'admin')
                                        <a href="{{ route('profile.users.show', $user) }}" class="btn btn-sm btn-info">查看</a>
                                        <a href="{{ route('profile.users.edit', $user) }}" class="btn btn-sm btn-primary">編輯</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection