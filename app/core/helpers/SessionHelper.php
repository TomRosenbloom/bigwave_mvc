<?php

namespace App;

class SessionHelper
{
    /**
     * handle flash message
     * 
     * maybe I'm thick, but the logic of this (from Traversy) seems confusing because the function does more than one thing
     * 1. creates a flash message: 
     *      if a message text is supplied, and there isn't an existing session var for this message, 
     *      use session vars to create a flash message (and class name for displaying the message)
     * 2. displays a flash message:
     *      if a message text is not supplied, and there is an existing session var for this message,
     *      display the message, then destroy the session vars
     * The other confusing thing is having if(!empty($_SESSION[$name])) within if(empty($_SESSION[$name]))
     * 
     * @param string $name An identifier for the message
     * @param string $message The content of the message
     * @param string $class (optional) class to display the message
     */
    public function flash($name = '', $message = '', $class = 'alert alert-success')
    {
        if(!empty($name)){
            if(!empty($message) && empty($_SESSION[$name])){
                
                if(!empty($_SESSION[$name])){
                    unset($_SESSION[$name]);
                }
                if(!empty($_SESSION[$name . '_class'])){
                    unset($_SESSION[$name . '_class']);
                }
                
                $_SESSION[$name] = $name;
                $_SESSION[$name . '_class'] = $class;
            } elseif(empty($message) && !empty($_SESSION[$name])){
                $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
                echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name . '_class']);
            }

        }
    }
}
