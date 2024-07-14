@extends('layouts.app')

@section('header', __('Products'))

@section('content')
<div class="row mb-2">
    <div class="col-md-2 pull-right">
        <a class="btn btn-light border border-dark w-100" href="{{ route('products.create') }}">
            @lang("Create :name", ['name' => __('Product')])
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden">
            <thead class="table-dark">
                <tr>
                    <th scope="col">@lang('validation.attributes.code')</th>
                    <th scope="col">@lang('validation.attributes.name')</th>
                    <th scope="col">@lang('validation.attributes.status')</th>
                    <th scope="col">@lang('validation.attributes.created_at')</th>
                    <th scope="col">@lang('validation.attributes.updated_at')</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->product_status->trans() }}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>{{ $product->updated_at }}</td>
                    <td>
                        <a class="btn btn-info border border-dark w-100" href="{{ route('products.edit',['id' => $product->id]) }}">
                            @lang('Edit')
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection