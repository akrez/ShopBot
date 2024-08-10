@extends('layouts.app')

@section('header', __('Create :name', ['name' => __('blog_logo')]))
@section('subheader', \App\Facades\ActiveBlog::name())

@section('content')
@include('blog_logos._form', [
'action' => route('blog_logos.store')
])
@endsection
