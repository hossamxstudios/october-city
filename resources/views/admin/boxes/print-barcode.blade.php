<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة باركود البوكس: {{ $box->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            padding: 20px;
            direction: rtl;
        }
        .print-actions {
            position: fixed;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 10px;
        }
        .print-actions button {
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        .btn-print {
            background: #0d6efd;
            color: white;
        }
        .btn-close {
            background: #6c757d;
            color: white;
        }
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .print-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .print-header p {
            color: #666;
            font-size: 14px;
        }
        .barcodes-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .barcode-card {
            border: 2px solid #333;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            page-break-inside: avoid;
        }
        .barcode-card .file-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .barcode-card .barcode-value {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .barcode-card .barcode-image {
            margin: 15px 0;
        }
        .barcode-card .barcode-image svg {
            max-width: 100%;
            height: 60px;
        }
        .barcode-card .geo-location {
            font-size: 12px;
            color: #333;
            font-weight: bold;
            margin: 5px 0;
        }
        .barcode-card .physical-location {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        @media print {
            .print-actions {
                display: none;
            }
            body {
                padding: 0;
            }
            .barcode-card {
                border-width: 1px;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">
            🖨️ طباعة
        </button>
        <button class="btn-close" onclick="window.close()">
            ✕ إغلاق
        </button>
    </div>

    <div class="print-header">
        <h1>أرشيف اكتوبر</h1>
        <p>طباعة باركود البوكس</p>
    </div>

    @php
        $rack = $box->rack;
        $stand = $rack?->stand;
        $lane = $stand?->lane;
        $room = $lane?->room;
        $physicalLocation = collect([
            $room?->name ? 'غرفة ' . $room->name : null,
            $lane?->name ? 'ممر ' . $lane->name : null,
            $stand?->name ? 'ستاند ' . $stand->name : null,
            $rack?->name ? 'رف ' . $rack->name : null,
        ])->filter()->implode(' - ') ?: '-';
    @endphp

    <div class="barcodes-grid">
        <div class="barcode-card">
            <div class="file-name">{{ $box->name }}</div>
            <div class="geo-location">📦 بوكس</div>
            <div class="physical-location">{{ $physicalLocation }}</div>
            <div class="barcode-image">
                <svg id="barcode"></svg>
            </div>
            <div class="barcode-value">{{ $box->barcode }}</div>
        </div>
    </div>

    <script src="{{ asset('dashboard/assets/js/barcode.min.js') }}"></script>
    <script>
        JsBarcode("#barcode", "{{ $box->barcode }}", {
            format: "CODE128",
            width: 1,
            height: 50,
            displayValue: false
        });
    </script>
</body>
</html>
