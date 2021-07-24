<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Auth;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $expiredDate = Carbon::parse($this->end_date);
        
        if($expiredDate < Carbon::now()){
            $days = "Overdue!";
        }else{
            $days = Carbon::now()->diffInDays($expiredDate)." Days Left";
        };

        return [
            'user_id' => $this->user_id,
            'id' => $this->id,
            'id' => $this->id,
            'task_name' => $this->task_name,
            'task_level' => $this->task_level,
            'status' => $this->status,
            'start_date' => Carbon::parse($this->start_date)->format('Y-m-d'),
            'end_date' => Carbon::parse($this->end_date)->format('Y-m-d'),
            'file_name' => $this->file_name,
            'expired' => $days ,            
        ]; 
    }
}
