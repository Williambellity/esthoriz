<?php
    //http://stackoverflow.com/questions/18382740/cors-not-working-php
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    $mysql_host = "mysql51-34.perso";
    $mysql_database = "esthorizbdd";
    $mysql_user = "esthorizbdd";
    $mysql_password = "mines11nancy";

    //http://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
    $postdata = file_get_contents("php://input");
    if (isset($postdata)) {
        $request = json_decode($postdata);
        $username = $request->username;
        $resultstring = $request->result;
        $result = intval($resultstring);

        if($username != ''){
        $conn = new mysqli($mysql_host, $mysql_user, $mysql_password,$mysql_database);
        /*if ($username != "") {
            echo "Server returns: " . $username;
        }
        else {
            echo "Empty username parameter!";
        }*/
        $sql = "SELECT nickname FROM `users` WHERE (nickname = '$username' OR email = '$username')";
        $resultat = $conn->query($sql);
        if($resultat->num_rows == 1){
            $sql= "UPDATE `users` SET concours = $result WHERE (nickname = '$username' OR email = '$username') ";
            $resultat = $conn->query($sql);
            echo('Vote accepte1');
        }else{
            $sql = "SELECT mail FROM `visiteurs` WHERE ( mail = '$username')";
            $resultat = $conn->query($sql);
            if($resultat->num_rows == 1){
                $sql= "UPDATE `visiteurs` SET concours = $result WHERE ( mail = '$username') ";
                $resultat = $conn->query($sql);
                echo('Vote accepte');
            }else{
                echo('Authentification echouee. Veuillez essayer de nouveau.');
            }
        }
        }else{
            echo("Pas de data");
        }


    }
    else {
        echo "Erreur dans votre identifiant / MDP. Peut etre lie a la connexion.";
    }
?>