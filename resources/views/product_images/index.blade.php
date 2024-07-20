@extends('layouts.app')

@section('header', __('product_images'))
@section('subheader', $product->name)

@section('content')
<div class="row mb-2">
    <div class="col-md-2 mt-1">
        <a class="btn btn-light border border-dark w-100" href="{{ route('products.product_images.create', ['product_id' => $product->id]) }}">
            @lang('Create :name', ['name' => __('product_image')])
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle rounded-3">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">@lang('validation.attributes.name')</th>
                    <th scope="col">@lang('validation.attributes.is_selected')</th>
                    <th scope="col">@lang('validation.attributes.gallery_order')</th>
                    <th scope="col">@lang('validation.attributes.created_at')</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productImages as $productImage)
                <tr>
                    <td><a href="{{ '' }}" target="_blank"><img src="{{ '' }}" class="img-fluid max-width-32-px"></a></td>
                    <td>{{ $productImage->name }}</td>
                    <td>{{ $productImage->selected_at ? '✔️' : '❌' }}</td>
                    <td>{{ $productImage->gallery_order }}</td>
                    <td>{{ $productImage->created_at }}</td>
                    <td>
                        <a class="btn btn-light border border-dark w-100" href="{{ route('products.product_images.edit', ['product_id' => $product->id, 'name' => $productImage->name]) }}">
                            @lang('Edit')
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger border border-dark w-100" href="{{ route('products.product_images.destroy', ['product_id' => $product->id, 'name'=>$product->name]) }}">
                            @lang('Delete')
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection