<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $now = Carbon::now();
        $tasks = Tasks::whereDate('start_date',$now)->get();
        return response()->json(['tasks' => TaskResource::collection($tasks)]);
    }
    

    public function tasksWeek()
    {
        $now = Carbon::now();
        $from = $now->startOfWeek()->format('Y-m-d');
        $to =   $now->endOfWeek()->format('Y-m-d');

        $tasks = Tasks::whereBetween('start_date', [$from,$to])->get();

        return response()->json(['tasks' => TaskResource::collection($tasks)]);
    }

    public function tasksMonth()
    {
        $now = Carbon::now();

        $from = $now->startOfMonth()->format('Y-m-d');
        $to =   $now->endOfMonth()->format('Y-m-d');

        $tasks = Tasks::whereBetween('start_date', [$from,$to])->get();

        return response()->json(['tasks' => TaskResource::collection($tasks)]);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validation = $this->validate($request, [
            'task_name' => 'required|max:255',
            'task_level' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($request->file('file_name') == null){
            $file_name ="";
        }
        else{

            $filepath = $request->file('file_name')->store('public');
            $file_name = str_replace('public/', '', $filepath);
        }
        $tasks = Tasks::create([
            'user_id' => '1',
            'task_name' => $request->task_name,
            'task_level' => $request->task_level,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'file_name' => $file_name,
        ]);
        return response()->json([ "code" => 200, "message" => "added successfully" , "data" =>$tasks->refresh() ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tasks =  Tasks::find($id);
        return response()->json(['tasks' => new TaskResource($tasks)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vallidation = $this->validate($request, [
            'task_name' => 'required|max:255',
            'task_level' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        
        
        $tasks =  Tasks::find($id);
            
        if($request->hasFile('file_name')){
            $filepath = $request->file('file_name')->store('public');
            $file_name = str_replace('public/', '', $filepath);
            $tasks->file_name = $file_name;
        }else{

            $file_name = $tasks->file_name;
        }

        $tasks->update([

            'user_id' => '1',
            'task_name' => $request->task_name,
            'task_level' => $request->task_level,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'file_name' => $file_name,
        ]);

        return response()->json(['code' => 200, "message" => "updated successfully" , "data" =>$tasks->refresh() ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tasks =  Tasks::find($id);
        $tasks->delete();
        return response()->json(["code" => 200,"message" => "Tasks Deleted successfully"]);
    }

    public function updateStatus($id){
        $tasks = Tasks::find($id);
        if($tasks->status == "complete"){
            $tasks->status = "not_complete";
            $tasks->save();
        }else{
            $tasks->status = "complete";
            $tasks->save();
        }
        return response()->json(["code" => 200,"message" => "Tasks Update successfully" , "data" =>$tasks->refresh()]);
    }
}
