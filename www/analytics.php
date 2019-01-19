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
            $monthname = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $year1 = date('Y');
            $month1 = date('m') + 0;
            $my1 = $monthname[$month1] . " " . $year1;
            $construct = "SELECT SUM(amount) AS valuesum FROM k2mb_xaction WHERE userid = '$id' AND type = 'expenses' AND MONTH(date) = $month1 AND YEAR(date) = $year1";
            $run = mysql_query($construct);
            $runrows = mysql_fetch_assoc($run);
            $expensem1 = $runrows['valuesum'];
            if($expensem1 == "") {
                $expensem1 = 0;
            }
            $construct = "SELECT SUM(amount) AS valuesum FROM k2mb_xaction WHERE userid = '$id' AND type = 'income' AND MONTH(date) = $month1 AND YEAR(date) = $year1";
            $run = mysql_query($construct);
            $runrows = mysql_fetch_assoc($run);
            $incomem1 = $runrows['valuesum'];
            if($incomem1 == "") {
                $incomem1 = 0;
            }
            $savingm1 = $incomem1 - $expensem1;
            $month2 = $month1 - 1;
            $year2 = $year1;
            if($month2 < 1) {
                $month2 = 12;
                $year2 = $year1 - 1;
            }
            $my2 = $monthname[$month2] . " " . $year2;
            $construct = "SELECT SUM(amount) AS valuesum FROM k2mb_xaction WHERE userid = '$id' AND type = 'expenses' AND MONTH(date) = $month2 AND YEAR(date) = $year2";
            $run = mysql_query($construct);
            $runrows = mysql_fetch_assoc($run);
            $expensem2 = $runrows['valuesum'];
            if($expensem2 == "") {
                $expensem2 = 0;
            }
            $construct = "SELECT SUM(amount) AS valuesum FROM k2mb_xaction WHERE userid = '$id' AND type = 'income' AND MONTH(date) = $month2 AND YEAR(date) = $year2";
            $run = mysql_query($construct);
            $runrows = mysql_fetch_assoc($run);
            $incomem2 = $runrows['valuesum'];
            if($incomem2 == "") {
                $incomem2 = 0;
            }
            $savingm2 = $incomem2 - $expensem2;
            $month3 = $month2 - 1;
            $year3 = $year2;
            if($month3 < 1) {
                $month3 = 12;
                $year3 = $year2 - 1;
            }
            $my3 = $monthname[$month3] . " " . $year3;
            $construct = "SELECT SUM(amount) AS valuesum FROM k2mb_xaction WHERE userid = '$id' AND type = 'expenses' AND MONTH(date) = $month3 AND YEAR(date) = $year3";
            $run = mysql_query($construct);
            $runrows = mysql_fetch_assoc($run);
            $expensem3 = $runrows['valuesum'];
            if($expensem3 == "") {
                $expensem3 = 0;
            }
            $construct = "SELECT SUM(amount) AS valuesum FROM k2mb_xaction WHERE userid = '$id' AND type = 'income' AND MONTH(date) = $month3 AND YEAR(date) = $year3";
            $run = mysql_query($construct);
            $runrows = mysql_fetch_assoc($run);
            $incomem3 = $runrows['valuesum'];
            if($incomem3 == "") {
                $incomem3 = 0;
            }
            $savingm3 = $incomem3 - $expensem3;
            $json = array();
            $json[]= array(
                'my1' => $my1,
                'exp1' => $expensem1,
                'inc1' => $incomem1,
                'sav1' => $savingm1,
                'my2' => $my2,
                'exp2' => $expensem2,
                'inc2' => $incomem2,
                'sav2' => $savingm2,
                'my3' => $my3,
                'exp3' => $expensem3,
                'inc3' => $incomem3,
                'sav3' => $savingm3,
            );
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            echo "failed";
        }
    }
?>