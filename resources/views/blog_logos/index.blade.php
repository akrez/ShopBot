@extends('layouts.app')

@section('header', __('blog_logos'))
@section('subheader', \App\Facades\ActiveBlog::name())

@section('content')
    <div class="row mb-2">
        <div class="col-md-2 mt-1">
            <a class="btn btn-light border border-dark w-100"
                href="{{ route('blog_logos.create') }}">
                @lang('Create :name', ['name' => __('blog_logo')])
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle rounded-3 text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">@lang('validation.attributes.name')</th>
                        <th scope="col">@lang('validation.attributes.is_selected')</th>
                        <th scope="col">@lang('validation.attributes.gallery_order')</th>
                        <th scope="col">@lang('validation.attributes.created_at')</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($blogLogos as $blogLogo)
                        <tr dir="ltr">
                            <td><a href="{{ $blogLogo->getUrl() }}" target="_blank"><img
                                        src="{{ $blogLogo->getUrl() }}" class="img-fluid max-width-38-px"></a></td>
                            <td>{{ $blogLogo->name }}</td>
                            <td>{{ $blogLogo->selected_at ? '✔️' : '❌' }}</td>
                            <td>{{ $blogLogo->gallery_order }}</td>
                            <td>{{ $blogLogo->created_at }}</td>
                            <td>
                                <a class="btn btn-light border border-dark w-100"
                                    href="{{ route('blog_logos.edit', ['name' => $blogLogo->name]) }}">
                                    @lang('Edit')
                                </a>
                            </td>
                            <td>
                                <form
                                    action="{{ route('blog_logos.destroy', ['name' => $blogLogo->name]) }}"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger border border-dark w-100">
                                        @lang('Delete')
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
