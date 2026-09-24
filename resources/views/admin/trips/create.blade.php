@extends('layouts.app')

@section('title', 'إضافة رحلة')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إضافة رحلة"
            description="تسجيل رحلة جديدة مع مصروفاتها."
            back="{{ route('admin.trips.index') }}"
            back-label="الرحلات"
        />

        <div class="mt-8">
            @include('admin.trips._form', [
                'trip' => null,
            ])
        </div>
    </div>
@endsection