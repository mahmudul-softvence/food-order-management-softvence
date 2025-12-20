@extends('backend.master')


@section('content')
    <div class="row py-4">
        <div class="col-md-12">
            <h4>{{ __('messages.dashboard') }}</h4>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 h-100">
                            <div class="d-flex flex-column justify-content-between">
                                <div>
                                    <h4>{{ __('messages.hi') }}, Mahmudul Elahi</h4>
                                    @php
                                        $hour = now()->format('H');
                                        if ($hour < 12) {
                                            $greet = __('messages.good_morning');
                                        } elseif ($hour < 18) {
                                            $greet = __('messages.good_afternoon');
                                        } else {
                                            $greet = __('messages.good_evening');
                                        }
                                    @endphp

                                    <h6 class="text-muted fw-500 pt-2">{{ $greet }}</h6>

                                    <p class="pb-3">{{ __('messages.today_updates') }}</p>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-primary">
                                        <i class="bi bi-gear me-1"></i> {{ __('messages.go_to_settings') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <img class="dashobard-image" src="{{ asset('backend/assets/img/dashobard.png') }}"
                                alt="Dashobard">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row gy-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow card-dashboard-right">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-icon purple">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="d-right-text">
                                    <h4>{{ __('messages.registered_users') }}</h4>
                                    <h3>1234</h3>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow card-dashboard-right">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-icon blue">
                                    <i class="bi bi-fork-knife"></i>
                                </div>
                                <div class="d-right-text">
                                    <h4>{{ __('messages.total_foods') }}</h4>
                                    <h3>1234</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow card-dashboard-right">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-icon green">
                                    <i class="bi bi-bag-check"></i>
                                </div>
                                <div class="d-right-text">
                                    <h4>{{ __('messages.success_orders') }}</h4>
                                    <h3>1234</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow card-dashboard-right">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-icon orange">
                                    <i class="bi bi-bag-x"></i>
                                </div>
                                <div class="d-right-text">
                                    <h4>{{ __('messages.cancelled_orders') }}</h4>
                                    <h3>1234</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @role(['admin', 'vendor'])
        <div class="row pt-4 g-4">
            <div class="col-md-7">
                <div class="card border-0 shadow ">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('messages.new_order_request') }}</h5>

                        <div id="bulkActions" class="d-none">
                            <button class="btn btn-danger btn-sm me-2" onclick="bulkSubmit('cancelled')">
                                <i class="bi bi-x-circle"></i> Cancel All
                            </button>

                            <button class="btn btn-success text-white btn-sm" onclick="bulkSubmit('delivered')">
                                <i class="bi bi-check-circle"></i> Deliver All
                            </button>
                        </div>
                    </div>

                    <div class="card-body table-wrapper table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>#</th>
                                    <th>{{ __('messages.customer') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>{{ __('messages.amount') }}</th>
                                    <th>{{ __('messages.payment') }}</th>
                                    <th>{{ __('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pending_orders as $index => $order)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        </td>

                                        <td>{{ $index + 1 }}</td>

                                        <td>{{ $order->user->name ?? 'NA' }}</td>

                                        <td>{{ $order->quantity }}</td>

                                        <td>${{ number_format($order->total_price, 2) }}</td>

                                        <td>
                                            <span
                                                class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-danger' }}">
                                                {{ __($order->payment_status) }}
                                            </span>
                                        </td>

                                        <td>
                                            @if ($order->status == 'pending')
                                                <button class="btn btn-danger text-white btn-sm me-2"
                                                    onclick="submitStatusForm({{ $order->id }}, 'cancelled')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            @endif

                                            <button class="btn btn-gray-700 btn-sm me-2" data-bs-toggle="tooltip"
                                                title="View Order" onclick="viewOrder({{ $order->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            @if ($order->status == 'pending')
                                                <button class="btn btn-success text-white btn-sm"
                                                    onclick="submitStatusForm({{ $order->id }}, 'delivered')">
                                                    <i class="bi bi-check-circle me-2"></i>Delivered
                                                </button>
                                            @endif

                                            <form id="statusForm" action="{{ route('vendor.order.status.update', 'ID_HERE') }}"
                                                method="POST" class="d-none">
                                                @csrf
                                                <input type="hidden" name="status" id="statusInput">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $pending_orders->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <form id="bulkForm" action="{{ route('vendor.order.bulkStatus') }}" method="POST" class="d-none">
                @csrf
                <input type="hidden" name="status" id="bulkStatus">
                <input type="hidden" name="order_ids" id="bulkOrderIds">
            </form>


            <div class="col-md-5">
                <div class="row g-4">

                    @foreach ($categories as $category)
                        @php
                            $meals = $todaysMeals->get($category->id);
                        @endphp

                        <div class="col-md-6">
                            <div class="card card-body shadow border-0">

                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <h5 class="mb-0">{{ $category->name }}</h5>
                                    <small class="text-muted">(Today)</small>
                                </div>
                                @if (!$meals)
                                    <div class="row">
                                        <div class="col-5">
                                            <small class="text-muted">Start</small>
                                            <h6 class="m-0">
                                                {{ \Carbon\Carbon::parse($category->start_time)->format('h:i A') }}
                                            </h6>
                                        </div>

                                        <div class="col-5">
                                            <small class="text-muted">End</small>
                                            <h6 class="m-0">
                                                {{ \Carbon\Carbon::parse($category->end_time)->format('h:i A') }}
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="button" class="btn btn-info w-100 add-category-btn"
                                            data-category-id="{{ $category->id }}"
                                            data-category-name="{{ $category->name }}">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Add {{ $category->name }}
                                        </button>
                                    </div>
                                @else
                                    @foreach ($meals as $meal)
                                        <div class="row mb-3">
                                            <div class="col-3">
                                                <img src="{{ asset($meal->food->image ?? 'uploads/default.png') }}"
                                                    height="50" class="rounded">
                                            </div>

                                            <div class="col-9">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="m-0">{{ $meal->food->name }}</h6>
                                                        <small class="text-muted">
                                                            Stock:
                                                            <strong
                                                                class="text-success">{{ $meal->stock - $meal->order_count }}</strong>
                                                        </small>

                                                        @if ($meal->order_count > 0)
                                                            <small class="text-muted ms-2">
                                                                Order:
                                                                <strong class="text-success">{{ $meal->order_count }}</strong>
                                                            </small>
                                                        @endif

                                                    </div>

                                                    @if ($meal->order_count <= 0)
                                                        <button class="btn btn-outline-danger btn-sm delete-meal"
                                                            data-id="{{ $meal->id }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="left" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#restockModal" data-meal-id="{{ $meal->id }}"
                                                            data-stock="{{ $meal->stock }}" title="Restock">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </button>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>
                                    @endforeach



                                    <div class="mt-1">
                                        <button type="button" class="btn btn-warning w-100 add-category-btn"
                                            data-category-id="{{ $category->id }}"
                                            data-category-name="{{ $category->name }}">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            More {{ $category->name }} Item
                                        </button>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>



            <div class="modal fade" id="orderDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Order Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="orderDetailsBody">
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="restockModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form id="restockForm" action="{{ route('vendor.food.stock.update') }}" method="POST">
                                @csrf

                                <input type="hidden" name="meal_id" id="restockMealId">

                                <div class="mb-4">
                                    <label class="form-label small">Add Stock</label>
                                    <input type="number" class="form-control form-control-sm" name="stock" min="1"
                                        required>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    Update Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            @php
                $foods = App\Models\Food::where('user_id', auth()->id())->get();
            @endphp

            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add Food</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="todayMealForm" method="POST" action="{{ route('vendor.food.set_today_meal') }}">
                            @csrf

                            <input type="hidden" name="food_category_id[]" id="food_category_id">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Foods</label>
                                    <select class="form-select select2" name="food_id">
                                        <option value="">Select Food</option>
                                        @foreach ($foods as $food)
                                            <option value="{{ $food->id }}">{{ $food->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger error-text food_id_error"></small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price">
                                        <small class="text-danger error-text price_error"></small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Stock</label>
                                        <input type="number" class="form-control" name="stock">
                                        <small class="text-danger error-text stock_error"></small>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Today Meal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



        </div>
    @endrole


@endsection



@section('script')
    <script>
        function submitStatusForm(orderId, status) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to change the order status.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, confirm',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('statusForm');

                    form.action = form.action.replace('ID_HERE', orderId);

                    document.getElementById('statusInput').value = status;

                    form.submit();
                }
            });
        }
    </script>

    <script>
        function viewOrder(id) {
            $("#orderDetailsModal").modal("show");

            $("#orderDetailsBody").html(`
            <div class="text-center py-5">
                <div class="spinner-border"></div>
                <p class="mt-2">Loading...</p>
            </div>
        `);

            $.ajax({
                url: "{{ route('vendor.order.details') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    $("#orderDetailsBody").html(response);
                },
                error: function() {
                    $("#orderDetailsBody").html(`
                    <div class="alert alert-danger">
                        Failed to load order details.
                    </div>
                `);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {

            const modalEl = document.getElementById('addCategoryModal');
            const modal = new bootstrap.Modal(modalEl);

            $(document).on('click', '.add-category-btn', function() {

                const categoryId = $(this).data('category-id');
                const categoryName = $(this).data('category-name');

                $('#food_category_id').val(categoryId);
                $('#modalTitle').text('Add ' + categoryName);

                modal.show();
            });

            modalEl.addEventListener('shown.bs.modal', function() {

                if (!$('.select2').hasClass('select2-hidden-accessible')) {
                    $('.select2').select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Select Food',
                        dropdownParent: $('#addCategoryModal')
                    });
                }
            });
            modalEl.addEventListener('hidden.bs.modal', function() {
                $('.select2').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });

                const form = document.getElementById('todayMealForm');
                if (form) {
                    form.reset();
                }
                $('.error-text').text('');
                $('.form-control, .form-select').removeClass('is-invalid');
            });

            $(document).on('submit', '#todayMealForm', function(e) {
                e.preventDefault();

                const form = this;

                $('.error-text').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: $(form).serialize(),

                    success: function(res) {
                        modal.hide();
                        location.reload();
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {
                                $(`.${key}_error`).text(value[0]);
                                $(`[name="${key}"]`).addClass('is-invalid');
                            });
                        }
                    }
                });
            });

        });
    </script>



    <script>
        $(document).ready(function() {

            $(document).on('click', '.delete-meal', function() {

                let mealId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This food will be removed from today’s meal!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ route('vendor.food.delete_today_meal') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: mealId
                            },
                            success: function(res) {

                                Swal.fire(
                                    'Deleted!',
                                    res.message,
                                    'success'
                                );

                                location.reload();
                            },
                            error: function(xhr) {

                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON?.message ??
                                    'Something went wrong',
                                    'error'
                                );
                            }
                        });

                    }
                });
            });

        });
    </script>


    <script>
        $(document).ready(function() {

            $('#restockModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);

                $('#restockMealId').val(button.data('meal-id'));
                $('#currentStock').val(button.data('stock'));

                $('#restockForm input[name="stock"]').val('');
            });

            $('#restockForm').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message ?? 'Something went wrong', 'error');
                        }
                    },
                    error: function(xhr) {

                        let message = 'Validation failed';

                        if (xhr.responseJSON) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire('Error', message, 'error');
                    }
                });
            });

        });
    </script>


    <script>
        $(document).ready(function() {

            $('#selectAll').on('change', function() {
                $('.order-checkbox').prop('checked', this.checked);
                toggleBulkActions();
            });

            $(document).on('change', '.order-checkbox', function() {
                toggleBulkActions();
            });

            function toggleBulkActions() {
                let checkedCount = $('.order-checkbox:checked').length;
                $('#bulkActions').toggleClass('d-none', checkedCount === 0);
            }

        });

        function bulkSubmit(status) {

            let ids = $('.order-checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (ids.length === 0) return;

            if (!confirm('Are you sure you want to update selected orders?')) return;

            $('#bulkStatus').val(status);
            $('#bulkOrderIds').val(ids.join(','));
            $('#bulkForm').submit();
        }
    </script>
@endsection
