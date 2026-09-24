@extends('layouts.app')

@section('title', 'تعديل عميل')

@section('content')
    <div class="form-page">
        <x-page-header
            :title="'تعديل العميل: ' . $customer->name"
            :description="$customer->phone ?: 'تحديث بيانات العميل'"
            back="{{ route('admin.customers.index') }}"
            back-label="العملاء"
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @include('admin.customers._form', [
                'customer' => $customer,
            ])
        </div>
    </div>
@endsection