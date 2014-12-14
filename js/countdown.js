function openResultScreen(){

	document.location.href = "./resultscreen.php";
}

var seconds = 59;

function countdown_timer() {
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds; 
    }
    document.getElementById('play_paragraph').innerHTML = remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('play_paragraph').innerHTML = "Time's up!";
		openResultScreen();

    } else {    
        seconds--;
    }
}
var countdownTimer = setInterval(countdown_timer, 1000);