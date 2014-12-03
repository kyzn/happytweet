var seconds = 59;

function secondPassed() {
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds; 
    }
    document.getElementById('countdown').innerHTML = remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('countdown').innerHTML = ":(";
    } else {    
        seconds--;
    }
}
var countdownTimer = setInterval(secondPassed, 1000);