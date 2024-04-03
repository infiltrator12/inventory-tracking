<?php

require_once 'config.php';

if (isset($_GET["function"])) {
    $func = $_GET["function"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($func == "add") {
            add();
        } elseif ($func == "update") {
            update();
        } elseif ($func == "delete") {
            delete();
        } elseif ($func == "search") {
            //get the search term
            $searchTerm = $_GET['q'];
            $searchResults = search($searchTerm);
        } elseif ($func == "save") {
            save($_POST);
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

function add()
{
    $conn = connectDatabase();

    $Name = $_POST["name"];
    $Host = $_POST["host"];
    $ProvisionedSpace = $_POST["provisionedspace"];
    $UsedSpace = $_POST["usedspace"];
    $OperatingSystem = $_POST["os"];
    $MemorySize = $_POST["memorysize"];
    $IPAddress = $_POST["ipaddress"];
    $Application = $_POST["application"];
    $Environment = $_POST["environment"];

    $stmt =  $conn->prepare("INSERT INTO vservers (Name, Host, provisionedspace, UsedSpace, OperatingSystem, MemorySize, IPAddress, Application, Environment) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssiisssss", $Name, $Host, $ProvisionedSpace, $UsedSpace, $OperatingSystem, $MemorySize, $IPAddress, $Application, $Environment);

    if ($stmt->execute()) {
        echo "Data Inserted.";
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
        if (isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["host"])) {
            $id = $_POST["id"];
            $Name = $_POST["name"];
            $Host = $_POST["host"];

            $conn = connectDatabase();

            //Delete Record from the database
            $sql = "DELETE FROM vservers WHERE id = '$id' AND name = '$Name' AND host = '$Host'";

            if ($conn->query($sql) === TRUE) {
                echo "SUCCESS";
            } else {
                echo "ERROR";
            }

            $conn->close();
        }
    }
}

function update()
{
    $conn = connectDatabase();

    $id = $_POST["id"];
    $Name = $_POST["editName"];
    $Host = $_POST["editHost"];
    $ProvisionedSpace = $_POST["editProvisionedspace"];
    $UsedSpace = $_POST["editUsedspace"];
    $OperatingSystem = $_POST["editos"];
    $MemorySize = $_POST["editMemorysize"];
    $IPAddress = $_POST["editIPAddress"];
    $Application = $_POST["editApplication"];
    $Environment = $_POST["editEnvironment"];

    $sql = "UPDATE vservers SET
            name = '$Name',
            host = '$Host',
            provisionedspace = '$ProvisionedSpace',
            usedspace = '$UsedSpace',
            operatingsystem = '$OperatingSystem',
            memorysize = '$MemorySize',
            ipaddress = '$IPAddress',
            application = '$Application',
            environment = '$Environment'
            WHERE id =$id";

    if ($conn->query($sql) === TRUE) {
        echo "Data updated Successfully";
        header("Location: vservers.php");
        exit();
    } else {
        echo "Error updating data: " . $conn->error;
    }

    $conn->close();
}

function save($data)
{
    //retrieve the JSON data from the post request
    $data = file_get_contents('php://input');
    if (isset($data) && !empty($data)) {
        $lines = json_decode($data, true);

        if ($lines === null) {
            //JSON decoding failed
            echo "Error: Invalid JSON data";
            exit();
        }
    } else {
        //No data recieved
        echo "Error: No data Recieved";
        exit();
    }

    $conn = connectDatabase();

    $csv = $lines['data'];
    for ($i = 1; $i < count($csv); $i++) {
        $tempArray = [];
        $tempArray = preg_split("/\,/", $csv[$i]);
        $ID = $tempArray[0];

        //check if the record already exists
        $chckStmt = $conn->prepare("SELECT id FROM vservers WHERE id = ?");
        $chckStmt->bind_param("i", $ID);
        $chckStmt->execute();
        $chckStmt->store_result();

        if ($chckStmt->num_rows > 0) {
            //Record with the same primary key already exists
            echo "Error: Duplicate entry for primary key 'id'";
            continue;
        }

        //prepare SQL insert statement
        $stmt = $conn->prepare("INSERT IGNORE INTO vservers (id, name, host, provisionedspace, usedspace, operatingsystem, memorysize, ipaddress, application, environment)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssssss", $tempArray[0], $tempArray[1], $tempArray[2], $tempArray[3], $tempArray[4], $tempArray[5], $tempArray[6], $tempArray[7], $tempArray[8], $tempArray[9]);
        // Execute the prepared statement
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}
function search($searchTerm)
{
    $conn = connectDatabase();

    //prepare the search query with the prepare statement to avoid sql injection
    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM vservers WHERE ";
    $conditions = array();

    //Define the fields to search
    $fields = array(
        'name',
        'host',
        'provisionedspace',
        'usedspace',
        'operatingsystem',
        'memorysize',
        'ipaddress',
        'application',
        'environment'
    );

    //loop through each fields and add it to the SQL query
    foreach ($fields as $field) {
        $conditions[] = "$field LIKE ?";
    }

    //combine the conditions with an OR operator
    $sql .= implode(" OR ", $conditions);
    $stmt = $conn->prepare($sql);

    //bind the search term to the query parameters
    $params = array_fill(0, count($fields), $searchTerm);
    $stmt->bind_param(str_repeat("s", count($fields)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();

    //process the seach results
    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //extract the relevant data from the rows
                $searchResults[] = $row;
            }
        } else {
            //No results found
            $searchResults = array();
        }
    } else {
        //Error executing te query
        die("Error executing the search query: " . $stmt->error);
    }
    //close the connection
    $stmt->close();
    $conn->close();

    //return the search results as JSON
    header('Content-type: application/json');
    echo json_encode($searchResults);
    return json_encode($searchResults);
}
