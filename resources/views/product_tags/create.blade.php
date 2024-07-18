@extends('layouts.app')

@section('header', __('Tags'))

@section('content')
    @include('product_tags._form', [
        'product' => $product,
        'productTags' => $productTags,
    ])
@endsection
