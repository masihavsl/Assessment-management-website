<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include "view/header.php";

?>
<h1>Upload Assessment File</h1>
<hr>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" value="Upload">
</form>
<hr>
<h3>Files Previously Uploaded (Now in uploads folder)</h3>
<form action="" method="get">
    <?php
    $folderName = get_folder_name();
    //if not create a folder with the uname
    //then run the following->
    $files = scandir($folderName);
    foreach ($files as $file){
        if(explode('.', $file)[1] == 'txt'){
            echo "<a href='controller.php?file=$file'>$file</a>";
            echo "<br>";
        }
    }
    ?>


<?php
    //while the user is trying to upload a file
    //instead of uploading into "uploads" folder upload into
    //$uname folder


    if (isset($_FILES['file']['name'])){
        error_reporting(E_ALL);
        $fileName1 = $_FILES['file']['name'];
        $location = $folderName."/".$fileName1;
        echo $location;
        if(move_uploaded_file($_FILES['file']['tmp_name'], $location)){
            if(!validate_file($location)){
                echo "The uploaded file is invalid.";
                unlink($location);
            }else{
                echo "File uploaded successfully.";
                insert_data_to_db($location, $username);

            }
        }else{
            echo "Error uploading file.";
        }
    }
?>


</body>
</html>