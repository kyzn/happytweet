 var emoLink ,strLink, emoChanged = false, strChanged = false;
 var tweetIndex = 0;
 var tweets;
 
 
 function getTweets(tweetArray){

	tweets = tweetArray;
	changeTweet();
	createSpan();
}
//selected word is added to the strLink 
function setString(str, element){

	var word = document.getElementById(element.id);
	word.style.color = "blue";
	
	strLink = "str="+str;
	strChanged = true;
	if (emoChanged) httpGet();

}
//words become clickable 
function createSpan()
{
	var myDiv = document.getElementById('tweetWords');	
	var res = tweets[tweetIndex].split(" ");
	
	while (myDiv.hasChildNodes()){
		myDiv.removeChild(myDiv.lastChild);
	}
	
	for (i = 0; i < res.length; i++) { 
	
		var spanTag = document.createElement("span");
		spanTag.id = i;
		spanTag.className ="dynamicSpan";
		spanTag.innerHTML = res[i] + " ";	
		spanTag.onclick =  function () {
			setString(this.innerHTML, this);
		};	
		myDiv.appendChild(spanTag);
	}
	tweetIndex++;
}

//we show next tweet						
function changeTweet(){

	var paragraph = document.getElementById("paragraph");
	paragraph.innerHTML = tweets[tweetIndex];	
}

//selected emotion is added to the emoLink 
function setEmotion(str, element){

    var tabContents = document.getElementsByClassName('tabRating');
    for (var i = 0; i < tabContents.length; i++) { 
        tabContents[i].style.border = "initial";
    }
    element.style.border = "thin solid green";
	
	emoLink = "emo="+str;
	emoChanged = true;
	if (strChanged) httpGet();

}

function resetEmotion(){

    var tabContents = document.getElementsByClassName('tabRating');
    for (var i = 0; i < tabContents.length; i++) { 
        tabContents[i].style.border = "initial";
    }
}

function resetString(){

    var words = document.getElementsByClassName('words');
    for (var i = 0; i < words.length; i++) { 
        words[i].style.color = "initial";
    }
}


//send selected rating and word with url. This function runs for each tweet
function httpGet()
{

	var theUrl = 'keepUserChoices.php?'+emoLink+'&'+strLink + '&' + "tweetIndex=" + tweetIndex + '&' + "time=" + document.getElementById("play_paragraph").innerHTML;
    var xmlHttp = null;

    console.log(theUrl);
    
    xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false );
    xmlHttp.send( null );
	
	if(tweetIndex <= 10)
	{	
		emoChanged = false;
		strChanged = false;
		resetEmotion();
		resetString();
		
		if(tweetIndex < 10){
			changeTweet();
			createSpan();
		}
		else if(tweetIndex == 10){
			document.location.href = "./resultscreen.php";
		}

	}
}
