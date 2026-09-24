@extends('layouts.app')

@section('title', 'تعديل نوع رحلة')

@section('content')
    <div class="form-page">
        <x-page-header
            :title="'تعديل نوع الرحلة: ' . $tripType->name"
            :description="$tripType->is_default ? 'النوع الافتراضي — يمكن تعديل اسمه لكن لا يمكن حذفه.' : 'تحديث بيانات نوع الرحلة'"
            back="{{ route('admin.trip-types.index') }}"
            back-label="أنواع الرحلات"
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @include('admin.trip-types._form', [
                'tripType' => $tripType,
            ])
        </div>
    </div>
@endsection