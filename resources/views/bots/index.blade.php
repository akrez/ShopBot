@extends('layouts.app')

@section('header', __('Bots'))

@section('content')
    <div class="row mb-2">
        <div class="col-md-2 mt-1">
            <a class="btn btn-light border border-dark w-100" href="{{ route('bots.create') }}">
                @lang('Create :name', ['name' => __('Bot')])
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover table-bordered align-middle text-center rounded-3">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">@lang('validation.attributes.token')</th>
                        <th scope="col">@lang('Upload :name Attribute', ['name' => __('Bot')])</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bots as $bot)
                        <tr>
                            <td class="font-monospace" dir="ltr">{{ Str::mask($bot->token, '*', 14, 22) }}</td>
                            <td>
                                <form action="{{ route('bots.upload_attribute', ['id' => $bot->id]) }}" method="post">
                                    @csrf

                                    <div class="btn-group w-100" role="group" aria-label="Basic example">
                                        <label for="btn-name-{{ $bot->id }}"
                                            class="btn btn-primary border border-dark">@lang('validation.attributes.name')</label>
                                        <label for="btn-short_description-{{ $bot->id }}"
                                            class="btn btn-primary border border-dark">@lang('validation.attributes.short_description')</label>
                                        <label for="btn-description-{{ $bot->id }}"
                                            class="btn btn-primary border border-dark">@lang('validation.attributes.description')</label>
                                    </div>

                                    <input id="btn-name-{{ $bot->id }}" type="submit" name="attribute" value="name"
                                        class="d-none">
                                    <input id="btn-short_description-{{ $bot->id }}" type="submit" name="attribute"
                                        value="short_description" class="d-none">
                                    <input id="btn-description-{{ $bot->id }}" type="submit" name="attribute"
                                        value="description" class="d-none">
                                </form>
                            </td>
                            <td>
                                <a class="btn btn-light border border-dark w-100"
                                    href="{{ route('bots.edit', ['id' => $bot->id]) }}">
                                    @lang('Edit')
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('bots.destroy', ['id' => $bot->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger border border-dark w-100">
                                        @lang('Delete')
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="table-warning">
                            <td colspan="99">
                                @lang('Not Found')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
