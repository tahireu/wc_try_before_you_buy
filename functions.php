<?php
/**
 * This file contains some global helping functions used by all classes
 */


/*
 * Prepare inputs for safe database injection
 * */
function tbyb_prepare($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


/*
 * Check if required fields are filled
 * */
function tbyb_check_required_fields($required_array)
{
    $error_fields = array();

    foreach ($required_array as $field_name) {

        $field_name_value = sanitize_text_field(tbyb_prepare($_POST[$field_name]));

        if (!isset($field_name_value) || (empty($field_name_value))) {
            $error_fields[] = $field_name;
        }
    }

    return $error_fields;
}


/*
 * Make sure content in fields is not too long
 * */
function tbyb_check_field_length($fields_max_lengths)
{
    $error_fields = array();
    foreach ($fields_max_lengths as $field_name => $max_length) {
        if (strlen(sanitize_text_field(tbyb_prepare($_POST[$field_name]))) > $max_length) {
            $error_fields[] = $field_name;
        }
    }
    return $error_fields;
}


/*
 * Get desired part of the string function
 * */
function tbyb_get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}