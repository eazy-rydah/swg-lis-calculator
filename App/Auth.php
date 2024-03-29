<?php

namespace App;

use \App\Models\User;

/**
 * Authentication
 * 
 * PHP version 7.0
*/  
class Auth
{
    /**
     * Login the user
     * 
     * @param User $user The user model
     * 
     * @return void
    */ 
    public static function login($user)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['is_admin'] = $user->is_admin;
    }

    /**
     * Logout the user
     * 
     * @return void 
    */ 
    public static function logout()
    {
        // Unset all of the session variables
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally destroy the session
        session_destroy();
    }

    /**
     * Return indicator of whether a user is admin in or not
     * 
     * @return boolean 
    */  
    public static function isAdmin()
    {      
      
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            return true;
        }
        
        return false;
    }

    /**
     * Remember the originally-requested page in the session
     * 
     * @return void
    */  
    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    /**
     * Get the originally-requested page to return to after requiring login, or default to the the homepage
     * 
     * @return void
    */ 
    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/';
    }

    /**
     * Get the current logged-in user, from the session
     * 
     * @return mixed The user model or null if not logged in
    */ 
    public static function getUser() 
    {
        if (isset($_SESSION['user_id'])) {
            return User::findByID($_SESSION['user_id']);
        }
    }
}
