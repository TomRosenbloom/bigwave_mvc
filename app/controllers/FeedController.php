<?php

class FeedController extends BaseController
{
    public function read()
    {
        echo "<br>hello";
        $this->view('feed/read');
    }

}
