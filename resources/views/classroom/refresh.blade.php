@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">更新課表</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('classroom.refresh') }}">
                            @csrf
                            <div class="form-group">
                                <label for="smtr">學期</label>
                                <input type="number" class="form-control @error('smtr') is-invalid @enderror" id="smtr"
                                    name="smtr" value="{{ old('smtr', '1132') }}" required>
                                @error('smtr')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">更新</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection