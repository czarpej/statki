<?php

require_once 'dbconnect.php';
$connectOk=false;

try
{
	if($connect->connect_errno!=0)
		throw new Exception($connect->connect_errno());
	else
	{
		$connect->query("SET CHARSET utf8");
		$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		if($connect->query("DELETE FROM players WHERE last_activity<(NOW() - INTERVAL 30 SECOND)"))
			setcookie("deleteUnactivePlayer", "ok", time()+5);
		else
			throw new Exception($connect->error);
	}
}
catch(Exception $e)
{
	echo '<h3>Przepraszamy, błąd połączenia.</h3>';
}
