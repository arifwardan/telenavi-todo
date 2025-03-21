<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function getChartData(Request $request)
    {
        $type = $request->query('type');

        switch ($type) {
            case 'status':
                $statusData = Todo::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->get()
                    ->mapWithKeys(fn ($item) => [$item->status => $item->count]);

                return response()->json(['status_summary' => $statusData]);

            case 'priority':
                $priorityData = Todo::selectRaw('priority, COUNT(*) as count')
                    ->groupBy('priority')
                    ->get()
                    ->mapWithKeys(fn ($item) => [$item->priority => $item->count]);

                return response()->json(['priority_summary' => $priorityData]);

            case 'assignee':
                $assigneeData = Todo::selectRaw('assignee,
                        COUNT(*) as total_todos,
                        SUM(CASE WHEN status = \'pending\' THEN 1 ELSE 0 END) as total_pending_todos,
                        SUM(CASE WHEN status = \'completed\' THEN time_tracked ELSE 0 END) as total_timetracked_completed_todos')
                    ->groupBy('assignee')
                    ->get()
                    ->mapWithKeys(fn ($item) => [$item->assignee => [
                        'total_todos' => $item->total_todos,
                        'total_pending_todos' => $item->total_pending_todos,
                        'total_timetracked_completed_todos' => $item->total_timetracked_completed_todos
                    ]]);

                return response()->json(['assignee_summary' => $assigneeData]);

            default:
                return response()->json(['error' => 'Invalid type parameter'], 400);
        }
    }
}
