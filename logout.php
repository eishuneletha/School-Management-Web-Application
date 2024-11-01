<?php
session_start();
unset($_SESSION['authorid']);
unset($_SESSION['authortype']);
echo "<script>alert('Logout successful')</script>";
echo "<script>location='index.php'</script>";
?>