@extends('layouts.app')

@section('title', 'إضافة سائق')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إضافة سائق"
            description="تسجيل سائق جديد في النظام."
            back="{{ route('admin.drivers.index') }}"
            back-label="السائقون"
        />

        <div class="mt-8">
            @include('admin.drivers._form', [
                'driver' => null,
            ])
        </div>
    </div>
@endsection