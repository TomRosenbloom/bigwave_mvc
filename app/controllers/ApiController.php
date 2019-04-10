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
    public function readOne()
    {        
        $event = new Event();
        $id = UrlHelper::param_value('id') ?? $event->getFirstId();
        $data = $event->getOneFromId($id);
        $json = json_encode($data);
        $this->view('api/readOne', ['json'=>$json]);
    }
    
//    public function getPage(paginator $paginator)
    public function getPage()
    {
        // get a page of results
        $event = new Event();
        $offset = UrlHelper::param_value('offset') ?? 0;
        $limit = UrlHelper::param_value('limit') ?? 5;
        $data = $event->getLimit($limit, $offset);
        $json = json_encode($data);
        $this->view('api/getPage', ['json'=>$json]);

    }
    
}

