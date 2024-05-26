
<?php
include_once '../db_conn.php';


if(isset($_GET['delete_id']))
{
 $sql_query="DELETE FROM Likes WHERE id=".$_GET['delete_id'];
 mysqli_query($con,$sql_query);
 header("Location: $_SERVER[PHP_SELF]");
}
if(isset($_GET['changestatus_id']))
{
 $sql_query="UPDATE Likes SET `status`='".$_GET['status']."' WHERE id=".$_GET['changestatus_id'];
 mysqli_query($con,$sql_query);
 header("Location: $_SERVER[PHP_SELF]");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Core PHP Crud functions By PHP Code Builder</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> <link rel="stylesheet" href="style.css" type="text/css" />
<script type="text/javascript">
function edt_id(id)
{
  window.location.href='edit_Likes.php?edit_id='+id;
}
function view_id(id)
{
  window.location.href='view_Likes.php?view_id='+id;
}
function delete_id(id)
{
 if(confirm('Sure to Delete ?'))
 {
  window.location.href='indexLikes.php?delete_id='+id;
 }
}
function changestatus_id(id,status)
{
  window.location.href='indexLikes.php?changestatus_id='+id+'&status='+status;
}
</script>
</head>
<body>
<center>

<div id="container"> <div id="table-responsive">
    <label>Core PHP Crud functions - <a href="http://www.phpcodebuilder.com" target="_blank">By PHP Code Builder</a></label>
    </div>
</div>

<div id="container">
 <div id="table-responsive">
 
    <table  class="table table-striped" > 
    <tr>
    <th colspan="5"><a href="add_Likes.php">add Likes.</a></th>
    </tr>
    <th>SL NO</th>
    <th>like_id</th>
   
    <th colspan="3">Actions</th>
    </tr>
    <?php
 $sql_query="SELECT * FROM Likes";
 $result_set=mysqli_query($con,$sql_query);
 $i=1;
 while($row=mysqli_fetch_row($result_set))
 {
  ?>
        <tr>
        <td align="center" ><?php echo $i; ?></td>
        <td align="center" > <a href="javascript:view_id('<?php echo $row[0]; ?>')"> <?php echo $row[1]; ?> </a> </td>
        <?php if($row[count($row)-1]==1) { ?>
        <td align="center"><a href="javascript:changestatus_id('<?php echo $row[0]; ?>',0)">Deactivate</a></td>
        <?php } else { ?>
        <td align="center"><a href="javascript:changestatus_id('<?php echo $row[0]; ?>',1)">Activate</a></td>
        <?php } ?>
  <td align="center"><a href="javascript:edt_id('<?php echo $row[0]; ?>')">Edit</a></td>
        <td align="center"><a href="javascript:delete_id('<?php echo $row[0]; ?>')">Delete</a></td>
        </tr>
        <?php
       $i++;  
 }
 ?>
    </table>
    </div>
</div>

</center>
</body>
</html>





