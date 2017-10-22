<?php 
session_start();
include('db_connection.php');
?>
<?php
function get_contents($input){
global $dbh;	
 $sql = "SELECT * from content where id= ".$input;
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{
$array['id']=$input;	
$array['content']=$result->content;

json_encode($array);
return $array;


}
}
}
	
$x=get_contents(1);
echo $x['content'];
 ?>