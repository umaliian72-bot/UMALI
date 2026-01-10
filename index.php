<?php include'initialize';?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login/logout in php</title>
        <style type="text/css">
            .center {margin-left:auto;margin-right:auto;}
        </style>
    </head>
    <body>
        <div align="center">
            <h4>Welcome!</h4>
            <?php

            if(isset($_SESSION ['alert_message'])){
                echo'<div align="center"> <b> <i>' .$_SESSION ['alert_mesage'].'</i> </b>
           </div> ';
      unset($_SESSION['alert_message']); 
     } 
     ?>
     <?php if (isset($_SESSION['is_login'])):?>
        <h3>Congratulations! You are now login.click <a hreh="logout.php">here</a>to logout</h3>

        <php elese:?>
            <table border ="1"with="20%">
                <tr>
                    <td align ="center"><a href="login.php">Register</a></td>
                </tr>
            </table>

            <br><br>
            <?php endif;?>
     </div>
        </php>
    </body>
</html>