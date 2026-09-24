@extends('layouts.app')

@section('title', 'إنشاء دور')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إنشاء دور"
            description="إنشاء دور جديد وتحديد الصلاحيات الممنوحة له."
            back="{{ route('admin.roles.index') }}"
            back-label="الأدوار"
        />

        <div class="mt-8">
            @include('admin.roles._form', [
                'role' => null,
                'rolePermissions' => [],
            ])
        </div>
    </div>
@endsection