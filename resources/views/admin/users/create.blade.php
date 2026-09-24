@extends('layouts.app')

@section('title', 'إضافة مستخدم')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إضافة مستخدم"
            description="إنشاء حساب جديد وتعيين دوره في النظام."
            back="{{ route('admin.users.index') }}"
            back-label="المستخدمون"
        />

        <div class="mt-8">
            @include('admin.users._form', [
                'user' => null,
            ])
        </div>
    </div>
@endsection