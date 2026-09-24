@extends('layouts.app')

@section('title', 'تعديل الرحلة')

@section('content')
    <div class="form-page">
        <x-page-header
            title="تعديل الرحلة"
            :description="'تحديث بيانات رحلة بتاريخ ' . $trip->trip_date->format('Y-m-d') . '.'"
            back="{{ route('admin.trips.show', $trip) }}"
            back-label="الرحلة"
        />

        <div class="mt-8">
            @include('admin.trips._form', [
                'trip' => $trip,
            ])
        </div>
    </div>
@endsection