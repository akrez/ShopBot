<form enctype="multipart/form-data"
    action="{{ isset($color) ? route('colors.update', ['id' => $color->id]) : route('colors.store') }}" method="POST">
    @csrf
    @if (isset($color))
        @method('PUT')
    @endif

    @php
        $colorCode = old('code') ?? (isset($color) ? $color->code : '');
    @endphp
    <div class="row">
        <div class="col-md-4 mt-2">
            <div class="form-group">
                <label class="form-label" for="2055690881">
                    @lang('validation.attributes.code')
                </label>
                <input name="code" type="text" id="2055690881"
                    class="form-control {{ $errors->get('code') ? ' is-invalid ' : '' }}" value="{{ $colorCode }}"
                    data-jscolor='@json(['value' => $colorCode, 'paletteCols' => 10, 'palette' => collect(__('colors'))->keys()->implode(' ')])'>
                @foreach ($errors->get('code') as $error)
                    <div class="invalid-feedback">{{ $error }}</div>
                @endforeach
            </div>
        </div>
    </div>

    @include('components/formBuilder', [
        'name' => 'name',
        'value' => isset($color) ? $color->name : '',
    ])
    @include('components/formBuilder', [
        'type' => 'submit',
        'name' => 'submit',
        'value' => isset($color) ? __('Edit') : __('Create'),
        'size' => 2,
        'label' => '',
        'class' => 'btn w-100 ' . (isset($color) ? 'btn-primary' : 'btn-success'),
    ])
</form>
