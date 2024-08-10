@extends('layouts.app')

@section('header', __('Tags'))
@section('subheader', $product->name)

@section('content')
    @include('product_tags._form', [
        'product' => $product,
        'productTagsText' => $productTagsText,
    ])
@endsection
