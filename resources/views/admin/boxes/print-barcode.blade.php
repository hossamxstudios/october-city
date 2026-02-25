<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة باركود البوكس - {{ $box->name }}</title>
    <style>
        @page { size: 38mm 25mm; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; }
        .sticker {
            width: 38mm; height: 25mm; padding: 0.5mm;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 0.3mm; page-break-after: always; overflow: hidden;
        }
        .sticker:last-child { page-break-after: auto; }
        .client-name {
            font-size: 5.5pt; font-weight: bold; text-align: center;
            max-width: 36mm; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis; line-height: 1.1;
        }
        .geo {
            font-size: 5.5pt; text-align: center; color: black; font-weight: bold;
            max-width: 36mm; text-overflow: ellipsis; line-height: 1.1; max-height: 7mm;
            border-bottom: .1mm solid black;
        }
        .physical {
            font-size: 5.5pt; text-align: center; color: black; font-weight: bold;
            max-width: 36mm; max-height: 7mm; line-height: 1;
        }
        .barcode { display: flex; justify-content: center; }
        .barcode svg { max-width: 33mm; height: 8mm; max-height: 12mm; }
        .barcode-text { font-size: 5.5pt; font-family: monospace; text-align: center; line-height: 1; margin-top: 1mm; }
    </style>
</head>
<body>
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
    <div class="sticker">
        <div class="client-name">{{ $box->name }}</div>
        <div class="geo">بوكس</div>
        <div class="physical">{{ $physicalLocation }}</div>
        <div class="barcode">
            <svg id="barcode"></svg>
        </div>
        <div class="barcode-text">{{ $box->barcode }}</div>
    </div>

    <script src="{{ asset('dashboard/assets/js/barcode.min.js') }}"></script>
    <script>
        JsBarcode("#barcode", "{{ $box->barcode }}", {
            format: "CODE128",
            width: 1,
            height: 35,
            displayValue: false,
            margin: 0
        });
        window.onload = function() {
            window.print();
            window.onafterprint = function() { window.close(); };
        };
    </script>
</body>
</html>
