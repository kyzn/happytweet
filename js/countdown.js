var max_time = 59;
var cinterval;
 
function countdown_timer(){
  // decrease timer
  max_time--;
  document.getElementById('play_paragraph').innerHTML = "00:" +  ((max_time < 10) ? '0' : '') + max_time;
  if(max_time == 0){
    openResultScreen();

  }
}
// 1,000 means 1 second.
cinterval = setInterval('countdown_timer()', 1000);

function openResultScreen(){

	document.location.href = "file:///home/nefise/Downloads/happytweet.webflow%20(2)/resultscreen.html";
}
