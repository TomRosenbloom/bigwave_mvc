<?php

class UserController extends BaseController
{

    public function __construct()
    {
        $this->model('User');
    }

    public function register()
    {
        if($this->getRequest()->getMethod() === 'POST') {
            $post_data = $this->getRequest()->getPostVars();

echo $this->model->isValid($post_data);

            $name = trim($post_data['name']);
            $email = trim($post_data['email']);
            $password = trim($post_data['pwd']);
            $conf_pwd = trim($post_data['conf_pwd']);
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
