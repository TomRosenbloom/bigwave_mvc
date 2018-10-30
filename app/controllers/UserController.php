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

            if($this->isValid($post_data)){
                $name = trim($post_data['name']);
                $email = trim($post_data['email']);
                $password = trim($post_data['password']);
                // $conf_pwd = trim($post_data['conf_pwd']);
            } else {
                $post_errors = $this->getValidationErrors(); // array of arrays

                $data['name'] = $post_data['name'];
                $data['email'] = $post_data['email'];
                $data['password'] = $post_data['password'];
                $data['name_err'] = $post_errors['name'];
                $data['email_err'] = $post_errors['email'];
                $data['pwd_err'] = $post_errors['password'];
                // $data['conf_pwd_err'] = $post_errors['conf_pwd'];
                
                $this->view('user/register', $data);
            }


        } else {

            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                // 'conf_pwd' => '',
                'name_err' => '',
                'email_err' => '',
                'pwd_err' => '',
                // 'conf_pwd_err' => '',
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
