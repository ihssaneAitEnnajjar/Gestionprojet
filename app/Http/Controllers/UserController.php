<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {

       $tasks = Task::select('status', DB::raw('count(*) as data'))
        ->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ])
        ->groupBy('status')
        ->get();

        $tasksWithStatusAsKey = [];
        foreach ($tasks as $task) {
            $tasksWithStatusAsKey[$task['status']] = $task['data'];
        }

        $tasksByStatus = [
            'labels' => array_keys($tasksWithStatusAsKey),
            'datasets' => [
                [ 
                    'label'=> 'Tasks',
                    'backgroundColor' => [
                        '#0779E4',
                        '#4CBBB9',
                        '#77D8D8',
                        '#EFF3C6',
                    ],
                    'data' => array_values($tasksWithStatusAsKey),
                ],
            ]
        ];

        $tasksPerMonth = Task::select(DB::raw('count(*) as data'), DB::raw("DATE_FORMAT(created_at, '%M') as month"))
            ->whereYear('created_at', now()->format('Y'))
            ->groupBy('month')
            ->get();

        $months = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ];

        foreach ($tasksPerMonth as $task) {
            $months[$task['month']] = $task['data'];
        }

        $values = [
            'labels' => array_keys($months),
            'datasets' => [
                [ 
                    'label'=> 'Tasks',
                    'backgroundColor' => '#4CBBB9',
                    'data' => array_values($months),
                ],
            ]
        ];

        return inertia('Users/Index', [
            'tasksPerMonth' => $values,
            'tasksByStatus' => $tasksByStatus,
        ]);
    }
    //
}
