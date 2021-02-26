<?php

session_start();
require_once 'dbconnect.php';
$playerID_isset=false; //zmienna określająca czy istnieje gracz o podanym ID
$connectError='<h3>Przepraszamy, błąd połączenia.</h3>';

try
{
	if($connect->connect_errno!=0)
		$playerID_info=$connectError;
	else
	{
		$connect->query("SET CHARSET utf8");
		$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    if(isset($_POST['playerID']))
    {
      if(!empty($_POST['playerID']))
      {
        $playerID=$connect->real_escape_string($_POST['playerID']);
        if($playerID_ok=$connect->prepare("SELECT id_session FROM players WHERE id_session=?"))
        {
          $playerID_ok->bind_param('s', $playerID);
          if($playerID_ok->execute())
          {
            $result=$playerID_ok->get_result();
            if($result->num_rows>0)
							$playerID_isset=true;
            else
							throw new Exception("<h3>Przepraszamy, gracz o podanym ID nie jest już aktywny.</h3>");
          }
          else
            	throw new Exception($connect->error);
        }
        else
          throw new Exception($connect->error);
      }
      else
				throw new Exception("<h3>Błąd! System nie odebrał żadnego ID.</h3>");
    }
    else
      throw new Exception($connect->error); //no a jak nie istnieje/nie przesłano nic, to też ma to samo wypisać??
  }
}
catch(Exception $e)
{
	echo $connectError;
}

?>

<script>

	var playerID_isset="<?php echo $playerID_isset; ?>";

	if(playerID_isset!=true)
		infoOnline();
	else {
		var playerID="<?php echo $_POST['playerID']; ?>";
		var id_session="<?php echo session_id(); ?>";
		/*$(".form_invitePlayer").submit(function(event) {
			//event.preventDefault();
			*/$(".infoOnline_info").load("updateOnline_sentInvite.php", {
				id_my: id_session,
				id_player: playerID
			});/*
		});*/
		/*var request=$.ajax({
	    url: "updateOnline_sendInvite.php",
	    method: "POST",
	    data: {
				"id_my": "id_session",
				"id_player": "playerID"
			}
	    //dataType: "json"
		});
    request.done(function(data){
        alert("success success");
	    });
		request.fail(function(data) {
		    alert("failure");
		});*/
		/*request.always(function() {
	    alert( "complete" );
	  });*/
	}

</script>
