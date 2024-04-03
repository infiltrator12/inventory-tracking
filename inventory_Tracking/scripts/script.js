//IMPORT FUNCTION API
function handleFile() {
    var fileInput = document.getElementById('csvFileInput');
    var file = fileInput.files[0];
    var reader = new FileReader();
    
    reader.onload = function (event) {
        var contents = event.target.result;
        var lines = contents.split('\n');
        var table = document.getElementById('mytable');
        table.innerHTML = '';
        var headerRow = document.createElement('tr');
        headerRow.innerHTML = `
            <th>ID</th>
            <th>Name</th>
            <th>Host</th>
            <th>Provisioned Space</th>
            <th>Used Space</th>
            <th>Operating System</th>
            <th>Memory Size</th>
            <th>IP Address</th>
            <th>Application</th>
            <th>Environment</th>
        `;
        table.appendChild(headerRow);
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
            <span class='edit-icon' onclick='editData(this)'><i class='fa-solid fa-edit'></i></span>
            <span class='delete-icon' onclick='deleteData(this)'><i class='fa-solid fa-trash'></i></span>
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
function sendDataToPHP(lines) {
    var xhr = new XMLHttpRequest();
    var url = '/inventory_tracking/php/vserversdatabase.php?function=save'; 
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            }else{
                console.error('Error sending data to PHP:', xhr.status);
            }
        }   
    };
    
    var jsonData = JSON.stringify({ data: lines }); // Wrapping lines in an object with the 'data' key
    xhr.send(jsonData); // Sending JSON data directly
}
//END OF IMPORT EXPORT OPERATIONS
//Crud operations
//update crud function
function editData(element){
var row = element.parentNode.parentNode;
var id = row.getAttribute("id");
//extract the data from the row
var ID = row.cells[0].innerHTML;
var Name = row.cells[1].innerHTML;
var Host = row.cells[2].innerHTML;
var ProvisionedSpace = row.cells[3].innerHTML;
var UsedSpace = row.cells[4].innerHTML;
var OperatingSystem  = row.cells[5].innerHTML;
var MemorySize = row.cells[6].innerHTML;
var IPAddress = row.cells[7].innerHTML;
var Application = row.cells[8].innerHTML;
var Environment = row.cells[9].innerHTML;
//populate the form with the extracted data
document.getElementById("editID").value = ID;
document.getElementById("editName").value = Name;
document.getElementById("editHost").value = Host;
document.getElementById("editProvisionedspace").value = ProvisionedSpace;
document.getElementById("editUsedspace").value = UsedSpace;
document.getElementById("editos").value = OperatingSystem;
document.getElementById("editMemorysize").value = MemorySize;
document.getElementById("editIPAddress").value = IPAddress;
document.getElementById("editApplication").value = Application;
document.getElementById("editEnvironment").value = Environment;

//Display the edit form
document.getElementById('editFormContainer').style.display= 'block';
}
//Event listner for the editform
document.getElementById("editForm").addEventListener("submit",function(event){
    event.preventDefault();
    updateData();
})
function updateData(){
    //get the data from the form
    var editForm = document.getElementById("editForm");
    var formData = new FormData(editForm);
    // Get the unique identifier (ID) of the row
    var id = document.getElementById("editID").value;
    // Append the ID to the form data
    formData.append("id", id);

    //make an ajax request to update the data
    var xhr =new XMLHttpRequest();
    xhr.open("POST","/inventory_Tracking/php/vserversdatabase.php?function=update",true);
    xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){
            console.log(xhr.responseText); 
            location.reload();
        }else{
            console.error("error"+xhr.status)
        }
    };
    xhr.send(new URLSearchParams(formData));
}
//DELETE CRUD FUNCTION
function deleteData(element){
    var row = element.parentNode.parentNode;
    var id = row.getAttribute("ID");
    //extract data from the selected row
    var Name = row.cells[1].innerHTML;
    var Host = row.cells[2].innerHTML;
    //display confirmation message
    var confirmation = confirm("Are you sure you want to delete the record?");

    if(confirmation){
        //make an ajax request to delete the data
        var xhr = new XMLHttpRequest();
        xhr.open("POST","/inventory_Tracking/php/vserversdatabase.php?function=delete",true);
        xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
                location.reload();
            }
        };
        //send data to the server
        xhr.send("ID=" + encodeURIComponent(id) + "&name="+encodeURIComponent(Name)+"&host="+encodeURIComponent(Host));
    }
}
//data display searchbar
$(document).ready(function() {
    $("#searchForm").submit(function(event) {
        event.preventDefault();
        var searchTerm = $('#searchInput').val();
        searchData(searchTerm);
    });

    function searchData(searchTerm) {
        $.ajax({
            url: '/inventory_tracking/php/vserversdatabase.php?function=search&q=' +
                searchTerm,
            method: 'POST',
            datatype: 'json',
            success: function(response) {
                displaySearchResults(response);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    }

    function displaySearchResults(servers) {
        var tableBody = document.querySelector("#mytable tbody");
        console.log(servers);
        tableBody.innerHTML = "";

        if (servers.length > 0) {
            for (var i = 0; i < servers.length; i++) {
                var server = servers[i];

                tableBody.innerHTML += `
                <tr>
                <td> ${server.id} </td>
                <td> ${server.name} </td>
                <td> ${server.host} </td>
                <td> ${server.provisionedspace} </td>
                <td> ${server.usedspace} </td>
                <td> ${server.operatingsystem} </td>
                <td> ${server.memorysize} </td>
                <td> ${server.ipaddress} </td>
                <td> ${server.application} </td>
                <td> ${server.environment} </td>
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
        } else {
            var newrow = document.createElement("tr");
            newrow.innerHTML = '<td colspan="10">NO RESULTS FOUND</td>';
            tableBody.appendChild(newrow);
        }
    }
});
