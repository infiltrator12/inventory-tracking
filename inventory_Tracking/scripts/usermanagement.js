//edit and delete function
function editData(element)
{
    var row = element.parentNode.parentNode;
    // var username = row.getAttribute("USERNAME");
    //extract the data from the row
    var username = row.cells[0].innerHTML;
    var roles = row.cells[1].innerHTML;
    //populate the form with the extracted data
    document.getElementById("editusername").value = username;
    document.getElementById("editroles").value = roles;
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
    var username = document.getElementById("editusername").value;
    // Append the ID to the form data
    formData.append("username", username);

    //make an ajax request to update the data
    var xhr =new XMLHttpRequest();
    xhr.open("POST","/inventory_Tracking/php/validate_credentials.php?function=update",true);
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
function deleteData(element)
{
    var row = element.parentNode.parentNode;
    //extract data from the selected row
    var username = row.cells[0].innerHTML;
    var role = row.cells[1].innerHTML;
    //display confirmation message
    var confirmation = confirm("Are you sure you want to delete the record?");
    
    if(confirmation){
        //make an ajax request to delete the data
        var xhr = new XMLHttpRequest();
        xhr.open("POST","/inventory_Tracking/php/validate_credentials.php?function=delete",true);
        xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4 && xhr.status === 200){
                location.reload();
            }
        };
        //send data to the server
        xhr.send("ID=" + encodeURIComponent(username) + "&name="+encodeURIComponent(role));
    }
}
function validateForm() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmpassword').value;

    //check if password and confirm password they match
    if (password != confirmPassword) {
      alert('Passwords do not match.');
      return false;
    } else {
      return true;
    }
}
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('showPassword');

    if (showPasswordCheckbox.checked) {
      // If the checkbox is checked, show the plaintext password
      passwordInput.type = 'text';
    } else {
      // If the checkbox is unchecked, show the masked dots (password)
      passwordInput.type = 'password';
    }
}