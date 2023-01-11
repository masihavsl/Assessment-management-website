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
<?php include "view/header.php";
?>
<hr>
<h1>List of Completed Assessments</h1>
<form action='' method="get" name="page=completed">
<?php
global $username;
echo "<br>";
print_assessments('Completed', $username);
?>
<input type="submit" value="Update">
</form>

</body>
</html>