//data display searchbar
$(document).ready(function(){
    $("#searchForm").submit(function(event){
        event.preventDefault();
        var searchTerm = $('#searchInput').val();
        searchData(searchTerm);
    });

    function searchData(searchTerm){
        $.ajax({
            url: '/inventory_Tracking/php/allocationsdatabase.php?function=search&q='+searchTerm,
            method: 'POST',
            datatype: 'json',
            success: function(response){
                displaySearchResults(response);
            },
            error: function(xhr, status, error){
                console.error('Error: ' +error);
            }
        });
    }

    function displaySearchResults(device){
        var tableBody = document.querySelector("#mytable tbody");
        console.log(device);
        tableBody.innerHTML = "";//clears the existing table rows

        if(device.length > 0){
            for(var i = 0; i< device.length; i++){
                var devices = device[i];

                tableBody.innerHTML +=`
                    <tr>
                    <td> ${devices.serialnumber} </td>
                    <td> ${devices.assettag} </td>
                    <td> ${devices.employeenumber} </td>
                    <td> ${devices.employeename} </td>
                    <td> ${devices.allocationdate} </td>
                    <td> ${devices.deallocationdate} </td>
                    <td>
                  <span class='edit-icon' onclick='editData(this)'>
                    <i class='fa-solid fa-edit'></i>
                  </span>
                  <span class='delete-icon' onclick='deleteData(this)'>
                    <i class='fa-solid fa-trash'></i>
                  </span>
                </td>
                </tr>`;
            }
        }else{
            var newrow = document.createElement("tr");
            newrow.innerHTML = '<td colspan="7">NO RESULTS FOUND</td>';
            tableBody.appendChild(newrow);
        }
    }
});
function deleteData(element){
    var row = element.parentNode.parentNode;
    var serialnumber = row.cells[0].innerHTML;

    var assettag = row.cells[1].innerHTML;
    //display confirmation message
    var confirmation = confirm("Are you sure you want to permanently delete this record? ");

    if(confirmation){
        //make an ajax request to handle deletion
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/inventory_Tracking/php/allocationsdatabase.php?function=delete", true);
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
    var serialnumber = row.cells[0].innerHTML;
    var assettag = row.cells[1].innerHTML;
    var employeenumber = row.cells[2].innerHTML;
    var employeename = row.cells[3].innerHTML;
    var allocationdate = row.cells[4].innerHTML;
    var deallocationdate = row.cells[5].innerHTML;
    //populate the form with the extracted data
    document.getElementById("editserialnumber").value = serialnumber;
    document.getElementById("editassettag").value = assettag;
    document.getElementById("editemployeenumber").value = employeenumber;
    document.getElementById("editemployeename").value = employeename;
    document.getElementById("editallocationdate").value = allocationdate;
    document.getElementById("editdeallocationdate").value = deallocationdate;
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
    xhr.open("POST", "/inventory_Tracking/php/allocationsdatabase.php?function=update", true);
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