<?php

$username = $_COOKIE["username"];


//get the uname from the cookie
//while loading up the files
//check if a folder with the user's uname exists

//create a function where returns folder name

function get_folder_name(){
    global $username;
    $folderName = "uploads/".$username."Uploads";
    if(!is_dir($folderName)){
        mkdir($folderName);
    }
    return $folderName;
}



function get_username_row($uname){
    global $db_con;
    $query = "SELECT * FROM user1 where username ='$uname';";
    $prepped_q = $db_con->prepare($query);
    $prepped_q->execute();
    $sql_result = $prepped_q->fetch();
    return $sql_result;
};

function get_assessment_rows($uname, $status){
    global $db_con;
    $query = "SELECT * FROM $uname Where status= '$status';";
    $prepped_q = $db_con->prepare($query);
    $prepped_q->execute();
    $sql_results = $prepped_q->fetchALl();
    return $sql_results;
}

//create a function which reads the uploaded file and inserts all the data into the db
//id	course_code	type	due_date	due_time	status
function insert_data_to_db($filePath, $uname){
    global $db_con;

    //delete all the existing rows
    $query = "DELETE FROM $uname;";
    $prepped_query = $db_con-> prepare($query);
    $prepped_query->execute();
    $lines = file($filePath);
    foreach ($lines as $line){
        $row_values = explode(",", $line);
        $row_values[5] = trim($row_values[5]);
        $query = "INSERT INTO $uname (id, course_code, assessment_type, due_date, due_time, status)
                    VALUES ($row_values[0],'$row_values[1]','$row_values[2]','$row_values[3]','$row_values[4]','$row_values[5]');";
        $prepped_query = $db_con-> prepare($query);
        $prepped_query->execute();


    }
}


//insert new user to the database
function insert_user_to_db($uname, $em, $pass){
    global $db_con;
    $query = "INSERT INTO user1 (username, email, password)
                    VALUES ('$uname', '$em', '$pass');";
    $prepped_query = $db_con-> prepare($query);
    $success = $prepped_query->execute();
    if ($success){
        echo "You successfully created an account.";


    }else{
        echo "Account creation was unsuccessfull.";
    }
}

function create_assessment_table($uname){
    global $db_con;
    $query = "CREATE TABLE IF NOT EXISTS test.$uname(
    id int(10),
    course_code varchar(30),
    assessment_type varchar(60),
    due_date varchar(30),
    due_time varchar(30),
    status varchar(30)
);";
    $prepped_query = $db_con-> prepare($query);
    return $prepped_query->execute();
}


function update_db_content($option, $username){
    global $db_con;
    if ($option == "current" || $option == "completed"){
        //get tall the rows with the passed option
        $query = "SELECT * FROM $username Where status = '$option';";
        $prepped_q = $db_con->prepare($query);
        $prepped_q->execute();
        $sql_results = $prepped_q->fetchALl();
        unlink('pageName.txt');
        $fileW = fopen('pageName.txt', "w");
        $status = $option == 'current' ? 'completed':'current';
        fwrite($fileW,$option);
        fclose($fileW);
        if (isset($_GET['selected'])){
            $arr = $_GET['selected'];
            for($i = 0; $i < count($sql_results); $i++){
                foreach ($arr as $a => $d){
                    if ($sql_results[$i][0] == $d){
                        $query = "UPDATE $username
                            SET status = '$status'
                            WHERE id= '$d';";
                        $prepped_q = $db_con->prepare($query);
                        $prepped_q;
                        $prepped_q->execute();
                    }
                }

            }
        }
    }
}