<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $vehicle = $this->route('vehicle');

        return [
            'plate_number' => [
                'required',
                'string',
                'max:255',
                // Uniqueness only across active (non-deleted) vehicles.
                Rule::unique('vehicles', 'plate_number')
                    ->whereNull('deleted_at')
                    ->ignore($vehicle?->id),
            ],
            'type' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'plate_number.required' => 'رقم اللوحة مطلوب.',
            'plate_number.max' => 'يجب ألا يتجاوز رقم اللوحة :max حرفًا.',
            'plate_number.unique' => 'يوجد مركبة بهذا رقم اللوحة بالفعل.',
            'type.max' => 'يجب ألا يتجاوز نوع المركبة :max حرفًا.',
            'name.max' => 'يجب ألا يتجاوز اسم المركبة :max حرفًا.',
        ];
    }
}