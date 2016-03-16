<?php
/* Connection vars here for example only. Consider a more secure method. */
$dbhost = '127.0.0.1';
$dbuser = Config::read('db.user');
$dbpass = Config::read('db.password');
$dbname = Config::read('db.basename');
 
try {
  $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
}
catch(PDOException $e) {
    echo $e->getMessage();
}
 
$return_arr = array();
 
if ($conn)
{
    $ac_term = "%".$_GET['term']."%";
    $query = "SELECT * FROM vserv_contatti WHERE label LIKE :term";
    $result = $conn->prepare($query);
    $result->bindValue(":term",$ac_term);
    $result->execute();
     
    /* Retrieve and store in array the results of the query.*/
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row_array['label'] = $row['label'];
         
        array_push($return_arr,$row_array);
    }
 
     
}
/* Free connection resources. */
$conn = null; 
/* Toss back results as json encoded array. */
echo json_encode($return_arr);

?>
