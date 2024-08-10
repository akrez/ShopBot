@section('content')
    <form enctype="multipart/form-data"
        action="{{ isset($bot) ? route('bots.update', ['id' => $bot->id]) : route('bots.store') }}" method="POST">
        @csrf
        @if (isset($bot))
            @method('PUT')
        @endif
        @include('components/formBuilder', [
            'name' => 'token',
            'value' => isset($bot) ? $bot->token : '',
        ])
        @include('components/formBuilder', [
            'type' => 'submit',
            'name' => 'submit',
            'value' => isset($bot) ? __('Edit') : __('Create'),
            'size' => 2,
            'label' => '',
            'class' => 'btn w-100 ' . (isset($bot) ? 'btn-primary' : 'btn-success'),
        ])
    </form>
@endsection
