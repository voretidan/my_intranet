<?php
/**
 * Form input function
 *
 * Convienient function for calling other form functions
 *
 * @param string $field name of the field in the database
 * @param array $options options for the input type, see below
 */
function form_input($field, $options) {
    $defaults = array(
        'type' => 'text'
    );

    $options = array_merge($defaults, $options);

    if(!empty($options['value']) && empty($_POST[$field])) {
        $_POST[$field] = $options['value'];
    }

    $options['type'] = strtolower($options['type']);
    if(function_exists('form_' . $options['type'])) {
        return call_user_func('form_' . $options['type'], $field, $options);
    }
}
/**
 * Form hidden function
 *
 * Return valid type="hidden" remembering value between posts
 *
 * @param string $field
 * @param array $options
 */
function form_hidden($field, $options) {
    $defaults = array(
        'class' => array('input', 'hidden'),
    );

    $options = array_merge($defaults, $options);

    $field = strtolower($field);

    $output = '<input type="hidden" name="' . $field . '" id="' . form__field_id($field) . '"';

    if(!empty($_POST[$field])) {
        $output .= ' value="'. $_POST[$field] . '"';
    }

    $output .= ' />';

    return html__output($output);
}
/**
 * Form text function
 *
 * Return valid type="text" remembering value between posts
 *
 * @param string $field
 * @param array $options
 */
function form_text($field, $options) {
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'class' => array('input', 'text')
    );

    $options = array_merge($defaults, $options);

    $field = strtolower($field);

    $output = '<input type="text" name="' . $field . '" id="' . form__field_id($field) . '"';

    if(!empty($_POST[$field])) {
        $output .= ' value="'. $_POST[$field] . '"';
    }

    $output .= ' />';

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }

    return html__output($output);
}
/**
 * Form password function
 *
 * Return valid type="password" clearing value between posts
 *
 * @param string $field
 * @param array $options
 */
function form_password($field, $options) {
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'class' => array('input', 'password')
    );

    $options = array_merge($defaults, $options);

    $field = strtolower($field);

    $output = '<input type="password" name="' . $field . '" id="' . form__field_id($field) . '"';

    $output .= ' />';

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }

    return html__output($output);
}
/**
 * Form area function
 *
 * Return valid textarea remembering value between posts
 *
 * @param string $field
 * @param array $options
 * @return string
 */
function form_area($field, $options) {
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'rows' => 5,
        'cols' => 30,
        'class' => array('input', 'textarea')
    );

    $options = array_merge($defaults, $options);

    $field = strtolower($field);

    $output = '<textarea name="' . $field . '" id="' . form__field_id($field) . '" rows="' . $options['rows'] . '" cols="' . $options['cols'] . '">';

    if(!empty($_POST[$field])) {
        $output .= $_POST[$field];
    }

    $output .= '</textarea>';

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }

    return html__output($output);
}
/**
 * Form select function
 *
 * Return valid select/dropbox remembering selected value between posts
 *
 * @param string $field
 * @param array $options
 */
function form_select($field, $options) {
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'class' => array('input', 'select'),
        'options' => array()
    );

    $options = array_merge($defaults, $options);

    $output = '<select name="' . $field . '" id="' . form__field_id($field) . '">';
    foreach($options['options'] as $value => $text) {
        $output .= '<option value="' . $value . '"';
        if(!empty($_POST[$field]) && $_POST[$field] == $value) {
            $output .= ' selected="selected"';
        }
        $output .= '>' . $text . '</option>';
    }
    $output .= '</select>';

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }
    
    return html__output($output);
}
/**
 * Form checkbox function
 *
 * Return valid checkbox element remembering checked state between posts
 *
 * @param string $field
 * @param array $options
 * @return string
 */
function form_checkbox($field, $options) {
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'class' => array('input', 'checkbox')
    );

    $options = array_merge($defaults, $options);

    $field = strtolower($field);

    $output = '<input type="checkbox" name="' . $field . '" id="' . form__field_id($field) . '"';

    if(!empty($_POST[$field])) {
        $output .= ' value="'. $_POST[$field] . '"';
    }

    $output .= ' />';

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }

    return html__output($output);
}
/**
 * Form checkbox group function
 *
 * Return a group of checkboxes relating to a field
 *
 * @param string $field
 * @param array $options
 */
function form_checkbox_group($field, $options) {

}
/**
 * Form radio function
 *
 * Return a valid radio input remembering selected state between posts
 *
 * @param string $field
 * @param array $options
 * @return string
 */
function form_radio($field, $options) {
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'class' => array('input', 'radio')
    );

    $options = array_merge($defaults, $options);

    $field = strtolower($field);

    $output = '<input type="radio" name="' . $field . '" id="' . form__field_id($field) . '"';
    
    if(!empty($_POST[$field])) {
        $output .= ' value="'. $_POST[$field] . '"';
    }

    $output .= ' />';

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }

    return html__output($output);
}
/**
 * Form radio group function
 *
 * Return a group of radio inputs relating to a field
 *
 * @param string $field
 * @param array $options
 */
function form_radio_group($field, $options) {

}
/**
 * Form date function
 *
 * Return a valid date selection field (select boxes)
 * 
 * @param string $field
 * @param array $options
 */
function form_date($field, $options){
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'class' => array('date', 'input'),
        'minYear' => date('Y') - 10,
        'maxYear' => date('Y') + 10,
        'months' => array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ),
        'days' => array(
            1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8,
            9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15,
            16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20, 21 => 21, 22 => 22,
            23 => 23, 24 => 24, 25 => 25, 26 => 26, 27 => 27, 28 => 28, 29 => 29,
            30 => 30, 31 => 31
        ),
        'separator' => '-'
    );

    $options = array_merge($defaults, $options);

    $years = array();
    for($i = $options['minYear']; $i <= $options['maxYear']; $i++) {
        $years[$i] = $i;
    }

    if(empty($_POST[$field . '[day]'])) {
        $today = date('j');
        if(!empty($options['days'][$today])) {
            $_POST[$field . '[day]'] = $today;
        }
    }

    $output = form_select(
        $field . '[day]',
        array(
            'wrap' => false,
            'label' => false,
            'options' => $options['days']
        )
    );

    $output .= $options['separator'];

    if(empty($_POST[$field . '[month]'])) {
        $today = date('n');
        if(!empty($options['months'][$today])) {
            $_POST[$field . '[month]'] = $today;
        }
    }

    $output .= form_select(
        $field . '[month]',
        array(
            'wrap' => false,
            'label' => false,
            'options' => $options['months']
        )
    );

    $output .= $options['separator'];

    if(empty($_POST[$field . '[year]'])) {
        $today = date('Y');
        if(!empty($years[$today])) {
            $_POST[$field . '[year]'] = $today;
        }
    }

    $output .= form_select(
        $field . '[year]',
        array(
            'wrap' => false,
            'label' => false,
            'options' => $years
        )
    );

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }

    return html__output($output);
}
/**
 * Form time function
 *
 * Return a valid time selection field (select boxes)
 * 
 * @param string $field
 * @param array $options
 */
function form_time($field, $options){
    $defaults = array(
        'wrap' => true,
        'label' => true,
        'class' => array('date', 'input'),
        'interval' => 1,
        'format' => 24,
        'separator' => ':'
    );

    $options = array_merge($defaults, $options);
    
    $hours = array();
    for($i = 0; $i < $options['format']; $i++) {
        if($options['format'] == 12 && $i == 0) {
            $hour = 12;
        } else {
            $hour = $i;
        }
        $hours[$hour] = $hour;
    }

    $minutes = array();
    for($i = 0; $i < 60; $i+=$options['interval']) {
        $minute = str_pad($i, 2, '0', STR_PAD_LEFT);
        $minutes[$minute] = $minute;
    }
    
    if(empty($_POST[$field . '[hours]'])) {
        $_POST[$field . '[hours]'] = ($options['format'] == 12) ? date('g') : date('G');
    }

    $output = form_select(
        $field . '[hours]',
        array(
            'wrap' => false,
            'label' => false,
            'options' => $hours
        )
    );

    $output .= $options['separator'];

    if(empty($_POST[$field . '[minutes]'])) {
        $current = date('i');
        while(($current % 5) !== 0) {
            $current++;
        }
        
        $_POST[$field . '[minutes]'] = $current;
    }

    $output .= form_select(
        $field . '[minutes]',
        array(
            'wrap' => false,
            'label' => false,
            'options' => $minutes
        )
    );

    if($options['format'] == 12) {
        if(empty($_POST[$field . '[meridiem]'])) {
            $_POST[$field . '[meridiem]'] = date('A');
        }
        
        $output .= ' ';
        $output .= form_select(
            $field . '[meridiem]',
            array(
                'wrap' => false,
                'label' => false,
                'options' => array('AM' => 'AM', 'PM' => 'PM')
            )
        );
    }

    if($options['wrap']) {
        $options['field'] = $field;
        $output = form__wrap($output, $options);
    }

    $error = form_error_message($field);
    if(!empty($error)) {
        $output .= $error;
    }

    return html__output($output);
}
/**
 * Form create function
 *
 * Return a valid form opening tag
 *
 * @param string $model
 * @param array $options
 */
function form_create($model, $options = array()) {
    $defaults = array(
        'type' => false,
        'method' => 'post',
        'action' => BASE . $_GET['url']
    );

    $options = array_merge($defaults, $options);

    $output = '<form id="' . ucfirst(strtolower($model)) . 'Form"';
    if($options['type'] == 'file') {
        $output .= ' enctype="multipart/form-data"';
    }

    $output .= ' method="' . $options['method'] . '"';
    $output .= ' action="' . $options['action'] . '">';

    return html__output($output);
}
/**
 * Form end function
 *
 * Return valid form closing tag, if text is specified include submit button
 * with a value of $text
 *
 * @param string $text
 */
function form_end($text = '') {
    $output = '';
    if(!empty($text)) {
        $output .= form_submit($text);
    }

    $output .= '</form>';

    return html__output($output);
}
/**
 * Form submit function
 *
 * Return valid form submit input with a value of $text
 *
 * @param string $text
 * @param array $options
 */
function form_submit($text = 'Submit', $options = array()) {
    $defaults = array(
        'wrap' => true,
        'class' => 'submit'
    );

    $options = array_merge($defaults, $options);

    $string =  '<input type="submit" value="' . $text . '" />';

    if($options['wrap']) {
        $string = form__wrap(
            $string,
            array(
                'label' => false
            )
        );
    }

    return html__output($string);
}
/**
 * Form wrap function
 *
 * Return string wrapped in a div
 * @param string $string
 * @param array $options $options['field'] is required if you want a label
 */
function form__wrap($string, $options = array()) {
    $default = array(
        'label' => true
    );

    $options = array_merge($default, $options);
    
    $output = '<div class="';

    if(!empty($options['class'])) {
        if(is_array($options['class'])) {
            $options['class'] = join(' ', $options['class']);
            $output .= $options['class'];
        }
    }

    $output .= '">';

    if($options['label'] !== false) {
        if(is_string($options['label'])) {
         $output .= form__label($options['field'], $options['label']);
        } else {
            $output .= form__label($options['field']);
        }
    }
    
    $output .= $string;

    $output .= '</div>';
    
    return $output;
}
/**
 * Form label function
 *
 * Return valid label for field automatically uses fieldname as text if text
 * isn't specified
 * 
 * @param string $field
 * @param string $text
 * @return string
 */
function form__label($field, $text = '') {
    if(empty($text)) {
        $text = ucfirst(str_replace('_', ' ', $field));
    }

    return '<label for="' . form__field_id($field) . '">' . $text . '</label>';
}
/**
 * Form field id function
 *
 * Return an id for the specified field
 *
 * @param string $field
 * @return string
 */
function form__field_id($field) {
    return 'Input' . ucfirst(strtolower(str_replace('_', ' ', str_replace('[', '', str_replace(']', '', $field)))));
}
/**
 * Form error message function
 *
 * Return an error message under the field if there was a validation error
 *
 * @param string $field
 * @return string
 */
function form_error_message($field) {
    if(!empty($_SESSION['validation_errors'][$field])) {
        $message = '<div class="error_message">' . $_SESSION['validation_errors'][$field] . '</div>';
        unset($_SESSION['validation_errors'][$field]);
        return html__output($message);
    }
}
