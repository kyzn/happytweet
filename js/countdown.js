function openResultScreen(){

	document.location.href = "./resultscreen.php";
}

var seconds = 90;

function countdown_timer() {

	var remainingMinute = Math.floor(seconds / 60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds; 
    }
	if (remainingMinute < 10) {
        remainingMinute = "0" + remainingMinute; 
    }
    document.getElementById('play_paragraph').innerHTML = remainingMinute + " : " + remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('play_paragraph').innerHTML = "Time's up!";
		openResultScreen();

    } else {    
        seconds--;
    }
}
var countdownTimer = setInterval(countdown_timer, 1000);