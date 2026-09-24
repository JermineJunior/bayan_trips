@extends('layouts.app')

@section('title', 'تعديل دور')

@section('content')
    <div class="form-page">
        <x-page-header
            :title="'تعديل الدور: ' . $role->name"
            :description="'تحديث اسم الدور أو صلاحياته.'"
            back="{{ route('admin.roles.index') }}"
            back-label="الأدوار"
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @include('admin.roles._form', [
                'role' => $role,
                'rolePermissions' => $rolePermissions,
            ])
        </div>
    </div>
@endsection