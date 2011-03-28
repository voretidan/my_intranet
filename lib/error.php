<?php
/**
 * Error message function
 *
 * Trigger a php error and optionally die on fatal error
 *
 * @param string $message
 * @param bool $fatal
 */
function error($message, $fatal = true) {
    //echo $message;

    if($fatal) {
        $view_dir = PATH . DS . 'views' . DS;
        $title_for_layout = 'Errors';
        $content_for_layout = '<div class="error-message">' . $message . '</div>';
        require_once($view_dir . 'layouts' . DS . 'default.php');
        die();
    } else {
        echo $message;
    }
}
/**
 * Debug function
 *
 * Print the value of a variable on the screen in a meaninful format for
 * debugging purposes
 * 
 * @param mixed $variable
 */
function debug($variable) {
    echo '<pre>';
    print_r($variable);
    echo '</pre>';
}