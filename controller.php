<?php
session_start();
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require("./database/database_pdo.php");
include "lib/methods.php";
include "model.php";
global $db_con;
if(isset($_POST['username']) && isset($_POST['passw'])){
    if ($_POST['username'] == '' || $_POST['passw'] == '') {
//        include "view/header1.php";
        echo "All fields must be filled.";
        echo "<br>";
    }else{

        $uname = $_POST['username'];
        $sql_result = get_username_row($uname);
        if ($sql_result == null){
//            include "view/header1.php";
//            echo "<br>";
            echo "Password or username is wrong";
            echo "<br>";
        }else{
            if (password_verify($_POST['passw'],$sql_result['password'])){
                $_GET['page' ] = "current";
                //set a cookie for the uname
                setcookie("username", $uname, time() + (86400 * 30), "/");
                $username = $uname;
            }else{
//                include "view/header1.php";
//                echo "<br>";
                echo "Password or username is wrong";
                echo "<br>";
            }
        }
    }
}


if (!isset($_GET['page'])) {
    $option = fgets(fopen('pageName.txt', 'r'));
    if ($option == "login" || $option == "registration"){
        unset($_COOKIE["username"]);
        unlink("fileName.txt");
        $fileW = fopen('fileName.txt', "w");
        fclose($fileW);
        $_GET['page'] = "login";
    }
} else {
    if ($_GET['page'] == "logout"){
        unset($_COOKIE["username"]);
        unlink("fileName.txt");
        $fileW = fopen('fileName.txt', "w");
        fclose($fileW);
        $_GET['page'] = "login";
    }
    $option = $_GET['page'];
    unlink('pageName.txt');
    $fileW = fopen('pageName.txt', "w");
    fwrite($fileW, $option);
    fclose($fileW);
}


if (isset($_COOKIE["username"])){
//    $username = $_COOKIE["username"];
    $currentFileName = fgets(fopen('fileName.txt', 'r'));
    get_folder_name();
    if ($currentFileName != null){
        $fileR = fopen("uploads/".$username."Uploads/".$currentFileName, 'r');
    }
//    echo "its set ";
//    echo $username;
}else{
//    echo "naha";
}





    if (isset($_GET['file'])) {
        $newFileName = $_GET["file"];
        //get the new file and insert data into the db
        insert_data_to_db("./uploads/".$username."Uploads/$newFileName", $username);
        unlink('fileName.txt');
        $fileW = fopen('fileName.txt', "w");
        fwrite($fileW, $newFileName);
        fclose($fileW);
    }

update_db_content($option, $username);
    switch ($option) {
        case 'registration':
            include "view/registration.php";
            break;
        case 'login':
            include "view/login.php";
            break;
        case 'current':
            include "view/current.php";
            break;
        case 'completed':
//            include "model.php";
            include "view/completed.php";
            break;
        case 'upload':
//        include "model.php";
            include "view/update.php";
            break;
    }
