@extends('layouts.admin')

@section('header', 'Facebook Settings')

@section('content')
<div class="max-w-3xl">
    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.facebook.update') }}">
        @csrf
        <div class="grid gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Page Access Token</label>
                <textarea name="page_access_token" rows="2" class="w-full rounded-lg border border-slate-200 p-2">{{ old('page_access_token', $settings->page_access_token ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Page ID</label>
                    <input name="page_id" value="{{ old('page_id', $settings->page_id ?? '') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Page Username</label>
                    <input name="page_username" value="{{ old('page_username', $settings->page_username ?? '') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Admin PSID</label>
                    <input name="admin_psid" value="{{ old('admin_psid', $settings->admin_psid ?? '') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Verify Token</label>
                    <input name="verify_token" value="{{ old('verify_token', $settings->verify_token ?? '') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2" />
                </div>
            </div>

            <div>
                <button class="admin-btn admin-btn-primary">Save Settings</button>
            </div>
        </div>
    </form>
</div>
@endsection
