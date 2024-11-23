<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auth::user()->task;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'task' => ['required', 'string']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return $validator->errors();
        }

        $data = $validator->validated();

        $task = $user->task()->create([
            'task' => $data['task']
        ]);

        Notification::create([
            'title' => "Task Created",
            'message' => $user->name . " Added a new Task",
        ]);

        return response()->json([
            'success' => true,
            'status' => 201,
            'task' => $task,
            'message' => 'New Task Created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $rules = [
            'task' => ['required', 'string']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return $validator->errors();
        }

        $data = $validator->validated();

        $result = $task->update([
            'task' => $data['task']
        ]);

        Notification::create([
            'title' => "Task Created",
            'message' => $task->user->name . " Updated an existing Task",
        ]);

        return response()->json([
            'success' => true,
            'status' => 201,
            'task' => $task,
            'message' => 'Task Updated successfully'
        ], 201);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $user = $task->user->name;

        $task->delete();

        Notification::create([
            'title' => "Task Created",
            'message' => $user . " Added a new Task",
        ]);

        return response()->json([
            'success' => true,
            'status' => 201,
            'message' => 'Task Deleted'
        ], 201);
        
    }

    public function complete(Task $task){

        if (!$task->completed) {
            $task->update([
                'completed' => true
            ]);

            Notification::create([
                'title' => "Task Created",
                'message' => $task->user->name . " Marked a task as completed",
            ]);

            return response()->json([
                'success' => true,
                'status' => 201,
                'task' => $task,
                'message' => 'Task Marked as completed'
            ], 201);
        }

        return response()->json([
            'success' => true,
            'status' => 201,
            'task' => $task,
            'message' => 'Task has been marked completed already'
        ], 201);

    }

    public function completed(){

        return Auth::user()->task()->where('completed', 1)->get();
    }
}
