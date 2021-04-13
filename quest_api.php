<?php
session_start();
if(!isset($_SESSION["logged"])){
    $_SESSION["logged"] = false;
}

if(!isset($_POST["req-type"])){
    die("UNAUTHORIZED ACCESS");
}

$secret_pw = "testpassword";

if($_POST["req-type"] == "isLogged"){
    die((string)(int)$_SESSION["logged"]);
}

if($_POST["req-type"]=="login"){
    if($_POST["req-login"]==$secret_pw){
        //TODO : change password
        $_SESSION["logged"] = true;
        die("0");
    }
    die("101");
}
if((!isset($_SESSION["logged"]) || !$_SESSION["logged"])&&(!isset($_POST["login"]))){
    if(!isset($_POST["login"]) || $_POST["login"] != $secret_pw){
        die("UNAUTHORIZED ACCESS");
    }
}

$dbc = array(
	'server' => 'mysql51-34.perso',
	'port'   => '',
	'user'   => 'esthorizbdd',
	'pw'     => 'M2i0n1e7s',
	'dbname' => 'esthorizbdd'
);
try {
    $conn = new PDO("mysql:host=".$dbc['server'].";dbname=".$dbc["dbname"], $dbc["user"], $dbc["pw"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    die("Connection failed: " . $e->getMessage());
}

switch($_POST["req-type"]){
    case "get-questions":
        $req = $conn->prepare("SELECT * FROM questions WHERE qid>=:qid");
        $req->bindParam('qid', intval($_POST["question_id"]), PDO::PARAM_INT);
        $req->execute();
        print(json_encode($req->fetchAll()));
        break;
    case "test-add-question":
        $req = $conn->prepare("INSERT INTO questions(txt) VALUES (:txt)");
        $req->execute(array(
            "txt"=>$_POST["tst_msg"]
        ));
        break;
    case "delete-question":
        $req = $conn->prepare("DELETE FROM questions WHERE qid=:qid");
        $req->bindParam('qid', intval($_POST["question_id"]), PDO::PARAM_INT);
        $req->execute();
        break;
    case "mod-question":
        $req = $conn->prepare("UPDATE questions SET verified=1 WHERE qid=:qid");
        $req->bindParam('qid', intval($_POST["question_id"]), PDO::PARAM_INT);
        $req->execute();
        break;
    case "update-verified":
        $req = $conn->prepare("SELECT qid FROM questions WHERE verified=1");
        $req->execute();
        print(json_encode($req->fetchAll()));
        break;
}
?>