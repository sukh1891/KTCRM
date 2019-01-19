<?php
    header("Access-Control-Allow-Origin: *");
    if(isset($_POST['login'])) {
        require('../app/includes/connect.php');
        $email = mysql_real_escape_string(htmlspecialchars(trim($_POST['email'])));
        $password = mysql_real_escape_string(htmlspecialchars(trim($_POST['password'])));
        $newchar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
        $characters = 'BWEu3JOxDAFYMH1mzew!V^$7GrRjCqnQ@lig&KoI465skd0Lah9fT2btPcX%U8#vp*SNZy';
        $l = strlen($password);
        $i;
        $a = "";
        for($i=0; $i<$l; $i++) {
            $n = strpos($characters, substr($password, $i, 1));
            $a .= substr($newchar, $n, 1);
        }
        $password = $a;
        $runrows = mysql_fetch_assoc(mysql_query("SELECT * FROM kcoc_members where email = '$email' or mobile = '$email'"));
        $passwordc = $runrows['password'];
        $salt = $runrows['salt'];
        $mnsms = $runrows['sms'];
        $verified = $runrows['verified'];
        $password = crypt($password, $salt);
        $password = hash('sha512', $password);
        if($password == $passwordc && $verified == "1") {
            $json = array();
            $json[]= array(
                'name' => $name,
                'login' => 'success',
            );
            $jsonstring = json_encode($json);
            if($mnsms > 0) {
                echo $jsonstring;
            } else {
                echo "sms zero";
            }
        } else {
            echo "failed";
        }
    }
?>