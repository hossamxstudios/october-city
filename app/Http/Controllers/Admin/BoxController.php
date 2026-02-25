<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\Room;
use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BoxController extends Controller
{
    /**
     * Display a listing of boxes with filters
     */
    public function index(Request $request)
    {
        $query = Box::with(['rack.stand.lane.room', 'files'])
            ->withCount('files');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Room filter
        if ($request->filled('room_id')) {
            $query->whereHas('rack.stand.lane', function ($q) use ($request) {
                $q->where('room_id', $request->room_id);
            });
        }

        // Has files filter
        if ($request->filled('has_files')) {
            if ($request->has_files === 'yes') {
                $query->has('files');
            } else {
                $query->doesntHave('files');
            }
        }

        $boxes = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $rooms = Room::orderBy('name')->get();

        $stats = [
            'total_boxes' => Box::count(),
            'boxes_with_files' => Box::has('files')->count(),
            'empty_boxes' => Box::doesntHave('files')->count(),
        ];

        return view('admin.boxes.index', compact('boxes', 'rooms', 'stats'));
    }

    /**
     * Display a specific box with its files
     */
    public function show(Box $box)
    {
        $box->load([
            'rack.stand.lane.room',
            'files' => function ($q) {
                $q->with(['client', 'land.zone'])
                  ->whereNull('parent_id')
                  ->orderBy('created_at', 'desc');
            }
        ]);

        return view('admin.boxes.show', compact('box'));
    }

    /**
     * Print barcode for a single box
     */
    public function printBarcode(Box $box)
    {
        $box->load('rack.stand.lane.room');

        ActivityLogger::printed(
            "طباعة باركود البوكس: {$box->name}",
            [$box->id],
            ActivityLog::GROUP_PHYSICAL
        );

        return view('admin.boxes.print-barcode', compact('box'));
    }

    /**
     * Bulk print barcodes for multiple boxes
     */
    public function bulkPrintBarcodes(Request $request)
    {
        $boxIds = $request->box_ids;
        if (is_string($boxIds)) {
            $boxIds = json_decode($boxIds, true);
        }
        $request->merge(['box_ids' => $boxIds]);

        $request->validate([
            'box_ids' => 'required|array|min:1',
            'box_ids.*' => 'exists:boxes,id',
        ]);

        $boxes = Box::with('rack.stand.lane.room')
            ->whereIn('id', $request->box_ids)
            ->get();

        ActivityLogger::printed(
            "طباعة باركودات جماعية للبوكسات: " . $boxes->count() . " بوكس",
            $boxes->pluck('id')->toArray(),
            ActivityLog::GROUP_PHYSICAL
        );

        return view('admin.boxes.print-barcodes', compact('boxes'));
    }

    /**
     * Generate barcodes for boxes that don't have one
     */
    public function generateBarcodes(Request $request)
    {
        $boxesWithoutBarcode = Box::whereNull('barcode')->get();

        foreach ($boxesWithoutBarcode as $box) {
            $box->update(['barcode' => Box::generateBarcode()]);
        }

        return redirect()->route('admin.boxes.index')
            ->with('success', "تم توليد باركود لـ {$boxesWithoutBarcode->count()} بوكس");
    }

    /**
     * Export boxes data
     */
    public function export(Request $request)
    {
        $boxes = Box::with(['rack.stand.lane.room'])
            ->withCount('files')
            ->get();

        $filename = 'boxes_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($boxes) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'الاسم',
                'الباركود',
                'الموقع',
                'عدد الملفات',
                'الوصف',
            ]);

            foreach ($boxes as $box) {
                fputcsv($file, [
                    $box->name,
                    $box->barcode,
                    $box->full_path,
                    $box->files_count,
                    $box->description ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
