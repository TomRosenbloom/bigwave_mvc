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

            $data = [
                'name' => trim($post_data['name']),
                'email' => trim($post_data['email']),
                'password' => trim($post_data['password']),
                'conf_pwd' => trim($post_data['conf_pwd']),
                'name_err' => '',
                'email_err' => '',
                'pwd_err' => '',
                'conf_pwd_err' => ''
            ];

            // validation
            if(empty($post_data['name'])){
                $data['name_err'] = 'Please enter email';
            }
            if(empty($post_data['email'])){
                $data['email_err'] = 'Please enter email';
            } if($this->model->confirmUserByEmail($post_data['email'])) {
                $data['email_err'] = 'Email already registered';
            }
            if(empty($post_data['password'])){
                $data['pwd_err'] = 'Please enter a password';
            } elseif(strlen($post_data['password']) < 6){
                $data['pwd_err'] = 'Password must be at least 6 characters';
            }
            if(empty($post_data['conf_pwd'])){
                $data['conf_pwd_err'] = 'Please confirm password';
            } elseif($post_data['password'] != $post_data['conf_pwd']){
                $data['conf_pwd_err'] = 'Passwords do not match';
            }

            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['pwd_err'])
                        && empty($data['conf_pwd_err'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->model->register($data)){
                    header('location: ' . URL_ROOT . '/user/register');
                } else {
                    die('error');
                }

                die('success');
            } else {
                $this->view('user/register', $data);
            }


        } else {

            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
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

            $data = [
                'email' => trim($post_data['email']),
                'password' => trim($post_data['password']),
                'email_err' => '',
                'pwd_err' => '',
            ];

            // validation
            if(empty($post_data['email'])){
                $data['email_err'] = 'Please enter email';
            }
            if(empty($post_data['password'])){
                $data['pwd_err'] = 'Please enter a password';
            }

            if(empty($data['email_err']) && empty($data['pwd_err'])){
                die('success');
            } else {
                $this->view('user/register', $data);
            }

        } else {
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'pwd_err' => '',
            ];
            $this->view('user/login', $data);
        }

    }
}
