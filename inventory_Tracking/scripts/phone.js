//searchbar function
$(document).ready(function(){
    $('#searchForm').submit(function(event){
        event.preventdefault();
        var searchTerm = $('#searchInput').val();
        searchPhone(searchTerm);
    });

    function searchPhone(searchTerm){
        $.ajax({
            url: '/inventory_Tracking/php/phonesdatabse.php?function=search&q='+searchTerm,
            method: 'POST',
            datatype: 'json',
            success: function(response){
                displaySearchResult(response);
            },
            error: function(xhr, status, error){
                console.error('Error: ' +error);
            }
        });
    }

    function displaySearchResult(phones){
        var tableBody = document.querySelector("#mytable tbody");
        console.log(phones);
        tableBody.innerHTML = "";

        if(phones.length > 0){
            for(var i = 0; i < phones.length; i++){
                var phone = phones[i];

                tableBody.innerHTML += `
                <tr>
                <td> ${phone.assettag} </td>
                <td> ${phone.extension} </td>
                <td> ${phone.macaddress} </td>
                <td> ${phone.employeenumber} </td>
                <td> ${phone.employeename} </td>
                <td> ${phone.department} </td>
                <td>
                  <span class='edit-icon' onclick='edit(this)'>
                    <i class='fa-solid fa-edit'></i>
                  </span>
                  <span class='delete-icon' onclick='deleted(this)'>
                    <i class='fa-solid fa-trash'></i>
                  </span>
                </td>
                </tr>`;
            }
        }else{
            var newrow = doument.createElement("tr");
            newrow.innerHTML = '<td colspan = "7">No Results Found </td>';
            tableBody.appendChild(newrow);
        }
    }
});
//upload function
function handle() {
    var fileInput = document.getElementById('csvFileInput');
    var file = fileInput.files[0];
    var reader = new FileReader();
    
    reader.onload = function (event) {
        var contents = event.target.result;
        var lines = contents.split('\n');
        //var table = document.getElementById('mytable');
        var table = document.createElement('table');
        table.innerHTML = '';
        var headerRow = document.createElement('tr');
        headerRow.innerHTML = `
            <th>Asset Tag</th>
            <th>Extension</th>
            <th>Mac Address</th>
            <th>model</th>
            <th>Employee Number</th>
            <th>Employee Name</th>
            <th>Department</th>
        `;
        table.appendChild(headerRow);
        document.body.append(table);
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
            <span class='delete-icon' onclick='deleted(this)'>  <i class='fa-solid fa-trash'></i></span>
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
    var jsonData = JSON.stringify({ data: lines}); // Wrapping lines in an object with the 'data' key
    $.post("/inventory_Tracking/php/phonesdatabse.php?function=save",{data:lines});
}
//delete function
function deleteData(element){
    var row = element.parentNode.parentNode;
    var assettag = row.cells[0].innerHTML;
    //extract data from the selected row
    var extension = row.cells[1].innerHTML;
    var macaddress = row.cells[2].innerHTML;
    //display confirmation message
    var confirmation = confirm("Are you sure you want to delete this record?");

    if(confirmation){
        //make an ajax request to delete the data
        var xhr = new XMLHttpRequest();
        xhr.open("POST","/inventory_Tracking/php/phonesdatabse.php?function=delete",true);
        xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
                location.reload();//reload page after deletion. 
            }
        };
        //send data to the server
        xhr.send("assettag=" + encodeURIComponent(assettag) +
                 "&extension="+encodeURIComponent(extension)+ 
                 "&macaddress="+encodeURIComponent(macaddress));
    }
}
//edit function
function editData(element){
    var row = element.parentNode.parentNode;
    var assettag = row.getAttribute("ASSET TAG");
    //extract the data from the row
    var assettag = row.cells[0].innerHTML;
    var extension = row.cells[1].innerHTML;
    var macaddress = row.cells[2].innerHTML;
    var model = row.cells[3].innerHTML;
    var employeenumber = row.cells[4].innerHTML;
    var employeename = row.cells[5].innerHTML;
    var department = row.cells[6].innerHTML;
    //populate the form with the extracted data
    document.getElementById("editassettag").value = assettag;
    document.getElementById("editextension").value = extension;
    document.getElementById("editmacaddress").value = macaddress;
    document.getElementById("editmodel").value = model;
    document.getElementById("editemployeenumber").value = employeenumber;
    document.getElementById("editemployeename").value = employeename;
    document.getElementById("editdepartment").value = department;
    //Display the edit form on the right side
    openForm();
}
function openForm(){
    var formContainer = document.getElementById("editFormContainer");
    formContainer.style.display = "block";

    // document.body.style.marginRight = "0";
}
// Event listner for the editform
document.getElementById("editForm").addEventListener("submit",function(event){
   event.preventDefault();
   updateData();
})  
function updateData(){
    //get the data from the form
    var editForm = document.getElementById("editForm");
    var formData = new FormData(editForm);
    // Get the unique identifier (ID) of the row
    var assettag = document.getElementById("editassettag").value;
    // Append the ID to the form data
    formData.append("assettag", assettag);

    //make an ajax request to update the data
    var xhr =new XMLHttpRequest();
    xhr.open("POST","/inventory_Tracking/php/usersdatabse.php?function=update",true);
    xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            console.log(xhr.responseText);
            location.reload();
          } else {
            console.error("Error: " + xhr.status + " " + xhr.statusText);
          }
        } else {
          console.log("Request in progress...");
        }
      };      
    xhr.send(new URLSearchParams(formData));
}