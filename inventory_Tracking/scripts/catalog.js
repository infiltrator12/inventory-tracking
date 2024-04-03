function upload() {
    var fileInput = document.getElementById('csvFileInput');
    var file = fileInput.files[0];
    var reader = new FileReader();

    reader.onload = function(event) {
        var contents = event.target.result;
        var lines = contents.split('\n');
        var table = document.getElementById('mytable');
        table.innerHTML = '';
        var headerRow = document.createElement('tr');
        headerRow.innerHTML = `
                    <th>serialnumber</th>
                    <th>asset_tag</th>
                    <th>model</th>
                    <th>storage_capacity</th>
                    <th>storage_type</th>
                    <th>ram_capacity</th>
                    <th>employeename</th>
                    <th>employeenumber</th>
                    <th>department</th>
                    <th>dateissued</th>
                    <th>device_condition</th>
                    <th>allocation</th>
                    <th>description</th>
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
                    <span class='delete-icon' onclick='deleteData(this)'>  <i class='fa-solid fa-trash'></i></span>
                    `;
            row.appendChild(actionsCell);
            table.appendChild(row);
        }
        sendDataToPHP(lines);
    };

    reader.onerror = function(event) {
        console.error('Error reading file:', event.target.error);
    };

    reader.readAsText(file);
}

function sendDataToPHP(lines) {
    var xhr = new XMLHttpRequest();
    var url = '/inventory_Tracking/php/catalogdatabase.php?function=save';
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error('Error sending data to PHP:', xhr.status);
            }
        }
    };
    var jsonData = JSON.stringify({
        data: lines
    }); // Wrapping lines in an object with the 'data' key
    xhr.send(jsonData); // Sending JSON data directly
}
function deleteData(element){
    var row = element.parentNode.parentNode;
    var serialnumber = row.cells[0].innerHTML;

    var assettag = row.cells[1].innerHTML;
    //display confirmation message
    var confirmation = confirm("Are you sure you want to permanently delete this record? ");

    if(confirmation){
        //make an ajax request to handle deletion
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/inventory_Tracking/php/catalogdatabase.php?function=delete", true);
        xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
                location.reload();
            }
        }
        //send data to the server
        xhr.send("serialnumber=+" +encodeURIComponent(serialnumber) + "&assettag=" +encodeURIComponent(assettag));
    }
}
function editData(element){
    var row = element.parentNode.parentNode;
    var serialnumber = row.getAttribute("SERIALNUMBER");
    //extract the data from the row
    var assettag = row.cells[1].innerHTML;
    var model = row.cells[2].innerHTML;
    var storagecapacity = row.cells[3].innerHTML;
    var storagetype = row.cells[4].innerHTML;
    var ramcapacity = row.cells[5].innerHTML;
    var employeename = row.cells[6].innerHTML;
    var employeenumber = row.cells[7].innerHTML;
    var department = row.cells[8].innerHTML;
    var dateissued = row.cells[9].innerHTML;
    var devicecondition = row.cells[10].innerHTML;
    var allocation = row.cells[11].innerHTML;
    var description = row.cells[12].innerHTML;
    //populate the form with the extracted data
    document.getElementById("editserialnumber").value = serialnumber;
    document.getElementById("editassettag").value = assettag;
    document.getElementById("editmodel").value = model;
    document.getElementById("editstoragecapacity").value = storagecapacity;
    document.getElementById("editstoragetype").value = storagetype;
    document.getElementById("editramcapacity").value = ramcapacity;
    document.getElementById("editemployeename").value = employeename;
    document.getElementById("editemployeenumber").value = employeenumber;
    document.getElementById("editdepartment").value = department;
    document.getElementById("editdateissued").value = dateissued;
    document.getElementById("editdevicecondition").value = devicecondition;
    document.getElementById("editallocation").value = allocation;
    document.getElementById("editdescription").value = description;
    //display the form
    openForm();
}
function openForm(){
    var formContainer = document.getElementById("editFormContainer");
    formContainer.style.display = "block";
}
//Event listner for the edit form
document.getElementById("editForm").addEventListener("submit", function(event){
    event.preventDefault();
    update();
})
function update(){
    //get the data from the form
    var editForm = document.getElementById("editForm");
    var formData = new FormData(editForm);
    //get the unique iddentifier of the row
    var serialnumber = document.getElementById("editserialnumber").value;
    formData.append("serialnumber", serialnumber);
    //make an ajax request to update the data
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/inventory_Tracking/php/catalogdatabase.php?function=update", true);
    xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){
            console.log(xhr.responseText);
            location.reload();
        }else{
            console.log("Request in Progress...");
        }
    };
    xhr.send(new URLSearchParams(formData));
}
//searchbarfunction
$(document).ready(function(){
    $("#searchForm").submit(function(event){
        event.preventDefault();
        var searchTerm = $('#searchInput').val();
        searchData(searchTerm);
    });

    function searchData(searchTerm){
        $.ajax({
            url: '/inventory_Tracking/php/catalogdatabase.php?function=search&q='+searchTerm,
            method: 'POST',
            datatype: 'json',
            success: function(response){
                displaySearchResults(response);
            },
            error: function(xhr, status, error){
                console.error("Error: " +error);
            }
        });
    }

    function displaySearchResults(catalogs){
        var tableBody = document.querySelector('#mytable tbody');
        console.log(catalogs);
        tableBody.innerHTML = "";

        if(catalogs.length > 0){
            for(var i = 0; i < catalogs.length; i++){
                var catalog = catalogs[i];

                tableBody.innerHTML += `
                <tr>
                <td> ${catalog.serialnumber} </td>
                <td> ${catalog.assettag} </td>
                <td> ${catalog.model} </td>
                <td> ${catalog.storagecapacity} </td>
                <td> ${catalog.storagetype} </td>
                <td> ${catalog.ramcapacity} </td>
                <td> ${catalog.employeename} </td>
                <td> ${catalog.employeenumber} </td>
                <td> ${catalog.department} </td>
                <td> ${catalog.dateissued} </td>
                <td> ${catalog.devicecondition} </td>
                <td> ${catalog.allocation} </td>
                <td> ${catalog.description} </td>
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
            newrow.innerHTML = '<td colspan="14">NO RESULTS FOUND</td>';
            tableBody.appendChild(newrow);
        }
    }
});