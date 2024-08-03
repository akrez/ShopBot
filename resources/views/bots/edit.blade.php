@extends('layouts.app')

@section('header', __('Edit :name', ['name' => __('Bot')]))
@section('subheader', $bot->name)

@section('content')
    @include('bots._form', [
        'bot' => $bot,
    ])
@endsection
