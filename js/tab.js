function selectQuestion(element, text)  {

    var tabContents = document.getElementsByClassName('tabQuestionNumber');

    for (var i = 0; i < tabContents.length; i++) { 
        tabContents[i].style.backgroundColor = "initial";
    }

    element.style.backgroundColor = "#252A2F";

    var paragraph = document.getElementById("paragraph");
    paragraph.innerHTML = text;
}

function selectRating(element)  {

    var tabContents = document.getElementsByClassName('tabRating');
    for (var i = 0; i < tabContents.length; i++) { 
        tabContents[i].style.border = "initial";
    }

    element.style.border = "thin solid green";
}
