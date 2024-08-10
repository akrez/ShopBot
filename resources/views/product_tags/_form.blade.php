@section('content')
    <form enctype="multipart/form-data" action="{{ route('products.product_tags.store', ['product_id' => $product->id]) }}"
        method="POST">
        @csrf
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'tag_names',
            'value' => $productTagsText,
            'label' => '',
            'hints' => [
                __('Separate :names using :characters characters', [
                    'names' => __('Tags'),
                    'characters' => implode(' ', \App\Services\ProductTagService::NAME_SEPARATORS),
                ]),
            ],
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'label' => $productTagsText ? __('Edit') : __('Create'),
            'size' => 2,
            'class' => 'btn w-100 ' . ($productTagsText ? 'btn-primary' : 'btn-success'),
        ])
    </form>
@endsection
