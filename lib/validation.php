<?php
/**
 * Validation notEmpty rule
 *
 * Return true if the field has been completed
 *
 * @param string $field
 * @param array $data
 * @param string $table
 * @return boolean
 */
function validation_notEmpty($field, $data, $table) {
    return !empty($data[$field]);
}
/**
 * Validation unique rule
 *
 * Return true if the value is unique
 * 
 * @param string $field
 * @param array $data
 * @param string $table
 * @return boolean
 */
function validation_unique($field, $data, $table) {
    $conditions = "`{$field}` = '{$data[$field]}'";
    if(!empty($data['id'])) {
        $conditions .= " AND ";
        $conditions .= "`id` != " . $data['id'];
    }

    $result = database_find($conditions, $table);
    return empty($result);
}
/**
 * Validation email rule
 *
 * Return true if it is a valid email address
 *
 * @param string $field
 * @param array $data
 * @param string $table
 * @return boolean
 */
function validation_email($field, $data, $table) {
    $pattern = "/^([_a-zA-Z0-9-']+(\.[_a-zA-Z0-9-']+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4}))/si"; 
    return preg_match($pattern, $data[$field]);
}