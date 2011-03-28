<?php
/**
 * Dispatch function
 *
 * Work out the current controller and action from the url, make sure it all
 * exists and then call it with the right params
 *
 * @global array $config application config array
 * @global boolean $rendered whether a view has been rendered yet or not (turn off auto-render)
 */
function dispatch() {
    global $config, $rendered;
    $controller = $config['default_controller'];
    $action = $config['default_action'];
    $params = array();

    if(!empty($_GET['url'])) {
        if($_GET['url'] == 'admin') {
            redirect('admin/posts/index');
        }
        $url = explode('/', $_GET['url']);

        for($i = 0; $i < count($url); $i++) {
            if($i == 0 && !empty($url[$i])) {
                $controller = $url[$i];
            } elseif($i == 1 && !empty($url[$i])) {
                $action = $url[$i];
            } elseif(!empty($url[$i])) {
                $params[] = $url[$i];
            }
        }
    }

    if(in_array($controller, $config['admin_routes'])) {
        $admin_route = $controller;
        $controller = $action;

        if(empty($params)) {
            $action = $config['default_action'];
        } else {
            $action = array_shift($params);
        }

        $action = $admin_route . '_' . $action;
    }

    define('CURRENT_CONTROLLER', $controller);
    define('CURRENT_ACTION', $action);

    if(!empty($admin_route)) {
        auth_check();
    }

    $controller = PATH . DS . 'controllers' . DS . $controller . '.php';
    if(!file_exists($controller)) {
        error('Invalid/missing controler, please make sure it is defiend');
    } else {
        require_once($controller);

        if(!function_exists($action)) {
            error('Invalid/missing action, please make sure it is defined');
        }

        call_user_func_array($action, $params);

        if(!$rendered && empty($admin_route)) {
            render();
        } elseif(!$rendered && !empty($admin_route)) {
            render('', array(), 'admin');
        }
    }
}
/**
 * View render function
 *
 * Render the current view on the screen using the data from $vars or $view_vars
 * that have been pre-set using the set_view_var() function
 *
 * @global array $view_vars array of variables from the set_view_var() function
 * @global boolean $rendered whether or not a view is rendered yet (turn off auto-render)
 * @param string $view name of the view to render (in the views/controller_name directory)
 * @param array $vars variables to pass to the view
 * @param string $layout use a layout other than default
 */
$rendered = false;
function render($view = '', $vars = array(), $layout = 'default') {
    global $view_vars, $script_links, $script_blocks, $rendered, $config, $purifier;
    
    if(empty($view)) {
        $view = CURRENT_ACTION;
    }

    if(empty($vars)) {
        $vars = $view_vars;
    }

    $view_dir = PATH . DS . 'views' . DS;

    $view_path = $view_dir . CURRENT_CONTROLLER . DS . $view . '.php';
    if(!file_exists($view_path)) {
        error('Invalid/missing view, please make sure it is defined');
    }

    extract($vars);

    if(empty($title_for_layout)) {
        $current_action = CURRENT_ACTION;
        foreach($config['admin_routes'] as $admin_route) {
            $current_action = str_replace($admin_route . '_', '', $current_action);
        }
        $title_for_layout = ucfirst($current_action) . ' | ' . ucfirst(CURRENT_CONTROLLER);
    }
    
    ob_start();
    require_once($view_path);
    $content_for_layout = ob_get_clean();

    require_once($view_dir . 'layouts' . DS . $layout . '.php');

    $rendered = true;
}
/**
 * Set view vars function
 *
 * Set a variable for access in the view
 *
 * @global array $view_vars the global array to store the variables in
 * @param string $name name of the variable to store
 * @param mixed $value the value to store
 */
$view_vars = array();
function set_view_var($name, $value) {
    global $view_vars;

    $view_vars[$name] = $value;
}
/**
 * Get scripts function
 *
 * Get a list of all script links and script blocks defined in views and include
 * them in the right place on the page
 *
 * Expects variables to be set in views as so:
 *
 * $script_links[] = 'jquery.min.js';
 * $script_links[] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js';
 *
 * or
 *
 * $script_blocks[] = 'function doStuff() { return 1+1 }';
 *
 * @global array $script_links
 * @global array $script_blocks
 */
function get_scripts() {
    global $script_links, $script_blocks;

    $output = '';
    if(!empty($script_links)) {
        foreach($script_links as $src) {
            $output .= html_script_link($src);
        }
    }

    if(!empty($script_blocks)) {
        foreach($script_blocks as $code) {
            $output .= html_script_block($code);
        }
    }

    return $output;
}
/**
 * Redirect function
 *
 * Redirect the user to another page
 *
 * @param string $location the page to redirect to
 * @param boolean $exit stop processing further requests
 */
function redirect($location, $exit = true) {
    header('Location: ' . $location);
    if($exit) {
        die();
    }
}