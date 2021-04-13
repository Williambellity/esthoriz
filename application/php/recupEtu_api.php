<?php
include("PDOconnexion.php");

define("RESULT_SUCCESS",0);
define("RESULT_ERROR",1);

$idetu = $_POST["id"];
$erreur = RESULT_SUCCESS;

$result = recup_etudiant($cnn,$idetu);
if($result==""){
	$erreur=RESULT_ERROR;
}
echo(json_encode(array("result" => utf8_encode($result), "erreur" => $erreur)));


function recup_etudiant($cnn, $id) {
	$resu = "";
	$query = "SELECT * FROM visiteurs WHERE id=?";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$id);
	$stmt->execute();
	$rowcount = $stmt->rowcount();
	if ($rowcount !=0){
		$data = $stmt->fetch();
		$resu = $data['nom']."%".$data['prenom']."%".$data['mail'];
	}
	return $resu;
}