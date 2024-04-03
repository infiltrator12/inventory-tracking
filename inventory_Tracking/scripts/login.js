function validateCredentials(event) {
  event.preventDefault();

  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  // Send the form data to the server for validation
  const xhr = new XMLHttpRequest();
  const url = '/inventory_Tracking/php/validate_credentials.php?function=login';
  const data = `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`;

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          // If login is successful, redirect to the appropriate dashboard page
          if (response.role === "ADMIN") {
            window.location.href = '/inventory_Tracking/php/dashboard.php';
          } else {
            window.location.href = '/inventory_Tracking/php/userdashboard.php';
          }
        } else {
          alert(response.message);
        }
      } else {
        alert('Error occurred during login. Please try again later.');
      }
    }
  };

  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send(data);
}

