<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TripFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize the location fields before validation so trailing/leading
     * whitespace never reaches the database.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'from_location' => trim((string) $this->input('from_location')),
            'to_location' => trim((string) $this->input('to_location')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $trip = $this->route('trip');

        return [
            'trip_type_id' => ['required', 'exists:trip_types,id'],
            // Soft-deleted neighbours can no longer be picked, except for the
            // trip's own current record on update (history must stay valid).
            'customer_id' => [
                'nullable',
                Rule::exists('customers', 'id')->where(function ($query) use ($trip) {
                    $query->whereNull('deleted_at');
                    if ($trip?->customer_id) {
                        $query->orWhere('id', $trip->customer_id);
                    }
                }),
            ],
            'vehicle_id' => [
                'required',
                Rule::exists('vehicles', 'id')->where(function ($query) use ($trip) {
                    $query->whereNull('deleted_at');
                    if ($trip?->vehicle_id) {
                        $query->orWhere('id', $trip->vehicle_id);
                    }
                }),
            ],
            'driver_id' => [
                'required',
                Rule::exists('drivers', 'id')->where(function ($query) use ($trip) {
                    $query->whereNull('deleted_at');
                    if ($trip?->driver_id) {
                        $query->orWhere('id', $trip->driver_id);
                    }
                }),
            ],
            'from_location' => ['required', 'string', 'max:255'],
            'to_location' => ['required', 'string', 'max:255'],
            'trip_date' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0'],
            'driver_percentage' => [
                'required',
                'numeric',
                'between:0,100',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'notes' => ['nullable', 'string'],
            'expenses' => ['nullable', 'array'],
            'expenses.*.amount' => ['required', 'numeric', 'gt:0'],
            'expenses.*.description' => ['required', 'string', 'max:255'],
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
            'trip_type_id.required' => 'نوع الرحلة مطلوب.',
            'trip_type_id.exists' => 'نوع الرحلة المحدد غير صالح.',
            'customer_id.exists' => 'العميل المحدد غير صالح.',
            'vehicle_id.required' => 'المركبة مطلوبة.',
            'vehicle_id.exists' => 'المركبة المحددة غير صالحة.',
            'driver_id.required' => 'السائق مطلوب.',
            'driver_id.exists' => 'السائق المحدد غير صالح.',
            'from_location.required' => 'مكان الانطلاق مطلوب.',
            'from_location.max' => 'يجب ألا يتجاوز مكان الانطلاق :max حرفًا.',
            'to_location.required' => 'الوجهة مطلوبة.',
            'to_location.max' => 'يجب ألا تتجاوز الوجهة :max حرفًا.',
            'trip_date.required' => 'تاريخ الرحلة مطلوب.',
            'trip_date.date' => 'يجب أن يكون تاريخ الرحلة تاريخًا صحيحًا.',
            'price.required' => 'سعر الرحلة مطلوب.',
            'price.numeric' => 'يجب أن يكون سعر الرحلة رقمًا.',
            'price.min' => 'يجب ألا يقل سعر الرحلة عن صفر.',
            'driver_percentage.required' => 'نسبة السائق مطلوبة.',
            'driver_percentage.numeric' => 'يجب أن تكون نسبة السائق رقمًا.',
            'driver_percentage.between' => 'يجب أن تكون نسبة السائق بين 0 و 100.',
            'driver_percentage.regex' => 'يجب ألا تزيد نسبة السائق على منزلتين عشريتين.',
            'expenses.array' => 'صيغة المصروفات غير صحيحة.',
            'expenses.*.amount.required' => 'مبلغ المصروف مطلوب.',
            'expenses.*.amount.numeric' => 'يجب أن يكون مبلغ المصروف رقمًا.',
            'expenses.*.amount.gt' => 'يجب أن يكون مبلغ المصروف أكبر من صفر.',
            'expenses.*.description.required' => 'وصف المصروف مطلوب.',
            'expenses.*.description.max' => 'يجب ألا يتجاوز وصف المصروف :max حرفًا.',
        ];
    }
}