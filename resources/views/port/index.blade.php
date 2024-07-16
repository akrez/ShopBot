@extends('layouts.app')

@section('header', __('Port'))

@section('content')
    <div class="row mb-2">
        <div class="col-md-4 mt-1">
            <form enctype="multipart/form-data" action="{{ route('port.import') }}" method="POST" class="mb-1">
                @csrf
                <div class="input-group">
                    <input name="port" type="file" class="form-control">
                    <button type="submit" class="input-group-text btn btn-info border border-dark">
                        @lang('Import :name', ['name' => __('Products')])
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-6 mt-1">
        </div>
        <div class="col-md-2 mt-1">
            <a class="btn btn-info border border-dark w-100" href="{{ route('port.export') }}">
                @lang('Export :name', ['name' => __('Products')])
            </a>
        </div>
    </div>
@endsection
