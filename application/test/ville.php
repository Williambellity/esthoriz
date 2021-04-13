<?php

$servername = "mysql51-34.perso";
$username = "esthorizbdd";
$password = "M2i0n1e7s";
$dbname = "esthorizbdd";


if (isset($_POST["nom"])){

$conn = new mysqli($servername,$username,$password,$dbname);

if ($conn->connect_error){
	die("Erreur de connexion : ".$conn->connect_error);
}

$nom = $_POST["nom"];
$sql = "INSERT INTO villetest (nom) VALUES ('$nom')";

if ($conn->query($sql) === TRUE){
	echo "Ajout avec succes";
} else {
	"Erreur d ajout :".$sql."<br>".$conn->error;
}

$conn->close();

} else {
	$nom = $_POST["nom"];
	echo "Nom Invalide blabla $nom";
}
?>