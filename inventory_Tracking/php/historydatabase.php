<?php
require_once 'config.php';

if (isset($_GET["function"])) {
    $func = $_GET["function"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($func == "search") {
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

function search($searchTerm)
{
    $conn = connectDatabase();

    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM history WHERE ";
    $cond = array();

    $searchFields = array('serialnumber', 'assettag', 'employeenumber', 'employeesurname');

    foreach ($searchFields as $fields) {
        $cond[] = "$fields LIKE ?";
    }

    $sql .= implode(" OR ", $cond);
    $stmt = $conn->prepare($sql);

    $params = array_fill(0, count($searchFields), $searchTerm);
    $stmt->bind_param(str_repeat("s", count($searchFields)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //extract the relevant data
                $searchResults[] = $row;
            }
        } else {
            //No results found
            $searchResults = array();
        }
    } else {
        //error executing query
        die("Error executing the searchquery: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();

    header('Content-type: application/json');
    echo json_encode($searchResults);
    return json_encode($searchResults);
}
