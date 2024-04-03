<?php

require_once 'config.php';

//this confirms the payload request in the url
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET["function"])) {
        $func = $_GET["function"];

        if ($func == "save") {
            save($_POST);
        } elseif ($func == "add") {
            add();
        } elseif ($func == "update") {
            update();
        } elseif ($func == "delete") {
            delete();
        } elseif ($func == "search") {
            $searchTerm = $_GET['q'];
            $searchResults = searchUser($searchTerm);
        }
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

function save($data)
{
    if (isset($data["data"])) {
        $lines = $data["data"];
    } else {
        echo "Error: No data Received";
        exit();
    }

    $conn = connectDatabase();

    foreach ($lines as $ds) {
        if (strpos($ds, "mploy") === false) {
            $temp = preg_split("/\,/", $ds);
            if (sizeof($temp) > 4) {

                $sql0 = "select * from users where employeenumber='$temp[0]'";
                $retval = $conn->query($sql0);
                if ($retval->num_rows == 0) {
                    $sql = "INSERT INTO users (employeenumber,  employeesurname, employeefirstname, groups, department) VALUES('$temp[0]', '$temp[1]', '$temp[2]', '$temp[3]', '$temp[4]')";

                    $stmt = $conn->prepare($sql);
                    if ($stmt) {

                        $stmt->execute();

                        $stmt->close();
                    }
                }
            }
        }
    }

    $conn->close();
}

function add()
{
    // Create a connection
    $conn = connectDatabase();

    $employeenumber = $_POST["employeenumber"];
    $employeesurname = $_POST["employeesurname"];
    $employeefirstname = $_POST["employeefirstname"];
    $groups = $_POST["groups"];
    $department = $_POST["department"];

    $stmt = $conn->prepare("INSERT INTO users(employeenumber, employeesurname, employeefirstname, groups, department)
                VALUES(?,?,?,?,?)");

    $stmt->bind_param("sssss", $employeenumber, $employeesurname, $employeefirstname, $groups, $department);

    if ($stmt->execute()) {
        // Checking whether query executed or not
        // Redirect back to the current page
        header("Location: users.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}



function delete()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["employeenumber"]) && isset($_POST["employeesurname"]) && isset($_POST["employeefirstname"])) {
            $employeenumber = $_POST["employeenumber"];
            $employeesurname = $_POST["employeesurname"];
            $employeefirstname = $_POST["employeefirstname"];

            // ... Perform the delete operation ...
            // Connect to the database
            $conn = connectDatabase();

            // Prepare the delete statement
            $stmt = $conn->prepare("DELETE FROM users WHERE employeenumber = ? AND employeesurname = ?");
            $stmt->bind_param("ss", $employeenumber, $employeesurname);

            // Execute the delete statement
            if ($stmt->execute()) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        echo "Error: employeenumber, employeesurname, or employeefirstname not provided";
    }
}
function update()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $employeenumber = $_POST["editemployeenumber"];
        $employeesurname = $_POST["editemployeesurname"];
        $employeefirstname = $_POST["editemployeefirstname"];
        $groups = $_POST["editgroups"];
        $department = $_POST["editdepartment"];

        $conn = connectDatabase();

        $stmt = $conn->prepare("UPDATE users SET 
            employeesurname = ?,
            employeefirstname = ?,
            groups = ?,
            department = ?
            WHERE employeenumber = ?");

        // Bind parameters to the statement
        $stmt->bind_param("sssss", $employeesurname, $employeefirstname, $groups, $department, $employeenumber);

        if ($stmt->execute()) {
            echo "Data updated successfully";
            header("location: users.php");
            exit;
        } else {
            echo "Error updating data: " . $conn->error;
        }
        $conn->close();
    }
}
function searchUser($searchTerm)
{
    $conn = connectDatabase();

    // Prepare the searchTerm with the prepare statement to avoid SQL injection
    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM users WHERE ";
    $condition = array();

    // Define the fields to search
    $fields = array(
        'employeenumber',
        'employeesurname',
        'employeefirstname',
        'groups',
        'department'
    );

    // Loop through each field and add it to the SQL query
    foreach ($fields as $field) {
        $condition[] = "$field LIKE ?";
    }

    // Combine the conditions with the OR operator
    $sql .= "(" . implode(" OR ", $condition) . ")";
    $stmt = $conn->prepare($sql);

    // Bind the searchTerm to the query parameters
    $params = array_fill(0, count($fields), $searchTerm);
    $stmt->bind_param(str_repeat("s", count($fields)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();

    // Process the results
    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $searchResults[] = $row;
            }
        } else {
            // No results Found
            $searchResults = array();
        }
    } else {
        // Error executing the query
        die("Error executing the searchQuery: " . $stmt->error);
    }

    // Close database connection
    $stmt->close();
    $conn->close();

    // Return the search results as JSON
    header('Content-type: application/json');
    echo json_encode($searchResults);
    return json_encode($searchResults);
}
