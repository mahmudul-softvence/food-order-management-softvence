@extends('backend.master')



@section('style')
    <style>
        #quantity {
            min-width: 44px;
            font-size: 16px;
        }
    </style>
@endsection

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
                    <li class="breadcrumb-item"><a href="{{ route('order.make_order') }}">Make a Order</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor List</li>
                </ol>
            </nav>

            <h2 class="h4">Select Vendor</h2>
            <small class="mb-0">Choose a vendor from the list below.</small>

        </div>
    </div>

    <div class="table-settings mb-4">
        <div class="row align-items-center">
            <div class="col-md-4 col-xxl-2">
                <div class="input-group me-2 me-lg-3 fmxw-400">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search by Vendor Name">
                </div>
            </div>

            <div class="col-md-4 col-xxl-2">
                <div class="input-group me-2 me-lg-3 fmxw-400">
                    <span class="input-group-text">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Max Price">
                </div>
            </div>


            <div class="col-md-4 col-xxl-2">
                <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>
                <button class="btn btn-secondary ms-2">Clear</button>
            </div>

        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="row">

                @foreach ($foods as $meal)
                    @php
                        $available = $meal->stock - $meal->order_count;

                        $isOrdered = $orders
                            ->where('user_id', auth()->id())
                            ->where('food_id', $meal->food_id)
                            ->where('created_at', '>=', \Carbon\Carbon::today())
                            ->where('food_category_id', $food_category_id)
                            ->isNotEmpty();
                    @endphp

                    <div class="col-md-5">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row align-items-center g-4">

                                    <div class="col-md-5">
                                        <div class="order-image">
                                            <img src="{{ asset($meal->food->image) }}" alt="Image">
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2 gap-3">
                                                <h5 class="m-0">{{ $meal->food->name }}</h5>

                                                <small class="badge bg-info">
                                                    {{ $available }} available
                                                </small>
                                            </div>

                                            <h6 class="text-info mb-1">
                                                <i class="bi bi-currency-exchange me-2"></i>
                                                {{ $meal->price }}
                                            </h6>

                                            <small class="text-success">
                                                <i class="bi bi-shop"></i>
                                                {{ $meal->food->user->name }}
                                            </small>
                                        </div>

                                        <p>
                                            {{ Str::limit($meal->food->description, 80, '...') }}
                                        </p>

                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-gray-800 w-100 orderBtn"
                                                    data-meal_id="{{ $meal->id }}" data-id="{{ $meal->food_id }}"
                                                    data-category="{{ $meal->food_category_id }}"
                                                    data-vendor="{{ $meal->food->user_id }}"
                                                    data-price="{{ $meal->price }}" data-name="{{ $meal->food->name }}"
                                                    {{ $available <= 0 ? 'disabled' : '' }} data-bs-toggle="modal"
                                                    data-bs-target="#orderModal">

                                                    <i class="bi bi-shop me-2"></i>
                                                    {{ $isOrdered ? 'Order Again' : 'Get Now' }}
                                                </button>
                                            </div>

                                            <div class="col-md-6">
                                                @if ($isOrdered)
                                                    <small class="text-success m-0 d-block">
                                                        <i class="bi bi-check-circle"></i> Ordered
                                                    </small>
                                                @endif

                                                @if ($available <= 0)
                                                    <small class="text-danger m-0">
                                                        <i class="bi bi-x-circle"></i> Out of stock
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>


    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('employee.order') }}" method="POST">
                    @csrf

                    <input type="hidden" name="food_id" id="food_id">
                    <input type="hidden" name="vendor_id" id="vendor_id">
                    <input type="hidden" name="food_category_id" id="food_category_id">
                    <input type="hidden" name="price" id="price">
                    <input type="hidden" name="total" id="total_input">
                    <input type="hidden" name="quantity" id="quantity_input">
                    <input type="hidden" name="meal_id" id="meal_id">

                    <div class="modal-header">
                        <h2 class="h6 modal-title"><i class="bi bi-bag me-2"></i> Confirm Order — <span
                                id="foodName"></span></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong>Price: $<span id="unitPrice"></span></strong>

                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-gray-700 qtyMinus"><i
                                        class="bi bi-dash"></i></button>
                                <button type="button" class="btn btn-outline-gray-700" id="quantity">1</button>
                                <button type="button" class="btn btn-outline-gray-700 qtyPlus"><i
                                        class="bi bi-plus"></i></button>
                            </div>
                        </div>

                        <h5>Total: $ <span id="total">0</span></h5>

                        <hr>

                        <p class="text-muted">Order will be placed under your profile.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-clipboard-check me-2"></i> Confirm Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            let qty = 1,
                price = 0;

            $('.orderBtn').on('click', function() {

                qty = 1;
                price = $(this).data('price');

                $('#food_id').val($(this).data('id'));
                $('#food_category_id').val($(this).data('category'));
                $('#meal_id').val($(this).data('meal_id'));
                $('#vendor_id').val($(this).data('vendor'));
                $('#price').val(price);
                $('#foodName').text($(this).data('name'));
                $('#unitPrice').text(price);

                updateTotal();
            });

            $('.qtyPlus').click(function() {
                qty++;
                updateTotal();
            });

            $('.qtyMinus').click(function() {
                if (qty > 1) qty--;
                updateTotal();
            });

            function updateTotal() {
                let total = qty * price;

                $('#quantity').text(qty);
                $('#quantity_input').val(qty);
                $('#total').text(total);
                $('#total_input').val(total);
            }
        });
    </script>
@endsection
