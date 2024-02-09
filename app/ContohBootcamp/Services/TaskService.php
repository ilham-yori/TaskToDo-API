<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService {
	private TaskRepository $taskRepository;

	public function __construct() {
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if(isset($formData['title']))
		{
			$editTask['title'] = $formData['title'];
		}

		if(isset($formData['description']))
		{
			$editTask['description'] = $formData['description'];
		}

        if(array_key_exists('assigned',$formData))
        {
            $editTask['assigned'] = $formData['assigned'];
        }

		$id = $this->taskRepository->save($editTask);
		return $id;
	}

    public function deleteTask(string $taskId){

        $task = $this->taskRepository->deleteTaskById($taskId);
		return $task;
    }

    public function addSubTask(array $existTask, string $title, string $description){

        $subTasks = isset($existTask['subtasks']) ? $existTask['subtasks'] : [];

		$subTasks[] = [
			'_id'=> (string) new \MongoDB\BSON\ObjectId(),
			'title'=>$title,
			'description'=>$description
		];

        $existTask['subtasks'] = $subTasks;
        $id = $this->taskRepository->save($existTask);
		return $id;
    }

    public function deleteSubTask(array $existTask, string $subtaskId){

        $subTask = isset($existTask['subtasks']) ? $existTask['subtasks'] : [];

		$subTask = array_filter($subTask, function($subtask) use($subtaskId) {
			if($subtask['_id'] == $subtaskId)
			{
				return false;
			} else {
				return true;
			}
		});

		$subTask = array_values($subTask);

        $existTask['subtasks'] = $subTask;

        $id = $this->taskRepository->save($existTask);
		return $id;
    }
}
