<?php

require_once 'config.php';

if (isset($_GET["function"])) {
    $func = $_GET["function"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($func == "add") {
            add();
        } elseif ($func == "save") {
            save($_POST);
        } elseif ($func == "update") {
            update();
        } elseif ($func == "delete") {
            delete();
        } elseif ($func == "search") {
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
    // Create a connection
    $conn = connectDatabase();

    if (isset($data["serialnumber"]) && isset($data["asset_tag"]) && isset($data["model"]) && isset($data["storage_capacity"]) && isset($data["storage_type"]) && isset($data["ram_capacity"]) && isset($data["employeename"]) && isset($data["employeenumber"]) && isset($data["department"]) && isset($data["dateissued"]) && isset($data["device_condition"]) && isset($data["allocation"]) && isset($data["description"])) {
        $serialnumber = $data['serialnumber'];
        $assettag = $data['asset_tag'];
        $model = $data['model'];
        $storagecapacity = $data['storage_capacity'];
        $storagetype = $data['storage_type'];
        $ramcapacity = $data['ram_capacity'];
        $employeename = $data['employeename'];
        $employeenumber = $data['employeenumber'];
        $department = $data['department'];
        $dateissued = $data['dateissued'];
        $device_condition = $data['device_condition'];
        $allocation = $data['allocation'];
        $description = $data['description'];

        $stmt = $conn->prepare("INSERT IGNORE INTO laptops(serialnumber, asset_tag, model, storage_capacity, storage_type, ram_capacity, employeename, employeenumber, department, dateissued, device_condition, allocation, description)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param("sssssssssdsss", $serialnumber, $assettag, $model, $storagecapacity, $storagetype, $ramcapacity, $employeename, $employeenumber, $department, $dateissued, $device_condition, $allocation, $description);

        if ($stmt->execute()) {
            // Checking whether query executed or not
            echo "Data Inserted";
            // Redirect back to the current page
            header("Location: catalog.php");
            exit();
        } else {
            echo "Error: " . $stmt . "<br>" . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error: Required data is missing";
    }
}

function save()
{
    $conn = connectDatabase();

    $jsonData = file_get_contents('php://input');
    if (isset($jsonData) && !empty($jsonData)) {
        $lines = json_decode($jsonData, true);

        if ($lines === null) {
            echo "Error: Invalid JSON data";
            exit;
        }
    } else {
        // No data received
        echo "Error: No data received";
        exit;
    }

    $csv = $lines['data'];
    for ($i = 1; $i < count($csv); $i++) {
        $tempArray = preg_split("/\,/", $csv[$i]);
        $serialnumber = $tempArray[0];

        $stmt = $conn->prepare("INSERT ignore INTO laptops (serialnumber, assettag, model, storagecapacity, storagetype, ramcapacity, employeename, employeenumber, department, dateissued, devicecondition, allocation, description)
                            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssssssss", $tempArray[0], $tempArray[1], $tempArray[2], $tempArray[3], $tempArray[4], $tempArray[5], $tempArray[6], $tempArray[7], $tempArray[8], $tempArray[9], $tempArray[10], $tempArray[11], $tempArray[12]);
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
function update()
{
    $conn = connectDatabase();

    $serialnumber = $_POST["serialnumber"];
    $assettag = $_POST["assettag"];
    $model = $_POST["model"];
    $storagecapacity = $_POST["storagecapacity"];
    $storagetype = $_POST["storagetype"];
    $ramcapacity = $_POST["ramcapacity"];
    $employeefirstname = $_POST["employeefirstname"];
    $employeenumber = $_POST["employeenumber"];
    $department = $_POST["department"];
    $dateissued = $_POST["dateissued"];
    $devicecondition = $_POST["devicecondition"];
    $allocation = $_POST["allocation"];
    $description = $_POST["description"];

    $sql = "UPDATE laptops SET 
    assettag = '$assettag',
    model = '$model',
    storagecapacity = '$storagecapacity',
    storagetype = '$storagetype',
    ramcapacity = '$ramcapacity ',
    employeefirstname = '$employeefirstname',
    employeenumber = '$employeenumber',
    department = '$department',
    dateissued = '$dateissued',
    devicecondition = '$devicecondition',
    allocation = '$allocation',
    description = '$description'
    WHERE serialnumber = $serialnumber";

    if ($conn->query($sql) === TRUE) {
        echo "Data updated successfully";
        header("location: catalog.php");
        exit();
    } else {
        echo "Error Updating data: " . $conn->error;
    }
    $conn->close();
}
function search($searchTerm)
{

    $conn = connectDatabase();

    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM laptops WHERE ";
    $condition = array();

    $fields = array(
        'serialnumber', 'assettag', 'model', 'storagecapacity', 'storagetype',
        'ramcapacity', 'employeename', 'employeenumber', 'department',
        'dateissued', 'devicecondition', 'allocation', 'description'
    );

    foreach ($fields as $field) {
        $condition[] = "$field LIKE ?";
    }

    $sql .= implode(" OR ", $condition);
    $stmt = $conn->prepare($sql);

    $params = array_fill(0, count($fields), $searchTerm);
    $stmt->bind_param(str_repeat("s", count($fields)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //extract the relevant data here
                $searchResults[] = $row;
                //add the result to the search result array
            }
        } else {
            $searchResults = array();
        }
    } else {
        die("Error executing the search query: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    header('Content-type: application/json');
    echo json_encode($searchResults);
    return json_encode($searchResults);
}
function delete()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["serialnumber"]) && isset($_POST["assettag"])) {
            $assettag = $_POST["assettag"];
            $serialnumber = $_POST["serialnumber"];

            $conn = connectDatabase();

            $sql = "DELETE FROM laptops WHERE serialnumber = '$serialnumber' AND assettag = '$assettag'";

            if ($conn->query($sql) === TRUE) {
                echo "SUCCESS";
            } else {
                echo "ERROR";
            }
            $conn->close();
        }
    }
}
