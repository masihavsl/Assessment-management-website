<?php
include "header1.php";
?>
<h1>Registration</h1>
<form action="" method="post">
    <input type="text" placeholder="Username" name="uname">
    <br>
    <input type="email" placeholder="Email" name="email">
    <br>
    <input type="password" placeholder="Password" name="pass">
    <br>

    <input type="submit">
</form>
<?php
//get the info when the parameters are set
//do the validation for the info
//check if the username already exists
//check if the email already exists
if (isset($_POST['uname']) && ($_POST['uname'] == '' || $_POST['email'] == '' || $_POST['pass'] == '')){
    echo "All fields must be filled.";
}else if (isset($_POST['uname'])){
    //data validation
    if (strlen($_POST['uname']) > 8 || strlen($_POST['uname']) < 4){
        echo "Username must be between 4 to 8 characters long";
        echo "<br>";
    }else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        echo "Email is not valid";
        echo "<br>";

    }else if (strlen($_POST['pass']) <= 8){
        echo "Password must be longer than 8 characters.";
    }else{
        global $db_con;
        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        //check the database to see if there is any account with the same username or email
        $query = "SELECT * FROM user1 where username ='$uname';";
        $query2 = "SELECT * FROM user1 where email ='$email';";

        $prepped_q = $db_con->prepare($query);
        $prepped_q->execute();
        $prepped_q2 = $db_con->prepare($query2);
        $prepped_q2->execute();
        $sql_results1 = $prepped_q->fetchALl();
        $sql_results2 = $prepped_q2->fetchALl();
        if (!sizeof($sql_results1) == 0 || !sizeof($sql_results2) == 0){
            echo "this account already exists with the entered email or username.";
        }else{
            //enter the info into the database
            $hash = password_hash($pass,PASSWORD_DEFAULT);
            insert_user_to_db($uname, $email, $hash);
            create_assessment_table($uname);

        }

    }

}



?>


















