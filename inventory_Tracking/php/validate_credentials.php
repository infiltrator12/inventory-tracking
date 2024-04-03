<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: 192.168.1.57');

require_once 'logger.php';
require_once 'config.php';

if (isset($_GET["function"])) {
    $func = $_GET['function'];

    if ($func == "login") {
        validateCredentials();
    } elseif ($func == "delete") {
        delete();
    } elseif ($func == "update") {
        update();
    } elseif ($func == "add") {
        add();
    }
}

function connectDatabase()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function validateCredentials()
{
    session_start();

    // Check if the user is already logged in
    if (isset($_SESSION['user_id'])) {
        // Redirect to the first page or any other authenticated area
        header("Location: /inventory_Tracking/php/dashboard.php");
        exit();
    }

    // Check if the POST data contains the 'username' and 'password' fields
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $conn = connectDatabase();

        // Sanitize the input data to prevent SQL injection
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        // Query the database to check if the provided credentials are valid
        $sql = "SELECT * FROM logins WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            // Valid credentials, login successful
            $user = $result->fetch_assoc();
            $hashedPasswordFromdb = $user['password'];

            // Verify the entered password with the hashed password from the database
            if (password_verify($password, $hashedPasswordFromdb)) {
                // Password is correct, login successful
                $_SESSION['user_id'] = $user['id']; // You can store other user data in the session if needed
                $roles = $user['roles'];


                // Set the response array with success and role information
                $response = array(
                    'success' => true,
                    'message' => 'Login successful',
                    'role' => $roles
                );

                // Close the database connection
                $conn->close();

                // Send the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            } else {
                // Invalid password
                $response = array(
                    'success' => false,
                    'message' => 'Invalid username or password.'
                );
            }
        } else {
            // User not found
            $response = array('success' => false, 'message' => 'User not found.');
        }

        // Close the database connection
        $conn->close();

        // Send the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = array(
            'success' => false,
            'message' => 'Invalid request'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

function add()
{
    $conn = connectDatabase();

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['roles'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $roles = $_POST["roles"];


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO logins(username, password, roles) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $roles);

        if ($stmt->execute()) {
            echo "Data Inserted correctly.";
            header("location: /inventory_Tracking/php/usermanagement.php");
            exit();
        } else {
            echo "Error: " . $stmt . "<br>" . $conn->error;
        }
        $stmt->close();
    }
    $conn->close();
}

function update()
{
    $conn = connectDatabase();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["username"]) && isset($_POST["editroles"])) {
            $username = $_POST["username"];
            $roles = $_POST["editroles"];

            // Add validation to check if the user is authorized to perform this action
            // For example, you can check if the user has admin privileges.

            if (!hasPermission($_SESSION['user_roles'], 'write')) {
                echo "You do not have permissions to perform this action.";
                return;
            }

            $stmt = $conn->prepare("UPDATE logins SET roles = ? WHERE username = ?");
            $stmt->bind_param("ss", $roles, $username);

            if ($stmt->execute()) {
                echo "DATA UPDATED SUCCESSFULLY";
                header("location: /inventory_Tracking/php/usermanagement.php");
            } else {
                echo "Error updating data: " . $conn->error;
            }

            $stmt->close();
        }
    }
    $conn->close();
}

function delete()
{
    $conn = connectDatabase();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["username"]) && isset($_POST["roles"])) {
            $username = $_POST["username"];
            $roles = $_POST["roles"];

            // Add validation to check if the user is authorized to perform this action
            // For example, you can check if the user has admin privileges.

            if (!hasPermission($_SESSION['user_roles'], 'delete')) {
                echo "You do not have permissions to perform this action.";
                return;
            }

            $stmt = $conn->prepare("DELETE FROM logins WHERE username = ? AND roles = ?");
            $stmt->bind_param("ss", $username, $roles);

            if ($stmt->execute()) {
                echo "SUCCESS";
            } else {
                echo "Error";
            }

            $stmt->close();
        }
    }
    $conn->close();
}

//function to fetch user role from the database
function getUserRoles($username)
{
    global $conn;

    $sql = "SELECT roles FROM logins where username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($roles);
    $stmt->fetch();
    $stmt->close();

    return $roles;
}
//sample function to chek if user has permission to perform a certain function
function hasPermission($roles, $action)
{
    //define roles and their permissions
    $role = [
        'admin' => ['read', 'write', 'delete', 'create'],
        'user' => ['read']
    ];

    if (isset($role[$roles]) && in_array($action, $role[$roles])) {
        return true;
    }
    return false;
}
