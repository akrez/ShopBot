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
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bots as $bot)
                        <tr>
                            <td>{{ $bot->token }}</td>
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
