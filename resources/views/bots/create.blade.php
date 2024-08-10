@extends('layouts.app')

@section('header', __('Create :name', ['name' => __('Bot')]))

@section('content')
    @include('bots._form')
@endsection
