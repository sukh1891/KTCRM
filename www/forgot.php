<?php
    header("Access-Control-Allow-Origin: *");
    if(isset($_POST['login'])) {
        require('../app/includes/connect.php');
        $email = mysql_real_escape_string(htmlspecialchars(trim($_POST['formail'])));
        $mobile = mysql_real_escape_string(htmlspecialchars(trim($_POST['formob'])));
        $run = mysql_query("SELECT * FROM kcoc_members where email = '$email' AND mobile = '$mobile'");
        $numrows = mysql_num_rows($run);
        if($numrows == 0) {
            echo "failed";
        } else {
            $runrows = mysql_fetch_assoc($run);
            $salt = $runrows['salt'];
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $newpass = '';
            for ($i = 0; $i < 8; $i++) {
                $newpass .= $characters[rand(0, $charactersLength - 1)];
            }
            $password = $newpass;
            $newpass = crypt($newpass, $salt);
            $newpass = hash('sha512', $newpass);
            $construct = "UPDATE kcoc_members SET password = '$newpass' WHERE email = '$email' AND mobile = '$mobile'";
            mysql_query($construct);
            include('../app/includes/functions.php');
	    newPassword($mobile, $email, $password);
            echo "done";
        }
    }
?>