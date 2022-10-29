<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Traits\ApiResponse;
use Exception;

class TaskController extends Controller
{

    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->success(Task::all(), 'All tasks.');
        } catch (Exception $e) {
            return $this->error('An error occurred. Try again later.', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            return $this->success(auth()->user()->tasks()->create($request->all()), 'Task created successfully.');
        } catch (Exception $e) {
            return $this->error('An error occurred. Try again later.', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        try {
            return $this->success($task, 'Task details.');
        } catch (Exception $e) {
            return $this->error('An error occurred. Try again later.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $updated = tap($task, function($task) use($request){
                $task->text =$request->input('text');
                $task->day =$request->input('day');
                $task->save();
            });
            return $this->success($updated, 'Task updated successfully.');
        } catch (Exception $e) {
            return $this->error('An error occurred. Try again later.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return $this->success([], 'Task deleted successfully.');
        } catch (Exception $e) {
            return $this->error('An error occurred. Try again later.', 500);
        }
    }
}
