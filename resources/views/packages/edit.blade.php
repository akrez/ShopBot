@extends('layouts.app')

@section('header', __('Edit :name', ['name' => __('Package')]))
@section('subheader', $package->name)

@section('content')
    @include('packages._form', [
        'package' => $package,
    ])
@endsection
