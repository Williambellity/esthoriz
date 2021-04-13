<?php

require('model.php');

$mail=$_POST['mail'];
$mdp=$_POST['mdp'];
if ($mdp==2020){
	echo "OK";
	echo $mail;
	prepare('UPDATE visiteurs SET Present=1 WHERE visiteurs.mail=$data
		');
	execute();
}

else {
	echo "NON";
}
?>