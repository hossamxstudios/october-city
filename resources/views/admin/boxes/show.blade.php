<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>تفاصيل البوكس: {{ $box->name }} - أرشيف اكتوبر</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                <!-- Header -->
                <div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="page-icon">
                                <div class="avatar avatar-lg bg-primary-subtle rounded-3">
                                    <i class="ti ti-box fs-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $box->name }}</h4>
                            <div class="text-muted">
                                <code class="bg-light px-2 py-1 rounded">{{ $box->barcode }}</code>
                                <span class="mx-2">•</span>
                                <span class="badge bg-primary-subtle text-primary">{{ $box->files->count() }} ملف</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="gap-2 btn-list">
                                <a href="{{ route('admin.boxes.print-barcode', $box) }}" class="btn btn-success" target="_blank">
                                    <i class="ti ti-printer me-1"></i>طباعة الباركود
                                </a>
                                <a href="{{ route('admin.boxes.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Box Info Card -->
                    <div class="col-lg-4 mb-4">
                        <!-- Barcode Card -->
                        <div class="border-0 shadow-sm card mb-4">
                            <div class="card-body text-center">
                                <div class="mb-3 p-3 bg-white border rounded">
                                    <svg id="boxBarcode"></svg>
                                </div>
                                <div class="fw-bold">{{ $box->barcode }}</div>
                            </div>
                        </div>

                        <!-- Location Card -->
                        <div class="border-0 shadow-sm card mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">
                                    <i class="ti ti-map-pin me-2"></i>موقع البوكس
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                @php
                                    $rack = $box->rack;
                                    $stand = $rack?->stand;
                                    $lane = $stand?->lane;
                                    $room = $lane?->room;
                                @endphp
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted"><i class="ti ti-home me-2"></i>الغرفة</span>
                                        <span class="fw-medium">{{ $room?->name ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted"><i class="ti ti-road me-2"></i>الممر</span>
                                        <span class="fw-medium">{{ $lane?->name ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted"><i class="ti ti-layout-board me-2"></i>الستاند</span>
                                        <span class="fw-medium">{{ $stand?->name ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted"><i class="ti ti-box-multiple me-2"></i>الرف</span>
                                        <span class="fw-medium">{{ $rack?->name ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Stats Card -->
                        <div class="border-0 shadow-sm card">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">
                                    <i class="ti ti-chart-bar me-2"></i>إحصائيات
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted">عدد الملفات</span>
                                        <span class="badge bg-primary">{{ $box->files->count() }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted">تاريخ الإنشاء</span>
                                        <span>{{ $box->created_at->format('Y/m/d') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Files List -->
                    <div class="col-lg-8">
                        <div class="border-0 shadow-sm card">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="ti ti-files me-2"></i>الملفات في هذا البوكس
                                        <span class="badge bg-primary ms-2">{{ $box->files->count() }}</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @if($box->files->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>الملف</th>
                                                    <th>العميل</th>
                                                    <th>الموقع الجغرافي</th>
                                                    <th>الباركود</th>
                                                    <th>تاريخ الإضافة</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($box->files as $file)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="p-2 rounded bg-danger bg-opacity-10 me-2">
                                                                    <i class="ti ti-file-type-pdf text-danger"></i>
                                                                </div>
                                                                <div>
                                                                    <span class="fw-medium">{{ $file->file_name }}</span>
                                                                    @if($file->pages_count)
                                                                        <br><small class="text-muted">{{ $file->pages_count }} صفحة</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($file->client)
                                                                <a href="{{ route('admin.clients.show', $file->client) }}" class="text-decoration-none">
                                                                    {{ $file->client->name }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $file->land?->zone?->name ?? '-' }}
                                                                @if($file->land?->land_no)
                                                                    - قطعة {{ $file->land->land_no }}
                                                                @endif
                                                            </small>
                                                        </td>
                                                        <td>
                                                            @if($file->barcode)
                                                                <code class="bg-light px-2 py-1 rounded">{{ $file->barcode }}</code>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">{{ $file->created_at->format('Y/m/d') }}</small>
                                                        </td>
                                                        <td>
                                                            @if($file->client)
                                                                <a href="{{ route('admin.clients.show', $file->client) }}" class="btn btn-sm btn-outline-primary">
                                                                    <i class="ti ti-external-link"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="py-5 text-center text-muted">
                                        <i class="ti ti-folder-off fs-1 d-block mb-3"></i>
                                        <p class="mb-0">لا توجد ملفات في هذا البوكس</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.main.scripts')
    <script src="{{ asset('dashboard/assets/js/barcode.min.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        JsBarcode("#boxBarcode", "{{ $box->barcode }}", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: false,
            margin: 10
        });
    });
    </script>
</body>
</html>
