@section('content')
    <form enctype="multipart/form-data"
        action="{{ route('products.product_properties.store', ['product_id' => $product->id]) }}" method="POST">
        @csrf
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'property_value',
            'value' => $productPropertiesText,
            'label' => '',
            'hint' => implode("\n", [
                __('Separate :names using :characters characters', [
                    'names' => __('validation.attributes.property_value'),
                    'characters' => implode(' ', \App\Support\ArrayHelper::SEPARATOR_KEY_VALUES + ["\t" => 'Tab']),
                ]),
            ]),
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'label' => $productPropertiesText ? __('Edit') : __('Create'),
            'size' => 2,
            'class' => 'btn w-100 ' . ($productPropertiesText ? 'btn-primary' : 'btn-success'),
        ])
    </form>
@endsection
