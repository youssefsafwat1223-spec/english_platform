@extends('layouts.admin')
@section('title', __('live_sessions.edit'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('live_sessions.edit') }}</span></h1>
        </div>
        <form method="POST" action="{{ route('admin.live-sessions.update', $liveSession) }}">
            @csrf
            @method('PUT')
            @include('admin.live-sessions._form')
        </form>
    </div>
</div>
@endsection
