@extends('layouts.app')

@section('header', __('Create :name', ['name' => __('product_image')]))
@section('subheader', $product->name)

@section('content')
    @include('product_images._form', [
        'product' => $product,
        'action' => route('products.product_images.store', ['product_id' => $product->id])
    ])
@endsection
