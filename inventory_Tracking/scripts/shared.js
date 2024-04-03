// sidenav functions
function opens() {
    document.getElementById("Sidenav").style.width = "250px";
    document.getElementById("Sidenav").style.display = "block";
    document.getElementById("main").style.marginLeft = "250px";
}
function closes() {
    document.getElementById("Sidenav").style.width = "0";
    document.getElementById("Sidenav").style.display = "none";
    document.getElementById("main").style.marginLeft = "0";
}
//sidenav dropdown
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;
for (i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}
//lightmode and darkmode toggle
function toggleMode() {
    var icon = document.getElementById("icon");
    var toggleSwitch = document.getElementById("modeToggle");
    var body = document.body;
    if (toggleSwitch.checked) {
        icon.classList.remove("light-mode-icon");
        icon.classList.add("dark-mode-icon");
        body.classList.remove("light-mode");
        body.classList.add("dark-mode");
    } else {
        icon.classList.remove("dark-mode-icon");
        icon.classList.add("light-mode-icon");
        body.classList.remove("dark-mode");
        body.classList.add("light-mode");
    }   
}
//export Function
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);        
    csv.push(row.join(","));
    }
    var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
    var downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
}
/*form-adddevices opening*/
function openModal(){
    var modal = document.getElementById("id01");
    modal.style.display="block";
    // modal.style.marginRight="150px";
}
function closeModal(){
    var modal = document.getElementById("id01");
    modal.style.display= "none";
}
/*This is for edit function*/
window.onclick = function(event) {  
    var modal2 = document.getElementById("editFormContainer");
    if (event.target == modal2) {
      modal2.style.display = "none";
    }
    var modal = document.getElementById("id01");
    if(event.target == modal){
        modal.style.display = "none";
    }
}
//logout function
function logout(){
    document.getElementById("out").addEventListener("click", function(){
        //send an ajax request to the logout script 
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "http://localhost/inventory_Tracking/php/logout.php", true);
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
                //on successful logout redirect to login.html
                window.location.href = 'http://localhost/inventory_Tracking/login.html';
            }else{
                //handle any errors that occured during logout,if necessary
                console.error("Logout Failed: ", xhr.status, xhr.responseText);
            }
        };
        xhr.send();
    })
}