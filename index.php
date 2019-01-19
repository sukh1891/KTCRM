<?php
    if(isset($_GET['i'])) {
        $id = $_GET['i'];
        header("Location:https://khuranatech.in/pro/crm/app/page.php?id=$id");
    } else {
        header("Location:https://khuranatech.in/pro/crm/ktcrm.php");
    }
?>