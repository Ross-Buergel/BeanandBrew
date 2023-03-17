<?php
//checks length of a variable 
function check_length($variable_name, $variable, $min_length, $max_length) {
    //returns message if too short
    if ($variable < $min_length){
        return $variable_name." cannot be less than ".$min_length." characters";
    }
    //returns message if too long
    else if ($variable < $max_length){
        return $variable_name." cannot be more than ".$max_length." characters";
    }
}

//checks a variable has been entered
function check_presence($variable_name, $variable){
    //returns message if empty
    if (empty($variable)){
        return $variable_name." is requred";
    }
}

//checks whether a variable contains an integer
function check_contains_integer($variable_name, $variable)
{
    //splits variable into characters
    $characters = str_split($variable);
    
    //defines necessary variable
    $contains_digit = False;

    //loops through each character and sets contains_digit to True if character is an integer
    foreach ($characters as $character) {
        if (is_int($character)) {
            $contains_digit = True;
        }
    }

    //checks if variable contained an integer and if so returns appropriate message
    if (isset($contains_digit) && $contains_digit == True)
    {
        return $variable_name . " cannot contain an integer";
    }
}
?>