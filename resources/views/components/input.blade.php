@props(['name', 'placeholder', 'id', 'description', 'value', 'maxlength', 'required'])

<div>
    <div>
        <input  type="{{ $name === 'email' ? 'email' : 'text' }}"
                label="{{ $name }}"
                id="{{ $name }}"
                class="form-control @error($name) is-invalid @enderror"
                placeholder="{{ $placeholder }}{{ ($required ?? false) ? ' *' : '' }}"
                name="{{ $name }}"
                value="{{ $value ?? '' }}"
                maxlength="{{ $maxlength ?? 255  }}"
                {{ ($required ?? false) ? 'required' : '' }} />
    </div>
</div>

    @error($name)
        <span class="text-danger">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
