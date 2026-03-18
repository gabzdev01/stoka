@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">{{ tenant('name') }}</p>
@endsection

@section('content')
    <div class="card" style="max-width: 480px;">
        <p style="font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 600; color: #1C1814; margin-bottom: 8px;">
            Good to see you, {{ session('auth_name') }}.
        </p>
        <p style="font-size: 14px; color: #8C7B6E; line-height: 1.6;">
            {{ tenant('name') }} is open and running. Head to <strong style="color: #1C1814;">Products</strong> to manage your stock,
            or <strong style="color: #1C1814;">Suppliers</strong> to track who you buy from.
            When your staff are ready to sell, they log in and open a shift.
        </p>
    </div>
@endsection
