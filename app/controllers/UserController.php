<?php

use App\UrlHelper;
use App\SessionHelper;

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
                    SessionHelper::flash('register_success','registered');
                    UrlHelper::redirect('user/login'); // how can I access redirect directly?
                                                       // well, however you slice it, you can't because the function
                                                       // only exists when the UrlHelper class has been instantiated
                                                       // Well... not actually instantiated as we are accessing the class
                                                       // method statically, but the function/method can't exist independently
                                                       // Why doesn't PHP complain that redirect isn't declared as static?
                
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

            // validate email and password
            if(empty($post_data['email'])){
                $data['email_err'] = 'Please enter email';
            }
            if(empty($post_data['password'])){
                $data['pwd_err'] = 'Please enter a password';
            }

            // check existing user
            if(!$this->model->confirmUserByEmail($data['email'])){
                $data['email_err'] = 'Email not registered';
            }
            
            // attempt to login the user with validated credentials
            // There's something about this Traversy logic that I don't like
            // too many conditionals I think...
            if(empty($data['email_err']) && empty($data['pwd_err'])){
                $loggedInUser = $this->model->login($data['email'], $data['password']); // attempt login
                if($loggedInUser){
                    die('success');
                } else {
                    $data['pwd_err'] = 'Password incorrect';
                    $this->view('user/login', $data);
                }
            } else {
                $this->view('user/login', $data); // re-display the form
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
