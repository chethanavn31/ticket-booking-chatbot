<html>
  <head>
    
  </head>
 
  <body>
 <?php // echo $_GET["layout"]; ?>
    <form id="myForm" action="<?php echo $_GET["layout"]; ?>" method="post"> 
     
       <input type="text" name="mid" value="<?php echo $_GET['mid']; ?>"/>
       <input type="text" name="thid" value="<?php echo $_GET['thid']; ?>"/>
       <input type="text" name="locid" value="<?php echo $_GET['locid']; ?>"/>
       <input type="text" name="shid" value="<?php echo $_GET['shid']; ?>"/>
       <input type="text" name="userId" value="<?php echo $_GET['userId']; ?>"/>
       <input type="text" name="date" value="<?php echo $_GET['date']; ?>"/>       
    
    </form>

    <script type="text/javascript">
      document.getElementById('myForm').submit();
    </script>
  </body>
 
</html>

