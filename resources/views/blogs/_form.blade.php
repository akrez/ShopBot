@section('content')
<form enctype="multipart/form-data" action="{{ isset($blog) ? route('blogs.update', ['id' => $blog->id]) : route('blogs.store') }}" method="POST">
    @csrf
    @if (isset($blog))
    @method('PUT')
    @endif
    @include('components/formBuilder', [
    'name' => 'name',
    'value' => isset($blog) ? $blog->name : '',
    ])
    @include('components/formBuilder', [
    'name' => 'short_description',
    'value' => isset($blog) ? $blog->short_description : '',
    ])
    @include('components/formBuilder', [
    'type' => 'textarea',
    'name' => 'description',
    'value' => isset($blog) ? $blog->description : '',
    ])
    @include('components/formBuilder', [
    'name' => 'blog_status',
    'label' => __('validation.attributes.status'),
    'type' => 'select',
    'value' => isset($blog) ? $blog->blog_status->value : '',
    'selectOptions' => App\Enums\Blog\BlogStatus::toArray(),
    ])
    @include('components/formBuilder', [
    'type' => 'submit',
    'name' => 'submit',
    'value' => isset($blog) ? __('Edit') : __('Create'),
    'size' => 2,
    'label' => '',
    'class' => 'btn btn-primary w-100',
    ])
</form>
@endsection