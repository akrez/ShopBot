@extends('layouts.app')

@section('title', 'تجارت بدون مرز!')

@section('content')
    <div class="col-12">
        <div class="card card-md">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-7 text-center">
                        اگر کسب و کار شما اینترنتی نیست متاسفانه شما صاحب یک کسب و کار از رده خارج و رو به زوال هستید
                        اگر کسب و کارتان در اینترنت نباشد به زودی از بازار هم حذف خواهید شد
                    </div>
                    <div class="col-lg-4 m-auto p-0">
                        <img class="rounded-3 img-fluid shadow-lg" src="{{ asset('images/dashboard.avif') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
