@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($product) ? route('products.update', ['id' => $product->id]) : route('products.store') }}"
        method="POST">
        @csrf
        @if (isset($product))
            @method('PUT')
        @endif
        @include('components/formBuilder', [
            'name' => 'code',
            'value' => isset($product) ? $product->code : '',
        ])
        @include('components/formBuilder', [
            'name' => 'name',
            'value' => isset($product) ? $product->name : '',
        ])
        @include('components/formBuilder', [
            'name' => 'product_status',
            'label' => __('validation.attributes.status'),
            'type' => 'select',
            'value' => isset($product) ? $product->product_status->value : '',
            'selectOptions' => App\Enums\Product\ProductStatus::toArray(),
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'value' => isset($product) ? __('Edit') : __('Create'),
            'size' => 2,
            'label' => '',
            'class' => 'btn w-100 ' . (isset($product) ? 'btn-primary' : 'btn-success'),
        ])
    </form>
@endsection
