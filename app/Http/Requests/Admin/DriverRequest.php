<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:255'],
            'default_percentage' => [
                'required',
                'numeric',
                'between:0,100',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
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
            'name.required' => 'اسم السائق مطلوب.',
            'name.max' => 'يجب ألا يتجاوز اسم السائق :max حرفًا.',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف :max حرفًا.',
            'address.max' => 'يجب ألا يتجاوز العنوان :max حرفًا.',
            'national_id.max' => 'يجب ألا يتجاوز الرقم الوطني :max حرفًا.',
            'default_percentage.required' => 'النسبة الافتراضية مطلوبة.',
            'default_percentage.numeric' => 'يجب أن تكون النسبة الافتراضية رقمًا.',
            'default_percentage.between' => 'يجب أن تكون النسبة الافتراضية بين 0 و 100.',
            'default_percentage.regex' => 'يجب ألا تزيد النسبة الافتراضية على منزلتين عشريتين.',
        ];
    }
}