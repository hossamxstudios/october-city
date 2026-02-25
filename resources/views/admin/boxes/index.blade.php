<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>إدارة البوكسات - أرشيف اكتوبر</title>
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
                            <h4 class="mb-1">إدارة البوكسات</h4>
                            <div class="text-primary">
                                <span class="badge bg-primary-subtle text-primary me-2">{{ $stats['total_boxes'] }} بوكس</span>
                                عرض وإدارة جميع البوكسات ومحتوياتها
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="gap-2 btn-list">
                                <a href="{{ route('admin.boxes.export') }}" class="btn btn-ghost-success">
                                    <i class="ti ti-file-spreadsheet me-1"></i>
                                    <span class="d-none d-sm-inline">تصدير CSV</span>
                                </a>
                                <button type="button" class="btn btn-primary" id="bulkPrintBtn" disabled>
                                    <i class="ti ti-printer me-1"></i>طباعة المحدد
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="mb-4 row g-3">
                    <div class="col-md-4">
                        <div class="border-0 shadow-sm card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="p-3 rounded avatar-md bg-primary bg-opacity-10">
                                            <i class="ti ti-box fs-4 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-1">{{ number_format($stats['total_boxes']) }}</h3>
                                        <p class="mb-0 text-muted">إجمالي البوكسات</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-0 shadow-sm card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="p-3 rounded avatar-md bg-success bg-opacity-10">
                                            <i class="ti ti-files fs-4 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-1">{{ number_format($stats['boxes_with_files']) }}</h3>
                                        <p class="mb-0 text-muted">بوكسات تحتوي ملفات</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-0 shadow-sm card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="p-3 rounded avatar-md bg-warning bg-opacity-10">
                                            <i class="ti ti-box-off fs-4 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-1">{{ number_format($stats['empty_boxes']) }}</h3>
                                        <p class="mb-0 text-muted">بوكسات فارغة</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter -->
                <div class="mb-4 card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.boxes.index') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0"
                                            placeholder="بحث بالاسم أو الباركود..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="room_id" class="form-select">
                                        <option value="">كل الغرف</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                                {{ $room->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="has_files" class="form-select">
                                        <option value="">الكل</option>
                                        <option value="yes" {{ request('has_files') === 'yes' ? 'selected' : '' }}>يحتوي ملفات</option>
                                        <option value="no" {{ request('has_files') === 'no' ? 'selected' : '' }}>فارغ</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <i class="ti ti-filter"></i>
                                        </button>
                                        <a href="{{ route('admin.boxes.index') }}" class="btn btn-outline-secondary">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="border-0 shadow-sm card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </th>
                                        <th>البوكس</th>
                                        <th>الباركود</th>
                                        <th>الموقع</th>
                                        <th class="text-center">عدد الملفات</th>
                                        <th style="width: 120px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($boxes as $box)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input box-checkbox" value="{{ $box->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="p-2 rounded bg-light me-2">
                                                        <i class="ti ti-box text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.boxes.show', $box) }}" class="fw-medium text-dark text-decoration-none">
                                                            {{ $box->name }}
                                                        </a>
                                                        @if($box->description)
                                                            <br><small class="text-muted">{{ Str::limit($box->description, 40) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <code class="bg-light px-2 py-1 rounded">{{ $box->barcode ?? '-' }}</code>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="ti ti-map-pin me-1"></i>{{ $box->full_path ?: '-' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                @if($box->files_count > 0)
                                                    <span class="badge bg-primary">{{ $box->files_count }} ملف</span>
                                                @else
                                                    <span class="badge bg-secondary">فارغ</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.boxes.show', $box) }}" class="btn btn-outline-primary" title="عرض">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.boxes.print-barcode', $box) }}" class="btn btn-outline-success" title="طباعة باركود" target="_blank">
                                                        <i class="ti ti-printer"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-5 text-center text-muted">
                                                <i class="ti ti-box-off fs-1 d-block mb-2"></i>
                                                لا توجد بوكسات
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($boxes->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-center">
                            {{ $boxes->links() }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Bulk Print Form -->
                <form id="bulkPrintForm" action="{{ route('admin.boxes.bulk-print') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="box_ids" id="bulkPrintIds">
                </form>
            </div>
        </div>
    </div>
    @include('admin.main.scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.box-checkbox');
        const bulkPrintBtn = document.getElementById('bulkPrintBtn');
        const bulkPrintForm = document.getElementById('bulkPrintForm');
        const bulkPrintIds = document.getElementById('bulkPrintIds');

        function updateBulkBtn() {
            const checked = document.querySelectorAll('.box-checkbox:checked');
            bulkPrintBtn.disabled = checked.length === 0;
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkBtn();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkBtn);
        });

        bulkPrintBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.box-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.value);
            bulkPrintIds.value = JSON.stringify(ids);
            bulkPrintForm.submit();
        });
    });
    </script>
</body>
</html>
