@extends('layouts.app')

@section('content')
    <div data-motion="fade-left">
        AOWKOWAK
    </div>

    <a href="{{ route('main') }}" data-motion="text-split">
        Main
    </a>

    <button type="button" @click="toggle()" class="btn btn-ghost btn-circle">
        Toggle
    </button>
@endsection
