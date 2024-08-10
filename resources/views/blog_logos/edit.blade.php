@extends('layouts.app')

@section('header', __('Edit :name', ['name' => __('blog_logo')]))
@section('subheader', \App\Facades\ActiveBlog::name())

@section('content')
@include('blog_logos._form', [
'gallery' => $gallery,
'action' => route('blog_logos.update', ['name' => $gallery->name])
])
@endsection
