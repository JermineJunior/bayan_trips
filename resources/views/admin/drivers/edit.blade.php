@extends('layouts.app')

@section('title', 'تعديل سائق')

@section('content')
    <div class="form-page">
        <x-page-header
            :title="'تعديل السائق: ' . $driver->name"
            :description="'الرقم الهاتف: ' . ($driver->phone ?: '—')"
            back="{{ route('admin.drivers.index') }}"
            back-label="السائقون"
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @include('admin.drivers._form', [
                'driver' => $driver,
            ])
        </div>
    </div>
@endsection