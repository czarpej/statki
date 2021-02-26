//----------------------------------------- zmienne -----------------------------------------
var onlineActivity_last=null; //ostatnio odnotowana aktywność gracza
var back_original; //zmienna pamiętająca kod HTML menu wyboru z gry online
var findId_check; //zmienna od setInterval odświeżająca co jakiś czas dostępność innych graczy
var invite_playerId=null; //zmienna zapamiętująca ID zaproszonego przez nas gracza
var infoOnline_isset=null; //zmienna timeout przechowująca aktualny ostatni infoOnline

//----------------------------------------- funkcje -----------------------------------------
function otherActive(children)
{
	setTimeout(function() {
		children.load("onlinePlayers.php", function() {
			;
		});
		//time(5.5);
	}, 100);
}

function youActive(children)
{
	children.load("deleteUnactivePlayers.php", function() {
		;
	}).delay(50);
	children.load("youActive.php", function() {
		if(onlineActivity_last==null || onlineActivity_last!=status)
		{
			onlineActivity_last=status;
			var player_id=$(".container").find("#player_id");
			if(status==true) {
				player_id.html(
					"<h3>Twoje ID:</h3><div id='playerId'></div>" +
					"<button type='button' id='playerId_copyToClipboard' onclick='playerId_copyToClipboard()'>Skopiuj do schowka</button>"
				);
				player_id.find("#playerId").load("return_sessionId.php");

				//efekty wizualne
				$(".online_players").find(".choice").css({
					"display": "flex"
				});
			}
			else {
				player_id.html(
					"<h3>Nie udało się dołączyć do sieci.</h3>"
				);
				//efekty wizualne
				$(".online_players").find(".choice").css({
					"display": "none"
				});
			}
		}

		if(status==true) {
			//stan zaproszeń
			$(".infoOnline_hidden").load("updateOnline_stateInvite.php");
		}
	});
}

function showCookie(name) //odczytuje ciasteczko
{
    if (document.cookie !== "")
    {
        var cookies = document.cookie.split(/; */);

        for (let i=0; i<cookies.length; i++) {
            var cookieName = cookies[i].split("=")[0];
            var cookieVal = cookies[i].split("=")[1];
            if (cookieName === decodeURIComponent(name)) {
                return decodeURIComponent(cookieVal);
            }
        }
    }
}

function deleteCookie(name) //usuwa ciasteczko
{
    const cookieName = encodeURIComponent(name);
    document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function time(i)
{

	if(i%1==0)
		$(".online_info_time").find("span").html(i);
	if(i<0.5)
		return;
	i-=0.5;
	setTimeout(function() {
		time(i);
	}, 500);
}

function find_player()
{
	obiekt=$(".container").find(".online_players");
	obiekt.find(".choice").css({
		"display": "none"
	}).next().css({
		"display": "flex"
	});
	var children=obiekt.find(".online_players_inner");

	$(".online_info_time").find("span").html(5.5);
	youActive(children); //twoja aktywność odnotowana
	setTimeout(function() {
		var activites=showCookie("youActive");
		if(typeof(activites)!=="undefined")
		{
			deleteCookie("youActive");
			refresh(children); //aktywność innych graczy
		}
	}, 500);

	obiekt.slideDown(1000);
}

function find_id()
{
	$(".choice").slideUp(700, function() {
		var object=$(this);
		object.html("<div></div> "); //wstawienie tam tego elementu powoduje płynne rozjeżdżanie przy ładowaniu danych
		otherActive(object);
		findId_check=setInterval(function() {
			;//otherActive(object);
		}, 5000);
		$(this).slideDown(700);
	});
}

function player_invite(id)
{
	/*$(".form_invitePlayer").submit(function(event) {
		event.preventDefault();
		*/
		invite_playerId=id;
		$(".infoOnline_info").load("return_inviteState.php", {
			playerID: id
		});
		/*
	});*/
	/*$.ajax({
		url: "return_sessionIdOk.php",
		method: "POST",
		data: {
			"id_my": id
		}
		//dataType: "JSON"
	})
	.done(function(data){
			alert("first: success");
		})
	.fail(function(data) {
			alert("first: failure");
	});*/
	/*$.post("return_sessionIdOk.php", {
		id_my: id
	})
	.done(resp => {
		alert("first: success");
	});*/
}

function invite_cancel()
{
	$(".givenInvite_divMain").load("invite_cancel.php", {
		invite_playerId: invite_playerId
	});
}

function invite_rejection()
{
	$(".infoOnline_hidden").load("invite_rejection.php");
}

function playerId_copyToClipboard()
{
	if (document.selection) {
	  var range = document.body.createTextRange();
	  range.moveToElementText(document.getElementById("playerId"));
	  range.select().createTextRange();
	  document.execCommand("copy");
	}
	else if (window.getSelection) {
		var range = document.createRange();
		range.selectNode(document.getElementById("playerId"));
		window.getSelection().addRange(range);
		document.execCommand("copy");
	 }
}

function back()
{
	clearInterval(findId_check);
	$(".choice").slideUp(700, function() {
		$(this).html(back_original).slideDown(700);
	});
}

function infoOnline()
{
	if(infoOnline_isset!=null)
		clearTimeout(infoOnline_isset);
	$(".infoOnline").animate({
		opacity: 1
	}, 300).css({
		"z-index": "3",
		"display": "flex"
	}).find(".infoOnline_info");
}

function countdown(myFunction)
{
	console.log("wywołano countdown"); //dlaczego podwójnie??
	if(infoOnline_isset!=null)
		clearTimeout(infoOnline_isset);
	infoOnline_isset=setTimeout(function() {
		myFunction();
	}, 3500);
}

$(document).ready(function() {
	back_original=$(".online_players").find(".choice").html();

	$(".online").on("click", function() {
		$(this).parent().animate({
			opacity: 0
		}, 1000, function() {
			//aktywni gracze, wizualne efekty
			var obiekt=$(this);
			obiekt.css({
				"display": "none"
			});

			//dalsze efekty wizualne
			$(".online_players").css({
				"display": "flex"
			}).animate({
				opacity: 1
			}, 1000);

			//status bycia online
			youActive($(".status_online_info")); //wywołanie natychmiastowe po załadowaniu
			setInterval(function() {
				youActive($(".status_online_info"));
			}, 5000); //usprawnić - kiedy traci połączenie online, natychmiast próbuje je ponownie nawiązać

			$(".infoOnline_info").delay(50, function() {
				$(this).load("playerInvite_givenInvite.html");
			});

			/*$(".find_id").on("click", function() {
				$(".online_players").slideUp(1000, function() {
					find_player();
				});
			});*/

			/*var children=$(".container").find(".online_players_inner");
			//odświeżanie aktywności
			setInterval(function() {
				youActive(children);
				setTimeout(function() {
					var activites=showCookie("youActive"); //odczytanie wartości ciasteczka youActive
					if(typeof(activites)!=="undefined") //ciasteczko istnieje
					{
						deleteCookie("youActive"); //usuń to ciasteczko
						refresh(children); //odświeżanie stanu
					}
				}, 500);
			}, 6500);*/

		});
	});
});
