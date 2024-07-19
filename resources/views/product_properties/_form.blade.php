@php
    $productPropertiesCollection = collect($productProperties);
    $isNew = $productPropertiesCollection->isEmpty();
@endphp

@section('content')
@dd($productPropertiesCollection)
    <form enctype="multipart/form-data" action="{{ route('products.product_properties.store', ['product_id' => $product->id]) }}"
        method="POST">
        @csrf
        @include('components/formBuilder', [
            'type' => 'textarea',
            'name' => 'property_key_values',
            'value' => $productPropertiesCollection->pluck('tag_name')->implode("\n"),
            'label' => '',
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'label' => $isNew ? __('Create') : __('Edit'),
            'size' => 2,
            'class' => 'btn w-100 ' . ($isNew ? 'btn-success' : 'btn-primary'),
        ])
    </form>
@endsection
