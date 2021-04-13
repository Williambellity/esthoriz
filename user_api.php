<?php
include("PDOconnexion.php");

define("ACTION_ADD_USER","add");
define("ACTION_LOGIN","login");
define("RESULT_SUCCESS",0);
define("RESULT_ERROR",1);
define("RESULT_USER_EXIST",2);
define("RESULT_SUCCESS_VISITEUR");


$action = $_POST["action"];
$result = RESULT_ERROR;

if(isset($action))
{
	$username = $_POST["username"];
	$pwd = $_POST["password"];

	if(ACTION_ADD_USER == $action) // Action add user
	{
		//check exists user
		if(isExistUser($cnn,$username))
		{
			$result = RESULT_USER_EXIST;
		}
		else
		{
			insertUser($cnn,$username,$pwd);
			$result = RESULT_SUCCESS;
		}
	}
	else //Action login user
	{
		if(login($cnn,$username,$pwd))
		{
			$result=RESULT_SUCCESS;
		}
		else if(loginVisiteur($cnn,$username,$pwd))
		{
			$result=RESULT_SUCCESS_VISITEUR;
		}
		else
		{
			$result=RESULT_ERROR;
		}
	}
}
echo(json_encode(array("result" => $result)));


function insertUser($cnn,$username,$pwd)
{
	$query = "INSERT INTO teste(username, password) VALUES (?,?)";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$username);
	$stmt->bindParam(2,$pwd);
	$stmt->execute();
}

function isExistUser($cnn,$username)
{
	$query = "SELECT * FROM teste WHERE username=?";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$username);
	$stmt->execute();
	$rowcount = $stmt->rowcount();
	//For debug
	//var_dump($rowcount);
	return $rowcount;
}

function login($cnn,$username,$pwd)
{
	$p = sha1($pwd);
	$query = "SELECT * FROM users WHERE nickname=? AND password=?";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$username);
	$stmt->bindParam(2,$p);
	$stmt->execute();
	$rowcount = $stmt->rowcount();
	//For debug
	//var_dump($rowcount);
	return $rowcount;
}

function loginVisiteur($cnn,$username,$pwd)
{
	$p = sha1($pwd);
	$query = "SELECT * FROM visiteurs WHERE mail=? AND clecheck = 1";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$username);
	$stmt->bindParam(2,$p);
	$stmt->execute();
	$rowcount = $stmt->rowcount();
	//For debug
	//var_dump($rowcount);
	return $rowcount;
}