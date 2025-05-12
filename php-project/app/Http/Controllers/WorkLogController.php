<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WorkLogController extends Controller
{
    // Show all logs with optional date filter
        public function index(Request $request)
        {
            $query = WorkLog::query();

            if ($request->has('date')) {
                $query->whereDate('work_date', $request->input('date'));
            }

            $workLogs = $query->orderBy('work_date', 'desc')->paginate(10);

            return response()->json($workLogs);
        }


    // Show create form
    public function create()
    {
        return response()->json(['message' => 'Display create form']);
    }

    // Store new work log
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_type' => 'required|in:Development,Test,Document',
            'job_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after_or_equal:start_time',
            'status' => 'required|in:ดำเนินการ,เสร็จสิ้น,ยกเลิก',
            'work_date' => 'required|date',
        ]);

        WorkLog::create($validated);

        return response()->json(['success' => true, 'log' => $workLog]);

    }

    // Show edit form
    public function edit(WorkLog $workLog)
    {
        return response()->json(['workLog' => $workLog]);
    }

    // Update work log
    public function update(Request $request, WorkLog $workLog)
    {
        $validated = $request->validate([
            'job_type' => 'required|in:Development,Test,Document',
            'job_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after_or_equal:start_time',
            'status' => 'required|in:ดำเนินการ,เสร็จสิ้น,ยกเลิก',
            'work_date' => 'required|date',
        ]);

        $workLog->update($validated);

        return response()->json(['success' => true, 'log' => $workLog]);
    }

    // Delete work log
    public function destroy(WorkLog $workLog)
    {
        $workLog->delete();
        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }

    // Daily
    public function dailyReport(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $logs = WorkLog::whereDate('work_date', $date)->get();

        return response()->json(['logs' => $logs, 'date' => $date]);
    }

    // Monthly
    public function monthlySummary(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $summary = WorkLog::select('status', \DB::raw('count(*) as total'))
            ->whereMonth('work_date', Carbon::parse($month)->month)
            ->whereYear('work_date', Carbon::parse($month)->year)
            ->groupBy('status')
            ->get();

        return response()->json(['summary' => $summary, 'month' => $month]);
    }
}