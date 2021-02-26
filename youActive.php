<?php

session_start();

require_once 'dbconnect.php';
$status=false;

try
{
	if($connect->connect_errno!=0)
		;//throw new Exception($connect->connect_errno());
	else
	{
		$connect->query("SET CHARSET utf8");
		$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		if($users=$connect->query("SELECT id_session FROM players WHERE last_activity>(NOW() - INTERVAL 30 SECOND) AND id_session='".session_id()."' "))
		{
			if($users->num_rows>0)
			{
				if($connect->query("UPDATE players SET last_activity=NOW(), ready_id=1 WHERE id_session='".session_id()."' "))
					$status=true;//setcookie("youActive", "ok", time()+10);
				else
					;//throw new Exception($connect->error);
			}
			else
			{
				if($connect->query("INSERT INTO players VALUES('".session_id()."', NOW(), 0, 1, 0, 0, 0)"))
					$status=true;//setcookie("youActive", "ok", time()+10);
				else
					;//throw new Exception($connect->error);
			}
		}
		else
			;//throw new Exception($connect->error);
	}
}
catch(Exception $e)
{
	echo '<h3>Nie udało się dołączyć do sieci.</h3>';
}

if($status==true)
	echo 'Online';
else
	echo 'Offline';

?>

<script>

	var status="<?php echo $status; ?>";
	if(status==true)
	{
		$(".status_online_color").css({
			"backgroundColor": "green"
		});
	}
	else
	{
		$(".status_online_color").css({
			"backgroundColor": "gray"
		});
	}

</script>
