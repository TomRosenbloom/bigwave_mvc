<?php

class UserController extends BaseController
{

    public function register()
    {
        if($this->getRequest()->getMethod() === 'POST') {
            $post_data = $this->getRequest()->getPostVars();
        } else {
            $data = [
                'name' => '',
                'email' => '',
                'pwd' => '',
                'conf_pwd' => '',
                'name_err' => '',
                'email_err' => '',
                'pwd_err' => '',
                'conf_pwd_err' => '',
            ];
            $this->view('user/register', $data);
        }

    }

    public function login()
    {
        if($this->getRequest()->getMethod() === 'POST') {
            $post_data = $this->getRequest()->getPostVars();
        } else {
            $data = [
                'email' => '',
                'pwd' => '',
                'email_err' => '',
                'pwd_err' => '',
            ];
            $this->view('user/login', $data);
        }

    }
}
