<html>
  <head>
    
  </head>
 
  <body>
    <form id="myForm" action="viewBookings.php" method="post"> 
     
       <input type="hidden" name="userId" value="<?php echo $_GET['userId']; ?>"/>      
    
    </form>

    <script type="text/javascript">
      document.getElementById('myForm').submit();
    </script>
  </body>
 
</html>

