@props(['name', 'options', 'check'])

<select class="form-control" name="{{ $name }}">
    @foreach ($options as $key => $option)
        <option value="{{ $key }}" {{ is_selected_option($key, $check ?? '') }}>{{ $option }}</option>
    @endforeach
</select>
