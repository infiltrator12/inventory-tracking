//data display searchbar
$(document).ready(function(){
    $("#searchForm").submit(function(event){
        event.preventDefault();
        var searchTerm = $('#searchInput').val();
        searchData(searchTerm);
    });

    function searchData(searchTerm){
        $.ajax({
            url: '/inventory_Tracking/php/pserversdatabase.php?function=search&q='+searchTerm,
            method: 'POST',
            datatype: 'json',
            success: function(response){
                displaySearchResults(response);
            },
            error: function(xhr, status, error){
                console.log('Error: ' +error);
            }
        });
    }

    function displaySearchResults(servers){
        var tableBody = document.querySelector("#mytable tbody");
        console.log(servers);
        tableBody.innerHTML = "";

        if(servers.length > 0){
            for(var i = 0; i < servers.length; i++){
                var server = servers[i];

                tableBody.innerHTML += `
                <tr>
                <td> ${server.assettag} </td>
                <td> ${server.model} </td>
                <td> ${server.make} </td>
                <td> ${server.serialnumber} </td>
                <td> ${server.host} </td>
                <td> ${server.ipaddress} </td>
                <td>
                <span class='edit-icon' onclick='editData(this)'>
                  <i class='fa-solid fa-edit'></i>
                </span>
                <span class='delete-icon' onclick='deleteData(this)'>
                  <i class='fa-solid fa-trash'></i>
                </span>
              </td>
              </tr>
                `
            }
        }else{
            var newrow = document.createElement("tr");
            newrow.innerHTML = '<td colspan="7">NO RESULTS FOUND</td>';
            tableBody.appendChild(newrow);
        }
    }
});
//crud functionalities
function upload() {
    var fileInput = document.getElementById('csvFileInput');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onload = function (event) {
      var contents = event.target.result;
      var lines = contents.split('\n');
      var table = document.getElementById('mytable');
  
      for (var i = 0; i < lines.length; i++) {
        var columns = lines[i].split(',');
        var row = document.createElement('tr');
  
        for (var j = 0; j < columns.length; j++) {
          var columnData = columns[j].trim();
          var cell = document.createElement('td');
          cell.textContent = columnData;
          row.appendChild(cell);
        }
  
        var actionsCell = document.createElement('td');
        actionsCell.innerHTML = `
          <span class='edit-icon' onclick='edit(this)'><i class='fa-solid fa-edit'></i></span><br>
          <span class='delete-icon' onclick='deleted(this)'><i class='fa-solid fa-trash'></i></span>
        `;
        row.appendChild(actionsCell);
  
        table.appendChild(row);
      }
  
      sendDataToPHP(lines);
    };
  
    reader.onerror = function (event) {
      console.error('Error reading file:', event.target.error);
    };
  
    reader.readAsText(file);
}
function sendDataToPHP(lines){
    var jsonData = JSON.stringify({data: lines});
    $.post("/inventory_Tracking/php/pserversdatabase.php?function=save", {data: lines});
}
function deleteData(element){
    var row = element.parentNode.parentNode;
    var assettag = row.cells[0].innerHTML;
    var model = row.cells[1].innerHTML;
    var serialnumber = row.cells[3].innerHTML;
    
    var confirmation = confirm("Are you sure you want to delete this record?");
    if(confirmation){
        //make an ajax request
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/inventory_Tracking/php/pserversdatabase.php?function=delete", true);
        xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
                location.reload();
            }
        };
        xhr.send("assettag=" + encodeURIComponent(assettag) +
                "&model=" + encodeURIComponent(model) +
                "&serialnumber=" + encodeURIComponent(serialnumber));
    }
}
function editData(element){
    var row = element.parentNode.parentNode;
    var serialnumber = row.getAttribute("SERIAL NUMBER");
    //extract data from the row
    var serialnumber = row.cells[0].innerHTML;
    var assettag = row.cells[1].innerHTML;
    var model = row.cells[2].innerHTML;
    var make = row.cells[3].innerHTML;
    var host = row.cells[4].innerHTML;
    var ipaddress = row.cells[5].innerHTML;
    //populate the form with the extracted data
    document.getElementById("editserialnumber").value = serialnumber;
    document.getElementById("editassettag").value = assettag;
    document.getElementById("editmodel").value = model;
    document.getElementById("editmake").value = make;
    document.getElementById("edithost").value = host;
    document.getElementById("editipaddress").value = ipaddress;
    //display the edit form on the right side
    openForm();
}
function openForm(){
    var formContainer = document.getElementById("editFormContainer");
    formContainer.style.display = "block";
}
document.getElementById("editForm").addEventListener("submit", function(event){
    event.preventDefault();
    updateData();
})
function updateData(){
    //get the data from the form
    var editForm = document.getElementById("editForm");
    var formData = new FormData(editForm);
    //get the unique iddentifier
    var assettag =  document.getElementById("editassettag").value;
    //append to the formData
    formData.append("assettag", assettag);

    //make an ajax request to update the data
    var xhr = new XMLHttpRequest();
    xhr.open("POST","/inventory_Tracking/php/pserversdatabase.php?function=update", true);
    xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){
            console.log(xhr.responseText);
            location.reload();
        }else{
            console.log("Request in progress...");
        }
    };
    xhr.send(new URLSearchParams(formData));
}