@props(['name', 'label' => null, 'description', 'value', 'required'])

<label for="{{ $name }}">{{ $label }} 
    {!! ($required ?? false) ? '' : '' !!} {{-- Rimuovi l'asterisco --}}
</label>
<div class="form-group">
    <div class="form-line {{ $errors->has($name) ? 'error' : '' }}">
        <input  type="email" 
                class="form-control" 
                name="{{ $name }}" 
                value="{{ old($name) ?? $value ?? '' }}" 
                {{ ($required ?? false) ? 'required' : '' }} />
    </div>

    @if ($errors->has($name))
        <label class="error">{{ $errors->first($name) }}</label>
    @endif                                                    
    <div class="help-info">{{ $description }}</div>
</div>
