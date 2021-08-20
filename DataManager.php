<?php
class DataManager
{
    private $task_list = [];
    private $next_id = 0;
    private $file_name;

    public function __construct($file_name, $page = 'design') {
        $file_name = $page . "_" . $file_name;
        $this->file_name = $file_name;
        if (file_exists($file_name)) {
            $file_data = json_decode(file_get_contents($file_name), true);
            if (
                is_array($file_data) &&
                isset($file_data['task_list']) &&
                isset($file_data['next_id'])
            ) {
                $this->task_list = $file_data['task_list'];
                $this->next_id = $file_data['next_id'];
            }
        }
    }

    /**
     * Savāc no JS faila visus datus (uzdevumus un nākošo ID) ierakstot tos masīvā;
     * xs
     * @return array $data
     */
    private function getData() {
        return [
            'task_list' => $this->task_list,
            'next_id' => $this->next_id,
        ];
    }

    public function getTasksInverseJson() {
        $task_list = $this->task_list;
        krsort($task_list);
        return json_encode($task_list);
    }

    public function addTask($task_message) {
        $text = htmlentities($task_message, ENT_QUOTES);
    
        $current_id = $this->next_id;
        $this->task_list[$current_id] = [
            'text' =>  $text,
            'status' => false
        ];
    
        ++$this->next_id;

        file_put_contents($this->file_name, json_encode($this->getData()));
    
        return [
            'id' => $current_id,
            'task' => $this->task_list[$current_id],
            'status' => true
        ];
    }


    public function deleteTask(int $id) {
        if (array_key_exists($id, $this->task_list)) {
            unset($this->task_list[$id]);

            file_put_contents($this->file_name, json_encode($this->getData()));
            return true;
        }
        return false;
    }

    public function updateTask($id, $new_text) {
        if (array_key_exists($id, $this->task_list)) {
            $task = htmlentities($new_text, ENT_QUOTES);
            $this->task_list[$id]['text'] = $task;
    
            file_put_contents($this->file_name, json_encode($this->getData()));
        
            return $task;
        }

        return false;
    }


    public function updateTaskStatus($id, $done) {
        if (array_key_exists($id, $this->task_list)) {
            $status = ($done === 'true') ? true : false;
            $this->task_list[$id]['status'] = $status;
    
            file_put_contents($this->file_name, json_encode($this->getData()));
        
            return $status;
        }
    }
}