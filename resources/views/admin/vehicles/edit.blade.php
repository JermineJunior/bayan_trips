@extends('layouts.app')

@section('title', 'تعديل مركبة')

@section('content')
    <div class="form-page">
        <x-page-header
            :title="'تعديل المركبة: ' . $vehicle->plate_number"
            :description="$vehicle->name ?: 'تحديث بيانات المركبة'"
            back="{{ route('admin.vehicles.index') }}"
            back-label="المركبات"
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @include('admin.vehicles._form', [
                'vehicle' => $vehicle,
            ])
        </div>
    </div>
@endsection