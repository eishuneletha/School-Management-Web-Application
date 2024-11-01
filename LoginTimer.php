<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
<head>
<style>
p 
{
    text-align: center;
    font-size: 60px;
    margin-top:0px;
}
</style>
</head>
<body>

<p id="Timer"></p>

<script>

// Set the date we're counting down to
var month=new Date().getMonth()+1;
var hour=new Date().getHours();
var day=new Date().getDate();
var year=new Date().getFullYear();
var minutes=new Date().getMinutes()+10;
var second=new Date().getSeconds();
var time=hour+":"+minutes+":"+second;
var ResetTime = new Date(month +" "+day+", "+year+" "+time).getTime();


var x = setInterval(function() {

  
    //var ResetTime = new Date("Sep 5, 2019 15:37:25").getTime();
    var now = new Date().getTime();
    var distance = ResetTime - now;

    //var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    document.getElementById("Timer").innerHTML = "<h1>Please try again </h1>"+ minutes + "m " + seconds + "s ";
    //document.getElementById("Timer").innerHTML = "<h1>Your account will expire in </h1>"+ days + "day(s)";

    if (distance < 0) {
        clearInterval(x);
        document.getElementById("Timer").innerHTML = "<?php session_destroy(); ?>";
        location='qac_login.php';
    }
}, 1000);
</script>

</body>
</html>
