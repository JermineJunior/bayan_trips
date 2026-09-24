@extends('layouts.app')

@section('title', 'إضافة عميل')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إضافة عميل"
            description="تسجيل عميل جديد في النظام."
            back="{{ route('admin.customers.index') }}"
            back-label="العملاء"
        />

        <div class="mt-8">
            @include('admin.customers._form', [
                'customer' => null,
            ])
        </div>
    </div>
@endsection