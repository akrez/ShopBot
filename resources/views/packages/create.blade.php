@extends('layouts.app')

@section('header', __('Create :name', ['name' => __('Package')]))

@section('content')
    @include('packages._form', ['colors' => $colors])
@endsection
