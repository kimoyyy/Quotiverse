<?php
include_once '../db_conn.php';

if(isset($_GET['edit_id']))
{
 $sql_query="SELECT * FROM Quotes WHERE id=".$_GET['edit_id'];
 $result_set=mysqli_query($con,$sql_query);
 $fetched_row=mysqli_fetch_array($result_set,MYSQLI_ASSOC);
}
if(isset($_POST['btn-update']))
{
 // variables for input data
     
   $quote_id = $_POST['quote_id'];
          
   $id = $_POST['id'];
          
   $quote = $_POST['quote'];
        // variables for input data

 // sql query for update data into database
  $sql_query="UPDATE Quotes SET `quote_id`='$quote_id',`id`='$id',`quote`='$quote' WHERE id=".$_GET['edit_id'];

 // sql query for update data into database
 
 // sql query execution function
 if(mysqli_query($con,$sql_query))
 {
  ?>
  <script type="text/javascript">
  alert('Quotes updated successfully');
  window.location.href='indexQuotes.php';
  </script>
  <?php
 }
 else
 {
  ?>
  <script type="text/javascript">
  alert('error occured while updating data');
  </script>
  <?php
 }
 // sql query execution function
}
if(isset($_POST['btn-cancel']))
{
 header("Location: indexQuotes.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Core PHP Crud functions By PHP Code Builder</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<center>

<div id="container"> <div id="table-responsive">
        <label>Core PHP Crud functions - <a href="http://www.phpcodebuilder.com" target="_blank">By PHP Code Builder</a></label>
    </div>
</div>

<div id="container"> &<div   id="table-responsive">
    <form method="post" enctype="multipart/form-data">
    <table  class="table table-striped">
    <tr>
   <td>
   <label for="quote_id" class="form-label">Quote_id:</label>
   </td>
    <td>
    <input type="number" value="<?php echo $fetched_row['quote_id'] ?>" class="form-control" id="quote_id" name="quote_id">
</td>
    </tr>
  <tr>
   <td>
   <label for="id" class="form-label">Id:</label>
   </td>
    <td>
    <input type="number" value="<?php echo $fetched_row['id'] ?>" class="form-control" id="id" name="id">
</td>
    </tr>
  <tr>
   <td>
   <label for="quote" class="form-label">Quote:</label>
   </td>
    <td>
    <input type="text" value="<?php echo $fetched_row['quote'] ?>" class="form-control" id="quote" name="quote">
</td>
    </tr>
      <tr>
    <td>
    <button type="submit" name="btn-update"><strong>UPDATE</strong></button>
    <button type="submit" name="btn-cancel"><strong>Cancel</strong></button>
    </td>
    </tr>
    </table>
    </form>
    </div>
</div>

</center>
</body>
</html>
