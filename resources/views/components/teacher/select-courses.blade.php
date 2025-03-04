@props(['name', 'label' => null, 'description' => null, 'check' => null, 'required' => false, 'disabled' => false, 'readonly' => false, 'courses'])

<label for="{{ $name }}">{{ $label ?? ucfirst($name) }}</label>

<div class="form-group">
    <div class="form-line {{ $errors->has($name) ? 'error' : '' }}">
        <select id="{{ $name }}" 
                class="form-control" 
                name="{{ $name }}" 
                {{ $required ? 'required' : '' }} 
                @disabled($disabled) 
                @readonly($readonly) >

            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old($name, $check) == $course->id)>{{ $course->name }}</option>
            @endforeach
        </select>        
    </div>

    @if ($errors->has($name))
        <label class="error">{{ $errors->first($name) }}</label>
    @endif                                                    
    <div class="help-info">{{ $description }}</div>
</div>
