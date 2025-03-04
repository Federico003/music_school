@props(['name', 'label' => null, 'value', 'description' => null, 'required', 'class', 'errors' => null])

<label for="{{ $name }}">{{ $label }} 
    {!! ($required ?? false) ? '' : '' !!} {{-- Rimuovi l'asterisco --}}
</label>
<div class="form-group">
    <div class="form-line {{ $errors ? 'error' : '' }}">
        <input 
            type="password" 
            class="{{ $class }}" 
            name="{{ $name }}"
            value="{{ $value }}"
            maxlength="255"
            {{ $required ? 'required' : '' }}
        />
    </div>

    {{--@if ($errors)
        <label class="error">{{ $errors }}</label>
    @endif--}}

    <div class="help-info">{{ $description }}</div>
</div>
