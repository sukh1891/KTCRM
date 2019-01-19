<?php
    header("Access-Control-Allow-Origin: *");
    if(isset($_POST['login'])) {
        require('../includes/connect.php');
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
        $runrows = mysql_fetch_assoc(mysql_query("SELECT * FROM k2mb_members where email = '$email' or mobile = '$email'"));
        $passwordc = $runrows['password'];
        $salt = $runrows['salt'];
        $id = $runrows['id'];
        $verified = $runrows['verified'];
        $password = crypt($password, $salt);
        $password = hash('sha512', $password);
        $data = array();
        if($password == $passwordc && $verified == "1") {
            $construct = "SELECT * FROM k2mb_refer WHERE userid = '$id'";
            $run = mysql_query($construct);
            if(mysql_num_rows($run) == 0) {
                function refcode() {
                    $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $key = "";
                    for($i=0; $i<6; $i++) {
                        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))]; 
                    }
                    $construct = "SELECT * FROM k2mb_refer WHERE code = '$key'";
                    $run = mysql_query($construct);
                    if(mysql_num_rows($run) == 0) {
                        return $key;
                    } else {
                        refcode();
                    }
                }
                $code = refcode();
                $construct = "INSERT INTO k2mb_refer (userid, code) VALUES ('$id', '$code')";
                $run = mysql_query($construct);
                $construct = "SELECT * FROM k2mb_refer WHERE userid = '$id'";
                $run = mysql_query($construct);
            }
            $runrows = mysql_fetch_assoc($run);
            $code = $runrows['code'];
            echo $code;
        } else {
            echo "failed";
        }
    }
?>