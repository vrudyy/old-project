
var n = "not";

var bNew = document.getElementById("new-button");
var bMenu = document.getElementById("button-menu");
var body = document.getElementsByTagName("BODY")[0];
var uploadFileWrapper = document.getElementById("in-upload-file-wrapper");
var uploadFile = document.getElementById("in-upload-file");
var uploadFolderWrapper = document.getElementById("in-upload-folder-wrapper");
var uploadFolder = document.getElementById("in-upload-folder");
var newFolderButton = document.getElementById("newFolderButton");
var newFileButton = document.getElementById("newFileButton");

//console.log(newFileButton);
//console.log(newFolderButton);

newFileButton.addEventListener("click", function(e){
    e.preventDefault();
    uploadFileWrapper.style.display = "initial";
});

newFolderButton.addEventListener("click", function(e){
    e.preventDefault();
    uploadFolderWrapper.style.display = "initial";
});

uploadFileWrapper.style.display = "none";
uploadFolderWrapper.style.display = "none";



uploadFileWrapper.addEventListener("click", function(e){
    uploadFileWrapper.style.display = "none";
});

uploadFolderWrapper.addEventListener("click", function(e){
    uploadFolderWrapper.style.display = "none";
});

uploadFile.addEventListener("click", function(e){
    e.stopPropagation();
});
uploadFolder.addEventListener("click", function(e){
    e.stopPropagation();
});




body.addEventListener("click", function(e){
    if(n === "yes"){
        bMenu.style.display = "none";
        n = "not";
    }
});

bNew.addEventListener("click", function(e){
    e.preventDefault();
    e.stopPropagation();
    if(n === "not"){
        bMenu.style.display = "initial";
        n = "yes";
    }else{
        bMenu.style.display = "none";
        n = "not";
    }
});


$filesCol = document.getElementsByClassName("in-drive-nf-file");
$files = Array.prototype.slice.call($filesCol);
for(i = 0; i<$files.length; i++){
    $files[i].addEventListener("drag", function(e){
       e.stopPropagation();
       e.dataTransfer.effectAllowed = "copyMove";
       $element = e.target;
       while($files.indexOf($element) === -1){
           $element = $element.parentElement;
       }
    });
} 