<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs
     */
    public function index()
    {
        // Only admin can view all activity logs
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $logs = ActivityLog::with('user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(50);

        return view('activity-logs.index', compact('logs'));
    }

    /**
     * Display activity logs for current user
     */
    public function myActivity()
    {
        $logs = ActivityLog::where('user_id', auth()->id())
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('activity-logs.my-activity', compact('logs'));
    }

    /**
     * Show activity log details
     */
    public function show(ActivityLog $activityLog)
    {
        if (auth()->id() !== $activityLog->user_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Filter activity logs by module
     */
    public function filterByModule($module)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $logs = ActivityLog::where('module', $module)
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(50);

        return view('activity-logs.index', compact('logs'));
    }

    /**
     * Filter activity logs by action
     */
    public function filterByAction($action)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $logs = ActivityLog::where('action', $action)
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(50);

        return view('activity-logs.index', compact('logs'));
    }

    /**
     * Export activity logs to CSV
     */
    public function export()
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->get();

        $filename = 'activity_logs_' . date('YmdHis') . '.csv';
        $handle = fopen('php://memory', 'w+');

        // Write header
        fputcsv($handle, ['Timestamp', 'User', 'Action', 'Module', 'Description', 'IP Address']);

        // Write data
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->created_at,
                $log->user->name,
                $log->action,
                $log->module,
                $log->description,
                $log->ip_address,
            ]);
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        fclose($handle);

        return response($contents, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Clear all activity logs
     */
    public function clear(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        ActivityLog::truncate();

        return redirect()->route('activity-logs.index')->with('success', 'All activity logs have been cleared.');
    }
}
