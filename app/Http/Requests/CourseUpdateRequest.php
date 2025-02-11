<?php

namespace App\Http\Requests;

use App\Models\Course;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CourseUpdateRequest
 *
 * This class represents the form request used for updating course-related data.
 * It extends Laravel's FormRequest class, providing validation rules and authorization logic.
 * The class defines the rules for validating requests when updating course data, ensuring data integrity.
 *
 * This PHP code was authored by Alessandro Tieri.
 * For inquiries related to this code, please contact Alessandro Tieri.
 *
 * @author Alessandro Tieri
 */
class CourseUpdateRequest extends FormRequest
{
    /**
     * Determine if the course is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric|exists:courses,id',
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Get the custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'Il campo "Nome" è obbligatorio.',
            'name.string' => 'Il campo "Nome" deve essere una stringa.',
            'name.max' => 'Il campo "Nome" non può superare :max caratteri.',
        ];
    }
}
