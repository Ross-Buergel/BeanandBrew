<?php
function check_length($variable_name, $variable, $min_length, $max_length) {
    if ($variable < $min_length){
        return $variable_name." cannot be less than ".$min_length." characters";
    }
    else if ($variable < $max_length){
        return $variable_name." cannot be more than ".$max_length." characters";
    }
}

function check_presence($variable_name, $variable){
    if (empty($variable)){
        return $variable_name." is requred";
    }
}

function check_contains_integer($variable_name, $variable)
{
    //splits variable into characters
    $characters = str_split($variable);

    $contains_digit = False;

    foreach ($characters as $character) {
        if (is_int($character)) {
            $contains_digit = True;
        }
    }

    if (isset($contains_digit) && $contains_digit == True)
    {
        return $variable_name . " cannot contain an integer";
    }
}
?>