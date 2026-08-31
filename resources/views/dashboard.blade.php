@extends('layouts.shop')

@section('content')
    <div class="card-lg panel-narrow">
        <h1 class="text-xl font-semibold tracking-tight text-slate-900">
            Welcome back, {{ auth()->user()->name }}
        </h1>

        @if (auth()->user()->is_admin)
            <p class="mt-2 text-sm text-slate-500">You're signed in as an admin.</p>

            <div class="form-actions mt-6">
                <a href="{{ route('home') }}" class="btn-primary">Manage products</a>
                <a href="{{ route('products.create') }}" class="btn-outline">Add a product</a>
            </div>
        @else
            <p class="mt-2 text-sm text-slate-500">
                You don't have admin access, so there's nothing to manage here yet.
            </p>

            <a href="{{ route('home') }}" class="btn-muted block-link mt-6">
                Browse products &rarr;
            </a>
        @endif

        <div class="mt-6 border-t border-slate-100 pt-6">
            <a href="{{ route('profile.edit') }}" class="btn-muted">Edit profile settings</a>
        </div>
    </div>
@endsection