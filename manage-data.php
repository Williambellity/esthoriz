<?php
header("Access-Control-Allow-Origin: *");
$mysql_host = "mysql51-34.perso";
$mysql_database = "esthorizbdd";
$mysql_user = "esthorizbdd";
$mysql_password = "M2i0n1e7s";
// Create connection

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password,$mysql_database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//$sql = "SELECT nickname FROM users WHERE nickame = ";
$sql = "SELECT nickname FROM `users` WHERE id >= 25 AND id <= 26";
$result = $conn->query($sql);
$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"nickname":"'  . $rs["nickname"] . '}';
}
$outp ='{ [ '.$outp.' ]}';
echo(json_encode($outp));
return json_encode($outp);
$conn->close();

?>