@props(['name', 'label' => null, 'id', 'description', 'value', 'maxlength', 'required'])

<label for="{{ $name }}">{{ $label }} 
    {!! ($required ?? false) ? '' : '' !!} {{-- Rimuovi l'asterisco --}}
</label>
<div class="form-group">
    <div class="form-line {{ $errors->has($name) ? 'error' : '' }}">
        <input  type="text"
                id="{{ $name }}"
                class="form-control"
                name="{{ $name }}"
                value="{{ $value ?? '' }}"
                maxlength="{{ $maxlength ?? 255  }}"
                {{ ($required ?? false) ? 'required' : '' }} />
    </div>

    @if ($errors->has($name))
        <label class="error">{{ $errors->first($name) }}</label>
    @endif
    <div class="help-info">{{ $description }}</div>
</div>
