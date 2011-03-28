<?php
/**
 * Database connect function
 *
 * Connect to and select database, error out gracefully if there is a problem
 */
function database_connect() {
    $connection = @mysql_connect(DATABASE_SERVER, DATABASE_USER_NAME, DATABASE_PASSWORD);
    if(!$connection) {
        error('Unable to connect to database');
    }

    $database = @mysql_select_db(DATABASE_DATABASE);
    if(!$database) {
        error('Unable to select database');
    }
}
/**
 * Database validate function
 * 
 * Pass an array of data to validate
 * 
 * @param array $data to validate
 * @param string $table being validated
 */
function database_validate($data, $table) {
    $validation_rules = call_user_func(substr($table, 0, -1) . '_get_validation');

    $all_valid = true;
    foreach($validation_rules as $field => $rules) {
        foreach($rules as $rule => $message) {
            $valid = call_user_func_array(
                'validation_' . $rule,
                array(
                    $field,
                    $data,
                    $table
                )
            );

            if(!$valid) {
                $all_valid = false;
                $_SESSION['validation_errors'][$field] = $message;
                break;
            }
        }
    }

    return $all_valid;
}
/**
 * Database save function
 *
 * Pass an array of data to save, if the 'id' field is present it updates
 * instead of inserts.
 *
 * Expects data in the format:
 *
 * array(
 *  'id' => 1, // only required on update, not on insert
 *  'field_name' => 'value',
 *  'field_name' => 'value'
 * );
 *
 * @param array $data array of data to save
 * @param string $table name of the table to save the data
 */
function database_save($data, $table) {
    if(!database_validate($data, $table)) {
        return false;
    }
    
    $table = mysql_real_escape_string($table);
    if(!empty($data['id'])) {
        $query = 'UPDATE ';
    } else {
        $query = 'INSERT INTO ';
    }

    $query .= '`' . $table . '` SET ' ;
    foreach($data as $field => $value) {
        $field = mysql_real_escape_string($field);
        $value = mysql_real_escape_string($value);

        $query .= "`{$field}` = '{$value}', ";
    }

    $query = substr($query, 0, -2);

    if(!empty($data['id'])) {
        $query .= ' WHERE `id` = ' . mysql_real_escape_string($data['id']);
    }

    $query .= ';';
    
    $result = @mysql_query($query);

    if(!$result) {
        error('There was an error with your query, mysql said: ' . mysql_error());
    }

    return (!$result != true);
}
/**
 * Data base read function
 * 
 * Read a record from the database by id
 * 
 * @param integer $id
 * @param string table name of the table to read data from
 */
function database_read($id, $table) {
    $id = mysql_real_escape_string($id);
    $table = mysql_real_escape_string($table);

    $query = 'SELECT * FROM ';
    $query .= '`' . $table . '` ';
    $query .= 'WHERE `id` = ' . (int)$id;

    $result = @mysql_query($query);
    if(!$result) {
        error('There was an error with your query, mysql said: ' . mysql_error());
    }

    return mysql_fetch_assoc($result);
}
/**
 * Database find function
 *
 * Return $limit records in $table that match $conditions
 *
 * Expects conditions in normal mysql format:
 *
 * `field` = 'value' AND `field` != value... etc
 *
 * This is not escaped so always escape user input before using this function
 *
 * @param array $conditions array of conditions to search for
 * @param string $table table name of the table to read data from
 * @param integer $limit the number of records to fetch, 0 or false for all
 *
 * @return array $results array of matching results
 */
function database_find($conditions, $table, $limit = 0) {
    if($conditions == 'all') {
        $conditions = '';
    }
    
    $table = mysql_real_escape_string($table);
    if(!empty($limit)) {
        $limit = mysql_real_escape_string($limit);
    }

    $query = 'SELECT * FROM ';
    $query .= '`' . $table . '` ';
    if(!empty($conditions)) {
        $query .= 'WHERE ' . $conditions;
    }

    if(!empty($limit)) {
        $query .= ' LIMIT ' . $limit;
    }

    $result = @mysql_query($query);
    if(!$result) {
        error('There was an error processing your query, mysql said: ' . mysql_error());
    }

    $output = array();
    while($row = mysql_fetch_assoc($result)) {
        $output[] = $row;
    }

    return $output;
}
/**
 * Database count function
 *
 * Return the number of rows found with $conditions
 *
 * @param array $conditions array of conditions to search for
 * @param string $table table name of the table to read data from
 * @return integer
 */
function database_count($conditions, $table) {
    if($conditions == 'all') {
        $conditions = '';
    }

    $table = mysql_real_escape_string($table);

    $query = 'SELECT count(*) as `count` FROM ';
    $query .= '`' . $table . '` ';
    if(!empty($conditions)) {
        $query .= 'WHERE ' . $conditions;
    }

    $result = @mysql_query($query);
    if(!$result) {
        error('There was an error processing your query, mysql said: ' . mysql_error());
    }

    $output = array();
    $row = mysql_fetch_assoc($result);
    return $row['count'];
}
/**
 * Database delete function
 *
 * Delete record $id from $table
 *
 * @param integer $id
 * @param string $table
 * @return boolean
 */
function database_delete($id, $table) {
    $query = 'DELETE FROM ' .
    $query .= '`' . $table . '` ';
    $query .= 'WHERE `id`=' . $id;

    $result = @mysql_query($query);
    if(!$result) {
        error('There was an error processing your query, mysql said: ' . mysql_error());
        return false;
    }

    return true;
}
/**
 * Database query function
 *
 * General sql query function
 *
 * @param string $query
 * @return array
 */
function database_query($query) {
    $result = @mysql_query($query);
    if(!$result) {
        error('There was an error processing your query, mysql said: ' . mysql_error());
    }
    
    $output = array();
    while($row = mysql_fetch_assoc($result)) {
        $output[] = $row;
    }

    return $output;
}
/**
 * Database date format function
 *
 * Take a form array and turn it into a valid database string
 *
 * @param array $date
 * @return string
 */
function database_date_format($date) {
    if(is_array($date)) {
        krsort($date);
        $date = join('-', $date);
    }
    
    return $date;
}
/**
 * Database time format function
 *
 * Take a form time array and turn it into a valid database string
 *
 * @param array $time
 * @return string
 */
function database_time_format($time) {
    if(is_array($time)) {
        if(count($data['start_time']) == 3) {
            if($time['meridiem'] == 'PM') {
                $time['hour'] += 12;
            } elseif($time['hour'] == 12) {
                $time['hour'] = 0;
            }
            
            unset($time['meridiem']);
        }
        
        ksort($time);
        $time = join(':', $time);
    }
    
    return $time;
}
