<?php
include("PDOconnexion.php");


define("ACTION_RECUP_CAT","0");
define("ACTION_RECUP_ENTREPRISES","1");
define("ENTREPRISES_TOUTES","0");
define("RESULT_SUCCESS",0);
define("RESULT_ERROR",1);


$action = $_POST["action"];
$secteur = $_POST["secteur"];
$result = "";
$error = RESULT_ERROR;

if(isset($action))
{
	if($action == ACTION_RECUP_CAT)
	{
		$result = entreprises_cats($cnn);
		$error = RESULT_SUCCESS;
	}

	else if($action == ACTION_RECUP_ENTREPRISES)
	{
		if(isset($secteur)){
			if($secteur == ENTREPRISES_TOUTES)
			{
				$result = entreprises_toutes($cnn);
				$error = RESULT_SUCCESS;
			}
			else
			{
				$result = entreprises_secteur($cnn,$secteur);
				$error = RESULT_SUCCESS;
			}
		}
	}
}
echo(json_encode(array("result" => utf8_encode($result), "error" => $error)));

function entreprises_cats($cnn)
{
	$query = "SELECT * FROM entreprises_cats";
	$stmt = $cnn->prepare($query);
	$stmt->execute();

	while($data = $stmt->fetch())
    {
    	$sect=$data['id'];
    	if(entreprises_secteur_vide($cnn,$sect)!=0){
    		$resu .= $data['id']."!";
    		$resu .= $data['cat_name']."%";
    	}

    }

    return $resu;
}

function entreprises_toutes($cnn)
{
	$query = "SELECT * FROM entreprises WHERE choix_pack!=0";
	$stmt = $cnn->prepare($query);
	$stmt->execute();
	$resu = "";

	while($data = $stmt->fetch())
    { 
    	$resu .= $data['id']."!";
    	$resu .= $data['name']."!";
    	$resu .= $data['website']."%";

    }

    return $resu;
}

function entreprises_secteur($cnn,$secteur)
{
	$query = "SELECT * FROM entreprises WHERE choix_pack != 0 AND cat = ?";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$secteur);
	$stmt->execute();

	$resu = "";
	while ($data = $stmt->fetch())
	{
		$resu .= $data['id']."!";
		$resu .= $data['name']."!";
		$resu .= $data['website']."%";

	}

	return $resu;
}

function entreprises_secteur_vide($cnn,$sector)
{
	$query2 = "SELECT * FROM entreprises WHERE choix_pack != 0 AND cat = ?";
	$stmt = $cnn->prepare($query2);
	$stmt->bindParam(1,$sector);
	$stmt->execute();
	$rowcount = $stmt->rowcount();

	return $rowcount;
}