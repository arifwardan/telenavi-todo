<?php

namespace App\Http\Controllers;

use App\Exports\TodosExport;
use App\Models\Todo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Todo::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'assignee' => 'required|string',
            'due_date' => 'required|date|after_or_equal:today',
            'time_tracked' => 'nullable|integer',
            'status' => 'in:pending,open,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
        ]);

        $data = $request->all();
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        $todo = Todo::create($data);
        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $todo->update($request->all());
        return response()->json($todo);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json(null, 204);
    }

    public function exportToExcel(Request $request)
    {
        $filters = $request->all(); // Ambil semua parameter untuk filtering

        return Excel::download(new TodosExport($filters), 'todos_report.xlsx');
    }
}
