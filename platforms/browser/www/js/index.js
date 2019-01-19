var email;
var password;
function onLoad() {
    document.addEventListener("deviceready", onDeviceReady, false);
}
function onDeviceReady() {
    document.addEventListener("offline", ifOffline, false);
    var db = openDatabase('ktcrm', '1.0', 'ktcrm', 1024*1024);
    db.transaction(populateDB, failed, success);
    db.transaction(successDB, failed, success);
}
function ifOffline() {
    alert("No internet. Check connection and restart the app.");
    navigator.app.exitApp();
}
function populateDB(tx) {
    tx.executeSql("CREATE TABLE IF NOT EXISTS members (id INTEGER PRIMARY KEY, email VARCHAR, password VARCHAR, login CHAR)");
}
function successDB(tx) {
    tx.executeSql("SELECT * FROM members WHERE login = 'yes'", [], querySuccess, failed);
}
function querySuccess(tx, results) {
    rowid = results.rows.item(0).id;
    email = results.rows.item(0).email;
    password = results.rows.item(0).password;
    $("#email").val(email);
    $("#password").val(password);
    var dataString="email="+email+"&password="+password+"&login=";
    var url="https://khuranatech.in/pro/crm/app/login.php";
    var data;
    if($.trim(email).length>0 & $.trim(password).length>0) {
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            dataType: "json",
            crossDomain: true,
            cache: false,
            beforeSend: function(){ $("#login").html('Connecting...');},
            success: function(data){
                if(data == "failed") {
                    alert("Login failed. Try again.");
                    $("#login").html('Login');
                } else if (data == "sms zero") {
                    alert("Your SMS balance is ZERO. Kindly recharge to access the app.");
                    $(".recharge").addClass("show");
                } else {
                    $.each(data, function(i, field){
                        login = field.login;
                        name = field.name;
                    });
                    if(login == "success") {
                        $(".name").html('Hi, ' + name);
                        $("#login").html('Login');
                        db = openDatabase('ktcrm', '1.0', 'ktcrm', 1024*1024);
                        db.transaction(insertoDB, failed, success);
                        dashboard();
                    } else {
                        alert("Login failed. Try again.");
                        $("#login").html('Login');
                        db = openDatabase('ktcrm', '1.0', 'ktcrm', 1024*1024);
                        db.transaction(deleteDB, failed, success);
                    }
                }
            }
        });
    }return false;
}
$("#login").click(function(){
    email = $("#email").val();
    password = $("#password").val();
    characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
    newchar = 'BWEu3JOxDAFYMH1mzew!V^$7GrRjCqnQ@lig&KoI465skd0Lah9fT2btPcX%U8#vp*SNZy';
    l = password.length;
    var i;
    a = "";
    for(i=0; i<l; i++) {
        var n = characters.indexOf(password.charAt(i));
        a += newchar.charAt(n);
    }
    password = a;
    $("#password").val(password);
    dataString="email="+email+"&password="+password+"&login=";
    url="https://khuranatech.in/pro/crm/mobileapp/login.php";
    if($.trim(email).length>0 && $.trim(password).length>0) {
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            dataType: "json",
            crossDomain: true,
            cache: false,
            beforeSend: function(){ $("#login").html('Connecting...');},
            success: function(data){
                if(data == "failed") {
                    alert("Login failed. Try again.");
                    $("#login").html('Login');
                } else {
                    $.each(data, function(i, field){
                        login = field.login;
                        name = field.name;
                    });
                    if(login == "success") {
                        $(".name").html('Hi, ' + name);
                        $("#login").html('Login');
                        db = openDatabase('ktcrm', '1.0', 'ktcrm', 1024*1024);
                        db.transaction(insertoDB, failed, success);
                        dashboard();
                    } else {
                        alert("Login failed. Try again.");
                        $("#login").html('Login');
                        db = openDatabase('ktcrm', '1.0', 'ktcrm', 1024*1024);
                        db.transaction(deleteDB, failed, success);
                    }
                }
            }
        });
    }return false;
});
$("#formit").click(function(){
    formail = $("#formail").val();
    formob = $("#formob").val();
    dataString="formail="+formail+"&formob="+formob+"&login=";
    url="https://khuranatech.in/pro/crm/mobileapp/forgot.php";
    if($.trim(formail).length>0 && $.trim(formob).length>0) {
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            dataType: "json",
            crossDomain: true,
            cache: false,
            beforeSend: function(){ $("#formit").html('Connecting...');},
            success: function(data){
                if(data == "failed") {
                    alert("Email and Mobile did not match. Try again.");
                    $("#formit").html('Submit');
                } else if (data == "done") {
                    alert("New password sent to your Email and Mobile.");
                    $(".index").removeClass("hide");
                    $(".forgot").removeClass("show");
                    $("#formit").html('Submit');
                } else {
                    alert("Unknown error. Try again.");
                    $("#formit").html('Submit');
                }
            }
        });
    }return false;
});
function insertoDB(tx) {
    password = $("#password").val();
    tx.executeSql("INSERT INTO members (email, password, login) VALUES ( ?, ?, 'yes')", [ $("#email").val(), password]);
}
function success() {
    
}
function failed() {
    
}
$(".logout").click(function(){
    db = openDatabase('ktcrm', '1.0', 'ktcrm', 1024*1024);
    db.transaction(deleteDB, failed, success);
    $(".index").removeClass("hide");
    $(".cpanel").removeClass("show");
    $("#email").val("");
    $("#password").val("");
    menuexit();
});
function logout() {
    db = openDatabase('ktcrm', '1.0', 'ktcrm', 1024*1024);
    db.transaction(deleteDB, failed, success);
    $(".index").removeClass("hide");
    $(".cpanel").removeClass("show");
    $("#email").val("");
    $("#password").val("");
}
function deleteDB(tx) {
    tx.executeSql("DROP TABLE IF EXISTS members");
}
function paynow(sms) {
    if(sms == 750) {
        $(".paynow").fadeOut(100);
        $(".pn750").delay(100).fadeIn(100);
    } else if(sms == 2000) {
        $(".paynow").fadeOut(100);
        $(".pn2000").delay(100).fadeIn(100);
    } else if(sms == 5000) {
        $(".paynow").fadeOut(100);
        $(".pn5000").delay(100).fadeIn(100);
    } else {
        $(".paynow").fadeOut(100);
    }
}
$("#forgot").click(function(){
    $(".forgot").addClass("show");
    $(".index").addClass("hide");
});
$("#recharge").click(function(){
    $(".recharge").addClass("show");
    $(".index").addClass("hide");
});
$("#indexb").click(function(){
    $(".index").removeClass("hide");
    $(".forgot").removeClass("show");
});
$("#index").click(function(){
    $(".recharge").removeClass("show");
    $(".index").removeClass("hide");
});
$(".custlink").click(function(){
    $(".contdiv").fadeOut(500);
    $(".customers").delay(500).fadeIn(500);
    customers();
});
$(".salelink").click(function(){
    $(".contdiv").fadeOut(500);
    $(".sale").delay(500).fadeIn(500);
    sales();
});
$(".explink").click(function(){
    $(".contdiv").fadeOut(500);
    $(".expenses").delay(500).fadeIn(500);
    expenses();
    menuexit();
});
$(".analink").click(function(){
    $(".contdiv").fadeOut(500);
    $(".analytics").delay(500).fadeIn(500);
    analytics();
    menuexit();
});
$(".reflink").click(function(){
    $(".contdiv").fadeOut(500);
    $(".refer").delay(500).fadeIn(500);
    refer();
    menuexit();
});
$(".incadd img").click(function(){
    $(".newinc").fadeIn(500);
});
$(".incclose").click(function(){
    $(".newinc").fadeOut(500);
});
$(".expadd img").click(function(){
    $(".newexp").fadeIn(500);
});
$(".expclose").click(function(){
    $(".newexp").fadeOut(500);
});