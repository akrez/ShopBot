@extends('layouts.app')

@section('header', __('Port'))
@section('subheader', $sheetName->trans())

@section('content')
    <div class="row mb-2">
        <div class="col-md-2 mt-1">
            <a class="btn btn-info border border-dark w-100"
                href="{{ route('ports.export', ['sheetName' => $sheetName->value]) }}">
                @lang('Export :name', ['name' => $sheetName->trans()])
            </a>
        </div>
        <div class="col-md-4 mt-1">
            <form enctype="multipart/form-data" action="{{ route('ports.import', ['sheetName' => $sheetName->value]) }}"
                method="POST" class="mb-1">
                @csrf
                <div class="input-group">
                    <input name="port" type="file" class="form-control">
                    <button type="submit" class="input-group-text btn btn-info border border-dark">
                        @lang('Import :name', ['name' => $sheetName->trans()])
                    </button>
                </div>
            </form>
        </div>
    </div>
    @if ($responseBuilders)
        <div class="row mb-2">
            <div class="col-md-12">
                @foreach ($responseBuilders as $responseBuilder)
                    <div role="alert"
                        class="alert {{ !$responseBuilder->isSuccessful() ? 'alert-danger' : ($responseBuilder->getStatus() == 201 ? 'alert-success' : 'alert-info') }} alert-dismissible fade show">
                        @include($responseBuilderView, [
                            'sheetName' => $sheetName,
                            'responseBuilder' => $responseBuilder,
                        ])
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
