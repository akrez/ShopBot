@extends('layouts.app')

@section('header', __('Edit :name', ['name' => __('Product')]))
@section('subheader', $product->name)

@section('content')
    @include('product_images._form', [
        'product' => $product,
        'gallery' => $product,
        'action' => route('products.product_images.update', ['product_id' => $product->id, 'name' => $gallery->name]),
    ])
@endsection
