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
            //get the search term from the query parameters
            $searchTerm = $_GET['q'];
            $searchResults = search($searchTerm);
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

    $assettag = $_POST["asset_tag"];
    $model = $_POST["model"];
    $make = $_POST["make"];
    $type = $_POST["type"];
    $ipaddress = $_POST["ip_address"];

    $stmt = $conn->prepare("INSERT INTO devices(assettag, model, make, type, ipaddress)
                VALUES(?,?,?,?,?)");

    $stmt->bind_param("sssss", $assettag, $model, $make, $type, $ipaddress);

    if ($stmt->execute()) {
        // Checking whether query executed or not
        // Redirect back to the current page
        header("Location: devices.php");
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
        if (isset($_POST["assetTag"])) {
            $assettag = $_POST["assetTag"];

            // Connect to the database
            $conn = connectDatabase();
            // Delete the record from the database
            $sql = "DELETE FROM devices WHERE assetTag = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $assettag);
            if ($stmt->execute()) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    }
}

function update()
{
    //create connection
    $conn = connectDatabase();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $assettag = $_POST["editassettag"];
        $model = $_POST["editmodel"];
        $make = $_POST["editmake"];
        $type = $_POST["edittype"];
        $ipaddress = $_POST["editipaddress"];

        //save the updated data to the database
        $sql = $conn->prepare("UPDATE devices SET 
                model = '$model',
                make = '$make',
                type = '$type',
                ip_address = '$ipaddress'
                WHERE asset_tag = $assettag");

        if ($conn->query($sql) === TRUE) {
            echo "Data updated successfully.";
            header("Location: devices.php");
        } else {
            echo "Error updating data: " . $conn->error;
        }
        $conn->close();
    }
}

function search($searchTerm)
{
    $conn = connectDatabase();

    //prepare the search query with the prepare statement to avoid sql injection
    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM devices WHERE ";
    $cond = array();

    //define the fields to search
    $fields = array(
        'assettag',
        'Model',
        'Make',
        'Type',
        'ipaddress',
    );

    //loop through each fields and add it to the SQL query
    foreach ($fields as $field) {
        $cond[] = "$field LIKE ?";
    }

    //combine the conditions with an OR operator
    $sql .= implode(" OR ", $cond);
    $stmt = $conn->prepare($sql);

    //bind the search term to the query parameters
    $param = array_fill(0, count($fields), $searchTerm);
    $stmt->bind_param(str_repeat("s", count($fields)), ...$param);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();

    //process the search results
    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //extract the relevant data from the row
                $assettag = $row["asset_tag"];
                $model = $row["model"];
                $make = $row["make"];
                $type = $row["type"];
                $ipaddress = $row["ip_address"];

                //add the result to the search result array
                $searchResults[] = array(
                    "asset_tag" => $assettag,
                    "model" => $model,
                    "make" => $make,
                    "type" => $type,
                    "ip_address" => $ipaddress
                );
            }
        } else {
            //No results found
            $searchResults = array();
        }
    } else {
        //Error executing the query
        die("Error executing the search query: " . $stmt->error);
    }
    //close database connection
    $stmt->close();
    $conn->close();

    //return the search results as JSON
    header('Content-type: application/json');
    echo json_encode($searchResults);
    return json_encode($searchResults);
}
