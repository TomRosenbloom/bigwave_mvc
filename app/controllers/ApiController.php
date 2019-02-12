<?php

//use App\Event;

class ApiController extends BaseController
{  
    
    /**
     * get all event data from db and export as json
     *
     * @return [type] [description]
     */
    public function readAll()
    {
        $event = new Event();
        $data = $event->getAll();
        $json = json_encode($data);
        $this->view('api/readAll', ['json'=>$json]);
    }

    /**
     * get one event from db and display as json, using a view
     *
     * @return [type] [description]
     */
    public function readOne($id = '')
    {        
        $event = new Event();
        $data = $event->getOneFromId($id);
        $json = json_encode($data);
        $this->view('api/readOne', ['json'=>$json]);
    }
    
}

