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
        $runrows = mysql_fetch_assoc(mysql_query("SELECT * FROM kcoc_members where email = '$email' or mobile = '$email'"));
        $passwordc = $runrows['password'];
        $salt = $runrows['salt'];
        $id = $runrows['id'];
        $verified = $runrows['verified'];
        $password = crypt($password, $salt);
        $password = hash('sha512', $password);
        $data = array();
        if($password == $passwordc && $verified == "1" && $mnsms > 0) {
            $q = mysql_query("SELECT kcoc_sales.id, kcoc_customers.name, kcoc_customers.company, kcoc_sales.date, kcoc_sales.amount FROM kcoc_sales INNER JOIN kcoc_customers ON kcoc_sales.custid = kcoc_customers.id WHERE memid = '$id' ORDER BY id DESC LIMIT 25");
            while($row = mysql_fetch_object($q)) {
                $data[] = $row;
            }
            echo json_encode($data);
        } else {
            echo "failed";
        }
    }
?>