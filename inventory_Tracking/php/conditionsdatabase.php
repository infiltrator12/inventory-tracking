<?php
require_once 'config.php';

if (isset($_GET["function"])) {
    $func = $_GET["function"];

    if ($func == "search") {
        $searchTerm = $_GET['q'];
        $searchResults = search($searchTerm);
    } elseif ($func == "add") {
        add();
    } elseif ($func == "delete") {
        delete();
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
    $sql = "SELECT * FROM conditions WHERE ";
    $condition = array();

    $fields = array(
        'serialnumber', 'assettag', 'employeenumber', 'employeename', 'conditions'
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

    if (isset($data["serialnumber"]) && isset($data["asset_tag"]) && isset($data["employeenumber"]) && isset($data["employeename"]) && isset($data["conditions"])) {
        $serialnumber = $data['serialnumber'];
        $assettag = $data['asset_tag'];
        $employeenumber = $data['employeenumber'];
        $employeename = $data['empoyeename'];
        $conditions = $data['conditions'];

        $stmt = $conn->prepare("INSERT IGNORE INTO conditions(serialnumber, asset_tag, employeenumber, employeename, conditions)
                VALUES(?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssdsss", $serialnumber, $assettag, $employeenumber, $employeename, $conditions);

        if ($stmt->execute()) {
            // Checking whether query executed or not
            echo "Data Inserted";
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

    $serialnumber = $_POST["serialnumber"];
    $assettag = $_POST["assettag"];
    $employeenumber = $_POST["employeenumber"];
    $employeename = $_POST["employeename"];
    $conditions = $_POST["conditions"];

    $sql = "UPDATE conditions SET 
    assettag = '$assettag',
    employeenumber = '$employeenumber',
    employeename = '$employeename',
    conditions = '$conditions'
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

function delete()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["serialnumber"]) && isset($_POST["assettag"])) {
            $assettag = $_POST["assettag"];
            $serialnumber = $_POST["serialnumber"];

            $conn = connectDatabase();

            $sql = "DELETE FROM conditions WHERE serialnumber = '$serialnumber' AND assettag = '$assettag'";

            if ($conn->query($sql) === TRUE) {
                echo "SUCCESS";
            } else {
                echo "ERROR";
            }
            $conn->close();
        }
    }
}
