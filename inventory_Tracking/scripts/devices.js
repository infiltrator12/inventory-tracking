// script2.js devices
function editD(element) {
    var row = element.parentNode.parentNode;
    var assetTag = row.getAttribute("asset tag")
    //extract data from the row
    var assetTag = row.cells[0].innerHTML;
    var model = row.cells[1].innerHTML;
    var make = row.cells[2].innerHTML;
    var type = row.cells[3].innerHTML;
    var ipAddress = row.cells[4].innerHTML;

    // Populate the edit form with the extracted data
    document.getElementById("editassettag").value = assetTag;
    document.getElementById("editmodel").value = model;
    document.getElementById("editmake").value = make;
    document.getElementById("edittype").value = type;
    document.getElementById("editip_address").value = ipAddress;

    // Display the edit form
    document.getElementById("editFormContainer1").style.display = "block";
}

document.getElementById("editForm").addEventListener("submit", function(event) {
    event.preventDefault();
    updateData();
});

function updateData() {
    //get the data from the form
    var editForm = document.getElementById("editForm");
    var formData = new FormData(editForm);
    //get the unique iddentifier(ID) of the row
    var assettag = document.getElementById("editassettag").value;
    //Append the assettag to the form data
    formData.append("editassettag",assettag);

    // Make an AJAX request to update the data
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/inventory_Tracking/php/devicesdatabase.php?function=update", true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
            location.reload(); // Reload the page after successful update
        } else {
            console.error("Error: " + xhr.status);
        }
    };
    xhr.send(formData);
}
// Delete a device
function deleteD(element) {
    var row = element.parentNode.parentNode;
    var assetTag = row.cells[0].innerHTML;

    // Confirm deletion with the user
    var confirmDelete = confirm("Are you sure you want to delete this record?");
    if (confirmDelete) {
        // Make an AJAX request to delete the record
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/inventory_Tracking/php/devicesdatabase.php?function=delete", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
              if( xhr.status === 200){
                // Successful deletion, remove the row from the table
                row.parentNode.removeChild(row);
                console.log(xhr.responseText);
              }
            } else {
                console.error("Error: " + xhr.status);
            }
        };
        xhr.send("assetTag=" + encodeURIComponent(assetTag));
    }
}
//data display searchbar function for parsing certain data
$(document).ready(function() {
    $('#searchForm').submit(function(event) {
      event.preventDefault();
      var searchTerm = $('#searchInput').val();
      searchDevices(searchTerm);
    });
  
    function searchDevices(searchTerm) {
      $.ajax({
        url: '/inventory_Tracking/php/devicesdatabase.php?function=search&q=' + searchTerm,
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log(response);
          displaySearchResults(response);
        },
        error: function(xhr, status, error) {
          console.error('Error: ' + error);
        }
      });
    }
  
    function displaySearchResults(devices) {
        var tableBody = document.getElementById("mytable").getElementsByTagName("tbody")[0];
        tableBody.innerHTML = "";
      
        if (devices.length > 0) {
          for (var i = 0; i < devices.length; i++) {
            var device = devices[i];
      
            var newRow = document.createElement("tr");
            newRow.innerHTML =
              '<td>' + device.asset_tag + '</td>' +
              '<td>' + device.model + '</td>' +
              '<td>' + device.make + '</td>' +
              '<td>' + device.type + '</td>' +
              '<td>' + device.ip_address + '</td>' +
              "<td><span class='edit-icon' onclick='edit(this)'><i class='fa-solid fa-edit'></i></span></td><span class='delete-icon' onclick='deleted(this)'><i class='fa-solid fa-trash'></i></span></td>";
      
            tableBody.appendChild(newRow);
          }
        } else {
          var newRow = document.createElement("tr");
          newRow.innerHTML = '<td colspan="6">No results found.</td>';
          tableBody.appendChild(newRow);
        }
    } 
});
//import data function
function handleFile() {
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
          <th>Model</th>
          <th>Make</th>
          <th>Type</th>
          <th>IP Address</th>
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
          <span class='edit-icon' onclick='editD(this)'><i class='fa-solid fa-edit'></i></span><br>
          <span class='delete-icon' onclick='deleteD(this)'>  <i class='fa-solid fa-trash'></i></span>
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
  $.post("/inventory_Tracking/php/devicesdatabase.php?function=save",{data:lines});

} 