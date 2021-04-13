<?php

$db = new PDO("mysql:host=mysql51-34.perso;dbname=esthorizbdd","esthorizbdd","M2i0n1e7s");
$results["error"] = false;
$results["message"] = array();

if(isset($_POST)){

	if(!empty($_POST["pseudo"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["password2"])){

		$pseudo = $_POST["pseudo"];
		$email = $_POST["email"];
		$password = $_POST["password"];
		$password2 = $_POST["password2"];

		//Verification du pseudo

		if(strlen(pseudo) < 2 || !preg_match("/^[a-zA-Z0-9 _-]+$/", $pseudo) || strlen($pseudo)>60){
			$results["error"] = true;
			$results["message"]["pseudo"] = "Pseudo invalide";

		}else{
			//Verifier que le pseudo n'existe pas
			$requete = $db->prepare("SELECT ids FROM test WHERE pseudo = :pseudo");
			$requete->execute(array(":pseudo" => $pseudo));
			$row = $requete->fetch();
			if($row){
				$results["error"] = true;
				$results["message"]["pseudo"] = "Le pseudo est deja pris";
			}
		}


		//Verification de l'email
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$results["error"] = true;
			$results["message"]["email"] = "Email invalide";
		}else{
			//Verifier que l'email n'existe pas
			$requete = $db->prepare("SELECT ids FROM test WHERE email = :email");
			$requete->execute(array(":email" => $email));
			$row = $requete->fetch();
			if($row){
				$results["error"] = true;
				$results["message"]["pseudo"] = "L'email est deja pris";
			}
		}

		//Verification des password
		if($password !== $password2){
			$results["error"] = true;
			$results["message"]["password"] = "Les mots de passe doivent être identique";
		}

		if($results["error"] === false){

			$password = sha1($password);
			
			//Insertion
			$sql = $db->prepare("INSERT INTO test(pseudo,email,password) VALUES(:pseudo,:email,:password)");

			$sql->execute(array(":pseudo" => $pseudo, ":email" => $email, ":password" => $password));

			if(!sql){
				$resutls["error"] = true;
				$results["message"] = "Erreur lors de l'inscription";
			}
			

		}



	}else{
		$results["error"] = true;
		$results["message"] = "Veuillez remplir tous les champs";
	}

	print(json_encode($results));
	
}

?>