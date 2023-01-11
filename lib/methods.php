<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

//go in the database and get all the rows that have status as the set param
//loop over them and print them to the screen
function print_assessments($status, $uname){
//    global $db_con;
//    $query = "SELECT * FROM $uname Where status= '$status';";
//    $prepped_q = $db_con->prepare($query);
//    $prepped_q->execute();
//    $sql_results = $prepped_q->fetchALl();
     $sql_results = get_assessment_rows($uname, $status);
    foreach ($sql_results as $sql_result){
        echo "$sql_result[0] $sql_result[1] $sql_result[2] $sql_result[3] $sql_result[4]";
        echo "<input type='checkbox' name='selected[]' value='$sql_result[0]'>";
        echo "<br>";
    }
}

//checks if the file is cvc, and there is not sql injection attack
function validate_file($fileName){
    $lines = file($fileName);
    foreach ($lines as $line){
        if( strpos($line, ",") == false ) {
            return false;
        }else if (strpos($line, "<") || strpos($line, ">") || strpos($line, "?") || strpos($line, "*")) {
            return false;
        }else{
            $columns = explode(',', $line);
            if (count($columns) != 6){
                echo 1;
                return false;
            };
            if (!is_numeric($columns[0])){
                echo 2;
                return false;
            };
            if ((strlen($columns[1]) != 8)) {
                echo 3;
                return false;
            };
            if (is_numeric($columns[2])) {
                echo 4;
                return false;
            };
            $columns[5] = trim($columns[5]);
            if (!(($columns[5] == 'Current') || ($columns[5] == 'Completed'))) {
                echo 5;
                return false;
            };

        }
    }
    return true;
}




























