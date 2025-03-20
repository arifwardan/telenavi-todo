<?php

namespace App\Exports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Http\Request;

class TodosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Todo::query();

        // Filtering berdasarkan input
        if (!empty($this->filters['title'])) {
            $query->where('title', 'like', '%' . $this->filters['title'] . '%');
        }
        if (!empty($this->filters['assignee'])) {
            $assignees = explode(',', $this->filters['assignee']);
            $query->whereIn('assignee', $assignees);
        }
        if (!empty($this->filters['start']) && !empty($this->filters['end'])) {
            $query->whereBetween('due_date', [$this->filters['start'], $this->filters['end']]);
        } elseif (!empty($this->filters['start'])) {
            $query->where('due_date', '>=', $this->filters['start']);
        } elseif (!empty($this->filters['end'])) {
            $query->where('due_date', '<=', $this->filters['end']);
        }
        if (!empty($this->filters['min']) && !empty($this->filters['max'])) {
            $query->whereBetween('time_tracked', [(int)$this->filters['min'], (int)$this->filters['max']]);
        } elseif (!empty($this->filters['min'])) {
            $query->where('time_tracked', '>=', (int)$this->filters['min']);
        } elseif (!empty($this->filters['max'])) {
            $query->where('time_tracked', '<=', (int)$this->filters['max']);
        }
        if (!empty($this->filters['status'])) {
            $statuses = explode(',', $this->filters['status']);
            $query->whereIn('status', $statuses);
        }
        if (!empty($this->filters['priority'])) {
            $priorities = explode(',', $this->filters['priority']);
            $query->whereIn('priority', $priorities);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked (minutes)',
            'Status',
            'Priority'
        ];
    }

    public function map($todo): array
    {
        return [
            $todo->title,
            $todo->assignee,
            $todo->due_date,
            $todo->time_tracked,
            $todo->status,
            $todo->priority
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $totalTodos = $event->sheet->getDelegate()->getHighestRow() - 1;
                $totalTimeTracked = 0;
                for ($i = 2; $i <= $event->sheet->getDelegate()->getHighestRow(); $i++) {
                    $totalTimeTracked += $event->sheet->getDelegate()->getCell('D' . $i)->getValue();
                }

                $event->sheet->appendRows([
                    ['Summary:', '', '', '', '', ''],
                    ['Total Todos:', $totalTodos, '', '', '', ''],
                    ['Total Time Tracked:', $totalTimeTracked . ' minutes', '', '', '', '']
                ], $event);
            }
        ];
    }
}
