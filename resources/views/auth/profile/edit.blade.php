@extends('layouts.app')

@section('title', '編輯個人資料')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>編輯個人資料</span>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-sm">返回</a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">姓名</label>
                            <div class="col-md-6">
                                <input id="name" type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}" 
                                    required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="student_id" class="col-md-4 col-form-label text-md-end">學號</label>
                            <div class="col-md-6">
                                <input id="student_id" type="text" 
                                    class="form-control @error('student_id') is-invalid @enderror" 
                                    name="student_id" 
                                    value="{{ old('student_id', $user->student_id) }}" 
                                    required>
                                @error('student_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">電子郵件</label>
                            <div class="col-md-6">
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    更新資料
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 將學號自動轉為大寫
    $('#student_id').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    // 表單提交時確保學號為大寫
    $('form').on('submit', function() {
        let studentId = $('#student_id').val();
        $('#student_id').val(studentId.toUpperCase());
    });
});
</script>
@endpush