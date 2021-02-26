<?php

session_start();
require_once 'dbconnect.php';
$connectError='<h3>Przepraszamy, błąd połączenia.</h3>';
$updateOnline_ok=false;
$updateOnline_infoWrong="Coś poszło nie tak. Spróbuj jeszcze raz.";

try
{
	if($connect->connect_errno!=0)
		throw new Exception($connect->error);
	else
	{
		$connect->query("SET CHARSET utf8");
		$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    $id_my=$_POST['id_my'];
    $id_player=$_POST['id_player'];
    if(isset($id_my) && isset($id_player))
    {
        if(!empty($id_my) && !empty($id_player))
        {
          if( ($connect->query("UPDATE players SET available=1, id_other='".session_id()."' WHERE id_session='".$id_player."' ")) && ($connect->query("UPDATE players SET available=1 WHERE id_session='".session_id()."' ")) )
					{
						//$invite_time=strtotime("now");
            $updateOnline_ok=true;
					}
          else
            throw new Exception($connect->error);
        }
        else
          throw new Exception($updateOnline_infoWrong);
    }
    else
      throw new Exception($updateOnline_infoWrong);
  }
}
catch(Exception $e)
{
	echo $connectError;
}

?>

<script>
var updateOnline_ok="<?php echo $updateOnline_ok; ?>";
infoOnline();

if(updateOnline_ok==true) {
	var id_player="<?php echo $id_player; ?>";
	//var invite_time=29;
	$(".infoOnline_info").load("playerInvite_givenInvite.html", function() { //tutaj jest od wysyłającego
		$(".givenInvite_playerId").html(id_player);
		$(".infoOnline_info button").on("click", function() {
			invite_cancel();
		})
		/*sessionStorage.removeItem("invitePlayer_sent"); //wstępne czyszczenie - w razie spamowania akcji "zaproś -> anuluj"
		var invite_time_interval=setInterval(function() {
			sessionStorage.setItem("invitePlayer_sent", invite_time_interval); //zapamiętanie interwału czasowego do wyczyszczenia
			$("#givenInvite_inviteTime").html(invite_time); //wypisywanie pozostałej ilości czasu
			invite_time--; //zmniejszanie sekund aktywności zaproszenia
			if(invite_time==0) {
				setTimeout(function() {
					clearInterval(invite_time_interval);
					sessionStorage.removeItem("invitePlayer_sent");
					//jeszcze akcje informujące o wygaśnięciu zaproszenia i zmiana stanu zaproszonego gracza w bazie danych
					$(".waiting").html("Czas na przyjęcie zaproszenia minął.");
				}, 1000);
			}
		}, 1000);*/
	});
}

</script>
