@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">Make a Order</li>
                </ol>
            </nav>

        </div>

    </div>



    <div class="row">
        @foreach ($categories as $category)
            @php
                $colors = [
                    '#F8BBD0',
                    '#E1BEE7',
                    '#D1C4E9',
                    '#C5CAE9',
                    '#BBDEFB',
                    '#B3E5FC',
                    '#B2EBF2',
                    '#B2DFDB',
                    '#C8E6C9',
                    '#DCEDC8',
                    '#F0F4C3',
                    '#FFF9C4',
                    '#FFECB3',
                    '#FFE0B2',
                    '#FFCCBC',
                ];

                $color = $colors[$category->id % count($colors)];

                $hex = str_replace('#', '', $color);
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
                $textColor = ($r * 299 + $g * 587 + $b * 114) / 1000 > 155 ? '#000' : '#fff';

                $now = now()->format('H:i');
                $start = \Carbon\Carbon::parse($category->start_time)->format('H:i');
                $end = \Carbon\Carbon::parse($category->end_time)->format('H:i');
                $active = $now >= $start && $now <= $end;
            @endphp

            <div class="col-md-3 mb-4">
                <div class="card bg-light {{ !$active ? 'disabled-card' : '' }}">
                    <div class="card-body make_order_card">

                        <div class="make_order_card_image mb-3">
                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="img-fluid">
                        </div>

                        <h4 class="mb-3">{{ $category->name }}</h4>

                        <div class="clock mb-3" style="background: {{ $color }}; color: {{ $textColor }};">
                            {{ $start }} - {{ $end }}
                        </div>

                        <p class="text-muted">
                            {{ Str::limit($category->description, 70, '...') }}
                        </p>

                        <a href="{{ route('order.make_order.vendor_list', $category->id) }}"
                            class="btn btn-primary w-100 {{ !$active ? 'disabled' : '' }}">
                            <i class="bi bi-basket me-2"></i>
                            Select {{ $category->name }}
                        </a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
