<?php

namespace MyPlugin\Models;

use NinjaCharts\Framework\Support\Arr;
use MyPlugin\Framework\Database\Orm\Model as BaseModel;

class Task extends BaseModel {

    protected $table = 'board';

    public static function store($data,$columnName){
      $task = new Task();
      $task->task = $data;
      $task->columnName = $columnName;
      $task->save();
    }

    public static function getTask(){
      $task = new Task();
      $data = $task->get();

     return $data;
    }

    public static function updateTask($key, $value, $id) {
      $task = new Task();
      if($key === 'name'){
        $task->where('id',$id)->update(['task' => $value]);
      }
      else if($key === 'description'){
        $task->where('id',$id)->update(['description' => $value]);
      }
    }

    public static function deleteTask( $id) {
      $task = new Task();
      $task->where('id',$id)->delete();
    }
    
    public static function moveTask($toTask, $id){
      $task = new Task();
      $task->where('id',$id)->update(['columnName' => $toTask]);
    }
}