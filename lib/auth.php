<?php
require_once(PATH . DS . 'models' . DS . 'users.php');
//require_once(PATH . DS . 'models' . DS . 'groups.php');
/**
 * Auth Check function
 *
 * Check to see if the current user is logged in, if not redirect to the login
 * action in the users controller
 */
function auth_check() {
    if(empty($_SESSION['user']) &&
       CURRENT_CONTROLLER != 'users' &&
       CURRENT_ACTION != 'login') {
        $_SESSION['auth_redirect'] = CURRENT_CONTROLLER . '/' . CURRENT_ACTION;
        redirect(html_url('users/login'));
    }
    
    /*if(!empty($_SESSION['user'])) {
        $actionMap = array(
            'logout' => 'Read',
            'dashboard' => 'Read',
            'index' => 'Read',
            'add' => 'Write',
            'edit' => 'Edit',
            'delete' => 'Full'
        );

        if(!auth_access(CURRENT_CONTROLLER, $actionMap[CURRENT_ACTION])) {
            $_SESSION['flash'] = 'Sorry you don\'t have access to that area';
            //redirect($_SERVER['HTTP_REFERER']);
        }
    }*/
}
/**
 * Auth login action
 *
 * Takes a username and password from post data and then sets up the user
 * session
 */
function auth_login() {
    global $config;
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        $_POST['password'] = hash('sha256', $_POST['password'] . $config['security_salt']);

        $user = user_find("`username` = '{$_POST['username']}' AND `password` = '{$_POST['password']}'");
        
        if(!empty($user[0])) {
            $_SESSION['user'] = $user[0];
            //$_SESSION['group'] = group_read($user['group_id']);

            if(!empty($_SESSION['auth_redirect'])) {
                redirect(html_url($_SESSION['auth_redirect']));
            } else {
                redirect(html_url('admin/posts/index'));
            }
        } else {
            $_SESSION['flash'] = 'Sorry your details appear to be wrong';
        }
    }
}
/**
 * Auth logout function
 *
 * Delete user/group session vars and redirect user
 */
function auth_logout() {
    unset($_SESSION['user']);
    //unset($_SESSION['group']);

    redirect(html_url('users/login'));
}
/**
 * Auth access function
 *
 * Check that the current logged in user has the required access
 *
 * @param string $area controller name being checked
 * @param string $access access level to check
 * @return boolean
 *//*
function auth_access($area, $access) {
    $levels = array_flip(group_enum('access'));
    return (!empty($_SESSION['group'][$area]) && $_SESSION['group'][$area] >= $levels[$access]);
}*/