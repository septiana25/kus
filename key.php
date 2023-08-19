<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Belajarphp.net</title>
    </head>


    <body>
        <form  name="form1" id="form1" method="post" action="index.php">
            <input type="text" name="">
            <button  type="submit">SAVE [F2]</button>
        <button type="button">CLEAR [F3]</button>
        </form>
        
<?php require_once 'include/footer.php' ?>
<script type="text/javascript">
$(document).ready(function(){

    jQuery(document).bind('keydown', 'Ctrl+s',function() {
      $('#form1').submit();
      return false;
    });
    $(document).bind('keydown', 'f3', function() {
        alert('Clear Data');
    });
});
</script>
    </body>
 
</html> 
