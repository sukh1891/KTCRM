<?php
    header("Access-Control-Allow-Origin: *");
    if(isset($_POST['login'])) {
        require('../includes/connect.php');
        $email = mysql_real_escape_string(htmlspecialchars(trim($_POST['email'])));
        $password = mysql_real_escape_string(htmlspecialchars(trim($_POST['password'])));
        $date = mysql_real_escape_string(htmlspecialchars(trim($_POST['date'])));
        $amount = mysql_real_escape_string(htmlspecialchars(trim($_POST['amount'])));
        $remarks = mysql_real_escape_string(htmlspecialchars(trim($_POST['remarks'])));
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
        $runrows = mysql_fetch_assoc(mysql_query("SELECT * FROM k2mb_members where email = '$email' or mobile = '$email'"));
        $passwordc = $runrows['password'];
        $salt = $runrows['salt'];
        $id = $runrows['id'];
        $verified = $runrows['verified'];
        $password = crypt($password, $salt);
        $password = hash('sha512', $password);
        $data = array();
        if($password == $passwordc && $verified == "1") {
            $q = mysql_query("INSERT INTO k2mb_xaction (userid, date, amount, type, remarks) VALUES ('$id', '$date', '$amount', 'income', '$remarks')");
            if($q) {
                echo "success";
            }
        } else {
            echo "failed";
        }
    }
?>