<?php include("initialize.php");?>
<?php

if (isset($_POST['reister'])){

    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $confrirm_password=$_POST['confirm_password'];
   
    if(empty($firstname)){
        $erro_message="firstname is required!";
    }elseif(empty($lastname)){
     $erro_message= "lastname is required!";
    }elseif(empty ($password)) {
        $erro_message= "password is required!";
    }elseif(empty ($username)){
        $erro_message= "Username is required!";
    }elseif(empty ($confrirm_password)) {
        $erro_message="cinfirm password is required!";
    }elseif($confrirm_password== $password) {
        $erro_message="Password and confirm password not match!";
    }else{
        $error_message= null;
    }
    if(empty($erro_message)){
        $_SESSION['alert_message']=$error_message;
        header('location:register.php');
    }else{

        $sql="INSERT INTO user(
        firstname ,lastname,username,password
        ) VALUES('".$firstname."','".$lastname"','".$username"','".$password"')";

        if($connection->query($sql)===TRUE) {
            $_SESSION["alert_message"]="Account successfully created.You can now <a href='login.php'>login</a>";
       $_SESSION['user_firstname']= $firstname;
       header ('location:register.php');
        }else{
            die($connection->error)
        }
    }

