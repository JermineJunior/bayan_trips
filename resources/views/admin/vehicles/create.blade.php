@extends('layouts.app')

@section('title', 'إضافة مركبة')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إضافة مركبة"
            description="تسجيل مركبة جديدة في أسطول النقل."
            back="{{ route('admin.vehicles.index') }}"
            back-label="المركبات"
        />

        <div class="mt-8">
            @include('admin.vehicles._form', [
                'vehicle' => null,
            ])
        </div>
    </div>
@endsection