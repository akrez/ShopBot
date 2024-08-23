<form enctype="multipart/form-data"
    action="{{ isset($contact) ? route('contacts.update', ['id' => $contact->id]) : route('contacts.store') }}"
    method="POST">
    @csrf
    @if (isset($contact))
        @method('PUT')
    @endif
    @include('components/formBuilder', [
        'name' => 'contact_type',
        'type' => 'select',
        'value' => isset($contact) ? $contact->contact_type->value : '',
        'selectOptions' => ['' => ''] + \App\Enums\Contact\ContactType::toArray(),
    ])
    @include('components/formBuilder', [
        'name' => 'contact_key',
        'value' => isset($contact) ? $contact->contact_key : '',
    ])
    @include('components/formBuilder', [
        'name' => 'contact_value',
        'value' => isset($contact) ? $contact->contact_value : '',
    ])
    @include('components/formBuilder', [
        'name' => 'contact_link',
        'value' => isset($contact) ? $contact->contact_link : '',
    ])
    @include('components/formBuilder', [
        'name' => 'contact_order',
        'value' => isset($contact) ? $contact->contact_order : '',
    ])
    @include('components/formBuilder', [
        'type' => 'submit',
        'name' => 'submit',
        'value' => isset($contact) ? __('Edit') : __('Create'),
        'size' => 2,
        'label' => '',
        'class' => 'btn w-100 ' . (isset($contact) ? 'btn-primary' : 'btn-success'),
    ])
</form>
