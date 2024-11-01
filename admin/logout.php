<?php
session_start();
unset($_SESSION['adminlogin']);
echo "<script>alert('Logout successful')</script>";
echo "<script>location='index.php'</script>";
?>