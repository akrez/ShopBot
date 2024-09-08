<form enctype="multipart/form-data"
    action="{{ isset($package)
        ? route('products.packages.update', ['product_id' => $product->id, 'id' => $package->id])
        : route('products.packages.store', ['product_id' => $product->id]) }}"
    method="POST">
    @csrf
    @if (isset($package))
        @method('PUT')
    @endif


    @include('components/formBuilder', [
        'name' => 'price',
        'value' => isset($package) ? $package->price : '',
    ])
    @include('components/formBuilder', [
        'name' => 'color_id',
        'type' => 'select',
        'value' => isset($package) ? $package->color_id : '',
        'selectOptions' => ['' => ''] + $colors,
    ])
    @include('components/formBuilder', [
        'name' => 'guaranty',
        'value' => isset($package) ? $package->guaranty : '',
    ])
    @include('components/formBuilder', [
        'name' => 'description',
        'value' => isset($package) ? $package->description : '',
    ])
    @include('components/formBuilder', [
        'name' => 'package_status',
        'label' => __('validation.attributes.status'),
        'type' => 'select',
        'value' => isset($package) ? $package->package_status->value : '',
        'selectOptions' => App\Enums\Package\PackageStatus::toArray(),
    ])
    @include('components/formBuilder', [
        'type' => 'submit',
        'name' => 'submit',
        'value' => isset($package) ? __('Edit') : __('Create'),
        'size' => 2,
        'label' => '',
        'class' => 'btn w-100 ' . (isset($package) ? 'btn-primary' : 'btn-success'),
    ])
</form>
