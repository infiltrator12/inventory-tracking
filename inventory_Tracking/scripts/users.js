// users.php functions
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
        // headerRow.innerHTML = `
        //     <th>EmployeeNumber</th>
        //     <th>EmployeeSurname</th>
        //     <th>EmployeeFirstName</th>
        //     <th>Group</th>
        //     <th>Department</th>
        // `;
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
    $.post("/inventory_Tracking/php/usersdatabase.php?function=save",{data:lines});
    console.log(data);

}
//delete function not qorking as it should
function deleted(element){
    var row = element.parentNode.parentNode;
    var employeenumber = row.cells[0].innerHTML;
    //extract data from the selected row
    var employeesurname = row.cells[1].innerHTML;
    var employeefirstname = row.cells[2].innerHTML;
    //display confirmation message
    var confirmation = confirm("Are you sure you want to delete this record?");

    if(confirmation){
        //make an ajax request to delete the data
        var xhr = new XMLHttpRequest();
        xhr.open("POST","/inventory_Tracking/php/usersdatabase.php?function=delete",true);
        xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
                location.reload();//reload page after deletion. 
            }
        };
        //send data to the server
        xhr.send("employeenumber=" + encodeURIComponent(employeenumber) +
                 "&employeesurname="+encodeURIComponent(employeesurname)+ 
                 "&employeefirstname="+encodeURIComponent(employeefirstname));
    }
}
function edit(element){
    var row = element.parentNode.parentNode;
    var id = row.getAttribute("EmployeeNumber");
    //extract the data from the row
    var employeenumber = row.cells[0].innerHTML;
    var employeesurname = row.cells[1].innerHTML;
    var employeefirstname = row.cells[2].innerHTML;
    var groups = row.cells[3].innerHTML;
    var department = row.cells[4].innerHTML;
    //populate the form with the extracted data
    document.getElementById("editemployeenumber").value = employeenumber;
    document.getElementById("editemployeesurname").value = employeesurname
    document.getElementById("editemployeefirstname").value = employeefirstname;
    document.getElementById("editgroups").value = groups;
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
    var employeenumber = document.getElementById("editemployeenumber").value;
    // Append the ID to the form data
    formData.append("employeenumber", employeenumber);

    //make an ajax request to update the data
    var xhr =new XMLHttpRequest();
    xhr.open("POST","/inventory_Tracking/php/usersdatabase.php?function=update",true);
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
// data display searchbar function 
$(document).ready(function(){
    $("#searchForm").submit(function(event) {
        event.preventDefault();
        var searchTerm = $('#searchInput').val();
        searchUser(searchTerm);
    });

    function searchUser(searchTerm){
        $.ajax({
            url: '/inventory_Tracking/php/usersdatabase.php?function=search&q='+searchTerm,
            method: 'POST',
            dataType: 'json',
            success: function(response){    
                displaySearchResults(response);
            },
            error: function(xhr, status, error){
                console.error('Error: ' +error);
            }
        });
    }
    function displaySearchResults(users) {
        var tableBody = document.querySelector("#mytable tbody");
        console.log(users);
        tableBody.innerHTML = ""; // Clear the existing table rows
      
        if (users.length > 0) {
          for (var i = 0; i < users.length; i++) {
            var user = users[i];
      
            tableBody.innerHTML += `
              <tr>
                <td>${user.employeenumber}</td>
                <td>${user.employeesurname}</td>
                <td>${user.employeefirstname}</td>
                <td>${user.groups}</td>
                <td>${user.department}</td>
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
        } else {
          var newrow = document.createElement("tr");
          newrow.innerHTML = '<td colspan="6">No Results Found</td>';
          tableBody.appendChild(newrow);
        }
      }
});