@section('content')
    <div class="row">
        <div class="col-md-4">
            <form enctype="multipart/form-data" action="{{ $action }}" method="POST">
                @csrf
                @if (isset($gallery))
                    @method('PUT')
                @endif
                @include('components/formBuilder', [
                    'type' => 'file',
                    'name' => 'file',
                    'label' => __('validation.attributes.file'),
                    'errorsArray' => $errors->get('file'),
                    'visible' => !isset($gallery),
                    'size' => 12,
                ])
                @include('components/formBuilder', [
                    'name' => 'gallery_order',
                    'label' => __('validation.attributes.gallery_order'),
                    'errorsArray' => $errors->get('gallery_order'),
                    'value' => isset($gallery) ? $gallery->gallery_order : '',
                    'size' => 12,
                ])
                @include('components/formBuilder', [
                    'type' => 'select',
                    'name' => 'is_selected',
                    'label' => __('validation.attributes.is_selected'),
                    'errorsArray' => $errors->get('is_selected'),
                    'value' => isset($gallery) ? ($gallery->selected_at ? '1' : '') : '',
                    'selectOptions' => [
                        '' => __('No'),
                        '1' => __('Yes'),
                    ],
                    'size' => 12,
                ])
                @include('components/formBuilder', [
                    'type' => 'submit',
                    'name' => 'submit',
                    'size' => 6,
                    'class' => 'btn btn-primary w-100',
                    'label' => isset($gallery) ? __('Edit') : __('Create'),
                ])
            </form>
        </div>
        <div class="col-md-4">
            @if (isset($gallery))
                <a class="card text-bg-dark overflow-hidden" href="{{ $gallery->getUrl() }}" target="_blank">
                    <img src="{{ $gallery->getUrl() }}" class="card-img">
                    <div class="card-img-overlay d-flex flex-column justify-content-end p-0">
                        <h5 dir="ltr" class="card-title bg-dark p-1 m-0 pt-2 bg-opacity-75">{{ $gallery->name }}</h5>
                    </div>
                </a>
            @endif
        </div>
    </div>
@endsection
