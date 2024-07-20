@extends('layouts.app')

@section('header', __('Properties'))
@section('subheader', $product->name)

@section('content')
    @include('product_properties._form', [
        'product' => $product,
        'productPropertiesText' => $productPropertiesText,
    ])
@endsection
