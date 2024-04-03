<!DOCTYPE html>
<html lang="en">
  <head>
    <title>LOGIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="/inventory_Tracking/styles/login.css" />
    <style>
      body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
          "Helvetica Neue", Arial, sans-serif;
        font-size: 1rem;
        line-height: 1.5;
        color: #373a3c;
        background-color: #fff;
        overflow: auto;
        background-image: url("/inventory_Tracking/assets/oip.jpeg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        font-family: Arial, sans-serif;
        color: black;
      }
    </style>
  </head>
  <body>
    <div class="login-container">
      <form
        class="login-form"
        onsubmit="validateCredentials(event)"
        id="login-form"
        action="/inventory_Tracking/php/validate_credentials.php?function=login"
      >
        <h2>LOGIN</h2>
        <!-- Your login form elements -->
        <label for="username">USERNAME: </label>
        <input
          type="text"
          placeholder="username"
          id="username"
          name="username"
          autocomplete="username"
        />

        <label for="password">PASSWORD: </label>
        <input
          type="password"
          placeholder="password"
          id="password"
          name="password"
          autocomplete="current-password"
        />

        <button type="submit">Login</button>
      </form>
    </div>
    <script src="/inventory_Tracking/scripts/login.js"></script>
  </body>
</html>
