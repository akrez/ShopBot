@php
    $inputValue = old($name, isset($value) ? $value : '');

    if (!isset($type)) {
        $type = 'text';
    }

    if (!isset($label)) {
        $label = __('validation.attributes.' . $name);
    }

    if (!isset($id)) {
        $id = crc32($label . $name);
    }

    if (!isset($row)) {
        $row = true;
    }

    if (!isset($mt)) {
        $mt = 2;
    }

    if (!isset($selectOptions)) {
        $selectOptions = [];
    }

    if (!isset($size)) {
        $size = 4;
    }

    if (!isset($textareaRows)) {
        $textareaRows = 4;
    }

    if (!isset($visible)) {
        $visible = true;
    }

    if (!isset($errorsArray)) {
        $errorsArray = $errors->get($name);
    }

    if (!isset($class)) {
        $class = 'form-control';
    }

    if (!isset($hint)) {
        $hint = '';
    }

    $inputClass = [$class];
    if ($errorsArray) {
        $inputClass[] = 'is-invalid';
    }
@endphp

@if ($visible)
    @if ($row)
        <div class="row">
    @endif
    <div class="col-md-{{ $size }} mt-{{ $mt }}">
        <div class="form-group">
            @if ('submit' == $type)
                <button name="{{ $name }}" type="{{ $type }}" id="{{ $id }}"
                    class="{{ implode(' ', $inputClass) }}">{{ $label ? $label : $value }}</button>
            @else
                @if ($label)
                    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
                @endif
                @if ('textarea' == $type)
                    <textarea name="{{ $name }}" id="{{ $id }}" class="{{ implode(' ', $inputClass) }}"
                        rows="{{ $textareaRows }}">{{ $inputValue }}</textarea>
                @elseif ('file' == $type)
                    <input name="{{ $name }}" type="{{ $type }}" id="{{ $id }}"
                        class="{{ implode(' ', $inputClass) }}" value="{{ $inputValue }}" />
                @elseif ('select' == $type)
                    <select name="{{ $name }}" id="{{ $id }}"
                        class="{{ implode(' ', $inputClass) }}">
                        @foreach ($selectOptions as $selectOptionValue => $selectOption)
                            <option value="{{ $selectOptionValue }}"
                                {{ $inputValue == $selectOptionValue ? ' selected ' : '' }}>{{ $selectOption }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input name="{{ $name }}" type="{{ $type }}" id="{{ $id }}"
                        class="{{ implode(' ', $inputClass) }}" value="{{ $inputValue }}" />
                @endif
            @endif
            @if ($hint)
                <small class="form-text">{{ $hint }}</small>
            @endif
            @foreach ($errorsArray as $error)
                <div class="invalid-feedback">{{ $error }}</div>
            @endforeach
        </div>
    </div>
    @if ($row)
        </div>
    @endif
@endif
