<?php

    use Carbon\Carbon;

    /**
     * Ritorna se la sezione attiva è fra quelle passate
     *
     * @param Array
     * @return Bool
     */
    function is_section_active($list)
    {
        foreach ($list as $l) {
            if (str_contains(app_section_name(), $l))
                return true;
        }

        return false;
    }

    /**
     * Ritorna il nome della sezione attiva.
     *
     * @return String
     */
    function app_section_name()
    {        
        $request_segments = request()->segments();

        return implode('.', $request_segments);
    }

    /**
     * Ritorna la data formattata.
     *
     * @param String
     * @return String
     */
    function format_date($date)
    {
        if (is_object($date) && get_class($date) == 'Illuminate\Support\Carbon') {
            return $date->format('d/m/Y');
        }

        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }

    /**
     * Ritorna nome e cognome formattati.
     *
     * @param String
     * @param String
     * @return String
     */
    function format_name($fname, $lname)
    {
        return ucfirst(strtolower($fname)) ." ". ucfirst(strtolower($lname));
    }

    /**
     * Ritorna se l'opzione è selezionata.
     *
     * @param String
     * @param String
     * @return Bool
     */
    function is_selected_option($val, $check)
    {
        return strtolower($val) === strtolower($check) ? 'selected' : '';
    }

    /**
     * Ritorna se il checkbox è selezionata.
     *
     * @param String
     * @param String
     * @return Bool
     */
    function is_checked($check, $val = null)
    {
        $val = $val != null ? $val : 1;

        return ($check === 'on' || $check === 'si' || $check === 1 || $check === true) ? 'checked' : '';
    }

    /**
     * Ritorna se il checkbox è disabilitato.
     *
     * @param String
     * @param String
     * @return Bool
     */
    function is_disabled($check, $val = null)
    {
        $val = $val != null ? $val : 1;

        return ($check === 'on' || $check === 'si' || $check === 1 || $check === true) ? 'disabled' : '';
    }

    /**
     * Disabilita l'elemento se $val è settato.
     *
     * @param String
     * @return String
     */
    function disable_if_set($val)
    {
        return isset($val) ? 'disabled' : '';
    }

    /**
     * Disabilita l'elemento se $val NON è settato.
     *
     * @param String
     * @return String
     */
    function disable_if_empty_or_unset($val)
    {
        return isset($val) && strlen($val) > 0 ? '' : 'disabled';
    }

    /**
     * Ritorna l'indice dell'opzione nell'array.
     *
     * @param String
     * @param array
     * @return Int
     */
    function selected_option_index($option, $array)
    {
        return array_search($option, $array);
    }

    /**
     * Ritorna la classe 'errored' se sono presenti errori per il campo
     *
     * @param errors Array
     * @param label String
     * @return String
     */
    function add_error_class($errors, $label): String
    {
        return $errors->has($label) ? 'errored' : '';
    }
    
    /**
     * Ritorna un valore valido fra v1, v2 o v3 che è il valore di default
     *
     * @param Any $value
     * @param Any $fallback
     * @param Any $default
     * @return Any | null
     */
    function get_value_or_default($value, $fallback, $default = null)
    {
        try {
            if (isset($value) && $value != null) {
                return $value;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        try {
            if (isset($fallback) && $fallback != null) {
                return $fallback;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        try {
            if (isset($default) && $default != null) {
                return $default;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return null;
    }

    /**
     * Data una string in snake_case
     * torna la stringa con gli spazi
     *
     * @param stringa String
     * @return String
     */
    function snake_to_space($stringa): String
    {
        return str_replace('_', ' ', $stringa);
    }

    /**
     * Data una string in snake_case
     * torna la stringa con gli spazi e l'iniziale maiuscola
     *
     * @param stringa String
     * @return String
     */
    function upper_first_snake_to_space($stringa): String
    {
        return str_replace('_', ' ', ucfirst($stringa));
    }

     /**
     * Data una string in snake_case
     * torna la stringa con gli spazi e l'iniziale minuscola
     *
     * @param stringa String
     * @return String
     */
    function down_first_snake_to_space($stringa): String
    {
        return str_replace('_', ' ', lcfirst($stringa));
    }

    /**
     * Data una string con spazi
     * sostituisce tutti gli spazi con _
     *
     * @param stringa String
     * @return String
     */
    function string_to_snake_case($stringa): String
    {
        return strtolower(str_replace(' ', '_', $stringa));
    }
