<?php

include('connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>Document</title>
</head>
<body>
  <button onclick="myFunction()"><h2>Download</h2></button>

  <script> 
      function myFunction(){
        let text= "Are you Download?"
        if(confirm(text) == true){
          location='CSV.php';
        }else {
          
        }
      }
  </script>
</body>
</html>
