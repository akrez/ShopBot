@extends('layouts.blog')

@section('favicon', \Arr::get($data, 'logo.url'))
@section('title', \Arr::get($data, 'name') . (\Arr::get($data, 'short_description') ? ' | ' . \Arr::get($data,
    'short_description') : ''))

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @if (\Arr::get($data, 'logo.url'))
                <div class="row pb-2">
                    <div class="col-sm-12">
                        <a href="" style="text-align: center;">
                            <img class="w-100 rounded" alt="{{ \Arr::get($data, 'name') }}"
                                src="{{ \Arr::get($data, 'logo.url') }}" style="margin: auto;">
                        </a>
                    </div>
                </div>
            @endif
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="btn-group-vertical w-100" role="group">
                        @foreach (collect(Arr::get($data, 'products', []))->pluck('product_tags')->flatten()->unique()->toArray() as $tagKey => $tag)
                            <input type="checkbox" class="btn-check" name="vbtn-radio" id="vbtn-checbox-{{ $tagKey }}"
                                autocomplete="off">
                            <label class="btn btn-outline-dark" for="vbtn-checbox-{{ $tagKey }}">
                                <h4>
                                    {{ $tag }}
                                </h4>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 mb-2">
            <div class="pb-2">
                <h1 class="d-inline-block">{{ \Arr::get($data, 'name') }}</h1>
                <h2 class="d-inline-block ms-2 text-secondary">{{ \Arr::get($data, 'short_description') }}</h2>
            </div>
            <div class="container-fluid">
                <div class="row equal">
                    @foreach (Arr::get($data, 'products', []) as $product1)
                        @foreach (Arr::get($data, 'products', []) as $product)
                            <div class="thumbnail border pt-3 pb-3 col-sm-6 col-md-4 col-lg-3">
                                @if (isset($product['images'][0]))
                                    <img class="w-100 pb-2 rounded" src="{{ $product['images'][0]['url'] }}"
                                        alt="{{ $product['name'] }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold pb-2">{{ $product['name'] }}</h5>
                                    <p class="card-text">
                                        @foreach ($product['product_properties'] as $property)
                                            <strong>{{ $property['property_key'] }}:</strong>
                                            {{ implode(', ', $property['property_values']) }}<br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
