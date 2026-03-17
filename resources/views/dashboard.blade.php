@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">{{ tenant('name') }}</p>
@endsection

@section('content')
    <div class="card" style="max-width: 480px;">
        <p style="font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: #1C1814; margin-bottom: 8px;">
            Welcome to Stoka
        </p>
        <p style="font-size: 14px; color: #8C7B6E; line-height: 1.6;">
            You're logged in as <strong style="color: #1C1814;">{{ session('auth_name') }}</strong>
            at <strong style="color: #C17F4A;">{{ tenant('name') }}</strong>.
            Use the sidebar to navigate.
        </p>
    </div>
@endsection
