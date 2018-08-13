var popup = document.getElementById("popup");


for(i = 0; i<5; i++){
    setTimeout(function(){
        popup.parentNode.removeChild(popup);
    }, 2000);
}


