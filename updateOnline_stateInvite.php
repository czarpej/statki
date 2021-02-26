<?php

session_start();
require_once 'dbconnect.php';
$connectError='<h3>Przepraszamy, błąd połączenia.</h3>';
$updateOnline_ok=false;
$givenInvite_ok=0;
$updateOnline_infoWrong="Coś poszło nie tak. Spróbuj jeszcze raz.";

try
{
	if($connect->connect_errno!=0)
		;//throw new Exception($connect->error);
	else
	{
		$connect->query("SET CHARSET utf8");
		$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    if($givenInvite=$connect->query("SELECT id_other FROM players WHERE id_session='".session_id()."' "))
    {
      $updateOnline_ok=true;
      if($givenInvite->num_rows>0)
      {
        $givenInvite_results=$givenInvite->fetch_row();
        if($givenInvite_results[0] != 0 || $givenInvite_results[0] !== "2" || $givenInvite_results[0] !== "3") { //dlaczego || a nie && ?
          $givenInvite_ok=$givenInvite_results[0];
					$_SESSION['id_player']=$givenInvite_results[0];
        }
				else if($givenInvite_results[0] == "2") {
					;
					/*if($connect->query("UPDATE players SET id_other=0 WHERE id_session='".$_POST['id_playerR']."' "))
						;
					else
						;*/
					//a co tutaj? tutaj chyba nic, ale przy następnym odświeżaniu aktywności nastąpi zmiana 2 na 0. 2 to zabezpieczenie
				}
				else if($givenInvite_results[0] == "3") {
					; //tutaj chyba też nic - obecnie nic mi nie przychodzi do głowy
				}
      }
      else
        ; //wtedy nic się nie dzieje
    }
    else
      ;//throw new Exception($connect->error);
  }
}
catch(Exception $e)
{
	echo $connectError;
}

?>

<script>

function infoOnline_changeInfo(info) {
	$(".givenInvite_divMain").html("<span style='margin: 15px 0;'>"+info+"</span>");
	$(".infoOnline_info").next().html("<button onclick='infoOnline_close()'>OK</button>");
	function help() {
		console.log("wykonało się"); //dlaczego podwójnie??
		infoOnline_close();
		$(".infoOnline_hidden").load("updateOnline_resetInvite.php"); //co w przypadku niepowodzenia - zobacz plik
		$(".infoOnline_info").delay(300, function() {
			$(this).load("playerInvite_givenInvite.html");
		});
	}
	countdown(help);
}

var status_invite="<?php echo $updateOnline_ok; ?>";
if(status_invite) {
  status_invite="<?php echo $givenInvite_ok; ?>";
	if(status_invite!=0) {
		if(status_invite=="2") {
			infoOnline_changeInfo("Zapraszający gracz wycofał zaproszenie.");
		}
		else if(status_invite=="3") {
			infoOnline_changeInfo("Wysłane zaproszenie zostało odrzucone.");
		}
		else {
	    $(".invite_header").html("Otrzymano zaproszenie od gracza o ID:");
	    $(".givenInvite_playerId").html(status_invite);
	    $(".waiting").html("Czy potwierdzasz zaproszenie?");
			$(".infoOnline_info").next().html("\
				<button type='button' style='margin-bottom: 5px;'>Potwierdź</button>\
				<button type='button' onclick='invite_rejection()'>Odrzuć</button>\
			");
	    infoOnline();
	  }
	}
}

</script>
