<?php

require_once 'config.php';

if (isset($_GET["function"])) {
    $func = $_GET['function'];

    if ($func == "search") {
        $searchTerm = $_GET['q'];
        $searchResults = search($searchTerm);
    } elseif ($func == "add") {
        add();
    } elseif ($func == "update") {
        update();
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

function search($searchTerm)
{
    $conn = connectDatabase();

    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM allocations WHERE ";
    $condition = array();

    $fields = array(
        'serialnumber', 'assettag', 'employeenumber', 'employeename', 'allocationdate', 'deallocationdate'
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

function add()
{
    $conn = connectDatabase();

    if (isset($_POST["serialnumber"]) && isset($_POST["asset_tag"]) && isset($_POST["employeenumber"]) && isset($_POST["employeename"]) && isset($_POST["allocationdate"]) && isset($_POST["deallocationdate"])) {
        $serialnumber = $_POST['serialnumber'];
        $assettag = $_POST['asset_tag'];
        $employeenumber = $_POST['employeenumber'];
        $employeename = $_POST['employeename'];
        $allocationdate = $_POST['allocationdate'];
        $deallocationdate = $_POST['deallocationdate'];

        $stmt = $conn->prepare("INSERT IGNORE INTO allocations(serialnumber, assettag, employeenumber, employeename, allocationdate, deallocationdate)
                VALUES(?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssss", $serialnumber, $assettag, $employeenumber, $employeename, $allocationdate, $deallocationdate);

        if ($stmt->execute()) {
            // Checking whether query executed or not
            echo "Data Inserted";
            header("location: /inventory_Tracking/php/allocation.php");
            exit();
        } else {
            echo "Error: " . $stmt . "<br>" . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error: Required data is missing";
    }
}

function update()
{
    $conn = connectDatabase();

    $serialnumber = $_POST['editserialnumber'];
    $assettag = $_POST['editassettag'];
    $employeenumber = $_POST['editemployeenumber'];
    $employeename = $_POST['editempoyeename'];
    $allocationdate = $_POST['editallocationdate'];
    $deallocationdate = $_POST['deallocationdate'];

    $sql = "UPDATE allocations SET 
            assettag = '$assettag',
            employeenumber = '$employeenumber',
            employeename = '$employeename',
            allocationdate = '$allocationdate',
            deallocationdate = '$deallocationdate'
            WHERE serialnumber = $serialnumber";

    if ($conn->query($sql) === TRUE) {
        echo "Data updated Successfully";
        header("location: allocation.php");
        exit();
    } else {
        echo "Error updating data: " . $conn->error;
    }
    $conn->close();
}
