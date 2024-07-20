@section('content')
<form enctype="multipart/form-data"
    action="{{ $action }}"
    method="POST">
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
    ])
    @include('components/formBuilder', [
        'name' => 'gallery_order',
        'label' => __('validation.attributes.gallery_order'),
        'errorsArray' => $errors->get('gallery_order'),
        'value' => isset($gallery) ? $gallery->gallery_order : '',
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
    ])
    @include('components/formBuilder', [
        'type' => 'submit',
        'name' => 'submit',
        'size' => 2,
        'class' => 'btn btn-primary w-100',
        'label' => isset($gallery) ? __('Edit') : __('Create'),
    ])
</form>
