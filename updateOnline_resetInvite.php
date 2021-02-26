<?php

session_start();
require_once 'dbconnect.php';
$connectError='<h3>Przepraszamy, błąd połączenia.</h3>';
/*$updateOnline_ok=false;
$givenInvite_ok=0;
$updateOnline_infoWrong="Coś poszło nie tak. Spróbuj jeszcze raz.";*/

try
{
	if($connect->connect_errno!=0)
		throw new Exception($connect->error);
	else
	{
		$connect->query("SET CHARSET utf8");
		$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    if($connect->query("UPDATE players SET id_other=0, available=0 WHERE id_session='".session_id()."' "))
      ;
    else
      ; //później się nad tym pomyśli
  }
}
catch(Exception $e)
{
echo $connectError;
}

?>
