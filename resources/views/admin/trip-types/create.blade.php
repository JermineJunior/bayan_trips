@extends('layouts.app')

@section('title', 'إضافة نوع رحلة')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إضافة نوع رحلة"
            description="إنشاء نوع رحلة جديد لتصنيف الرحلات."
            back="{{ route('admin.trip-types.index') }}"
            back-label="أنواع الرحلات"
        />

        <div class="mt-8">
            @include('admin.trip-types._form', [
                'tripType' => null,
            ])
        </div>
    </div>
@endsection