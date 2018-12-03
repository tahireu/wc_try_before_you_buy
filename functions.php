<?php
/**
 * This file contains some global helping functions used by all classes
 */


/*
 * Prepare inputs for safe database injection
 * */
if ( !function_exists( 'tbyb_prepare' ) ) {
    function tbyb_prepare($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}


/*
 * Check if required fields are filled
 * */
if ( !function_exists( 'tbyb_check_required_fields' ) ) {
    function tbyb_check_required_fields($required_array)
    {
        $error_fields = array();
        foreach ($required_array as $fieldname) {
            if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]))) {
                $error_fields[] = $fieldname;
            }
        }
        return $error_fields;
    }
}


/*
 * Make sure content in fields is not too long
 * */
if ( !function_exists( 'tbyb_check_field_length' ) ) {
    function tbyb_check_field_length($fieldsMaxLengths)
    {
        $error_fields = array();
        foreach ($fieldsMaxLengths as $fieldname => $maxlength) {
            if (strlen(tbyb_prepare($_POST[$fieldname])) > $maxlength) {
                $error_fields[] = $fieldname;
            }
        }
        return $error_fields;
    }
}



/*
 * Get desired part of the string function
 * */
if ( !function_exists( 'tbyb_get_string_between' ) ) {
    function tbyb_get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}