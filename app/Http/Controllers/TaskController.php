<?php

namespace MyPlugin\Http\Controllers;

use MyPlugin\Models\Task;

class TaskController extends Controller {

    public function store(){
     $data = $this->request->task;
     $columnName = $this->request->columnName;
     $task = Task::store($data, $columnName); 

     return $this->sendSuccess([
        'Task' => $task,
        'ColumnaName' => $columnName
    ]);
    }

    public function getTask(){

    $allTask = Task::getTask(); 

     return $this->sendSuccess([
        'allTask' => $allTask
    ]);
    }

    public function updateTask(){
        $key = $this->request->key;
        $value = $this->request->value;
        $id = $this->request->ID;

        $response = Task::updateTask($key, $value, $id);
   
        return $this->sendSuccess([
           'key' => $key,
           'value' => $value,
           'id' => $id,
       ]);
    }

    public function deleteTask(){
        $id = $this->request->ID;
        $response = Task::deleteTask($id);

        return $this->sendSuccess([
            'id' => $id
        ]);

    }

    public function moveTask(){
        $id = $this->request->ID;
        $toTask = $this->request->toTask;
        $response = Task::moveTask($toTask, $id);
        
        return $this->sendSuccess([
            'id' => $id,
            'toTask' => $toTask
        ]);
    }
};