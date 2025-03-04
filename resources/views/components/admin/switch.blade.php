@props(['name', 'label' => null, 'check', 'description', 'required'])

<head>
    <style>
        /* CSS per il design dello switch */
        .toggle-checkbox:checked + .toggle-label .switch {
            background-color: #4CAF50;
        }

        .toggle-checkbox + .toggle-label .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
            background-color: #ccc;
            border-radius: 50px;
        }

        .toggle-checkbox + .toggle-label .switch:before {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: white;
            transition: 0.3s;
        }

        .toggle-checkbox:checked + .toggle-label .switch:before {
            transform: translateX(14px);
        }
    </style>
</head>

<div class="form-group">
    <label for="{{ $name }}">{{ $label }} 
        {!! ($required ?? false) ? '' : '' !!} {{-- Rimuovi l'asterisco --}}
    </label>
    <div class="flex items-center">
        <!-- Checkbox visibile per il toggle -->
        <input type="checkbox" 
               id="{{ $name }}" 
               name="{{ $name }}" 
               class="toggle-checkbox" 
               value="1" 
               {{ ($check ?? false) ? 'checked' : '' }} 
               {{ $required ? 'required' : '' }} />

        <!-- Label per il toggle -->
        <label for="{{ $name }}" class="toggle-label cursor-pointer flex items-center">
            <span class="switch"></span>
        </label>
    </div>

    @if ($errors->has($name))
        <label class="error">{{ $errors->first($name) }}</label>
    @endif
    @if($description)
        <div class="help-info">{{ $description }}</div>
    @endif
</div>