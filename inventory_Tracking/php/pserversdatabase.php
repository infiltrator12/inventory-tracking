<?php
require_once 'config.php';

//this confirms the payload request in the URL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET["function"])) {
        $function = $_GET["function"];

        if ($function == "save") {
            save($_POST);
        } elseif ($function == "add") {
            add();
        } elseif ($function == "update") {
            update();
        } elseif ($function == "delete") {
            delete();
        } elseif ($function == "search") {
            $searchTerm = $_GET['q'];
            $searchResults = searchServer($searchTerm);
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

    $serialnumber = $_POST["serialnumber"];
    $assettag = $_POST["assettag"];
    $model = $_POST["model"];
    $make = $_POST["make"];
    $host = $_POST["host"];
    $ipaddress = $_POST["ipaddress"];

    $stmt = $conn->prepare("INSERT INTO pservers(serialnumber, assettag, model, make, host, ipaddress) 
                    VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $serialnumber, $assettag, $model, $make, $host, $ipaddress);

    if ($stmt->execute()) {
        header("location: pservers.php");
        exit;
    } else {
        echo "ERROR: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}

function update()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["editserialnumber"], $_POST["editassettag"], $_POST["editmodel"], $_POST["editmake"], $_POST["edithost"], $_POST["editipaddress"])) {
            $serialnumber = $_POST["editserialnumber"];
            $assettag = $_POST["editassettag"];
            $model = $_POST["editmodel"];
            $make = $_POST["editmake"];
            $host = $_POST["edithost"];
            $ipaddress = $_POST["editipaddress"];

            $conn = connectDatabase();

            $sql = "UPDATE pservers SET 
                assettag = '$assettag',
                model = '$model',
                make = '$make',
                host = '$host',
                ipaddress = '$ipaddress'
                WHERE serialnumber = $serialnumber";

            // $stmt->bind_param("ssssss", $assettag, $model, $make, $host, $ipaddress, $serialnumber);

            if ($conn->query($sql)) {
                echo "Data Updated Successfully";
                header("location: /inventory_Tracking/php/pservers.php");
                exit;
            } else {
                echo "Error updating data: " . $conn->error;
            }
            $conn->close();
        }
    }
}

function delete()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["serialnumber"]) && isset($_POST["assettag"])) {
            $serialnumber = $_POST["serialnumber"];
            $assettag = $_POST["assettag"];

            $conn = connectDatabase();

            $stmt = $conn->prepare("DELETE FROM pservers WHERE serialnumber = ? AND assettag = ?");
            $stmt->bind_param("ss", $serialnumber, $assettag);

            if ($stmt->execute()) {
                echo "Record Deleted Successfully";
            } else {
                echo "Error deleting Record: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        echo "Error: serialnumber and assettag or provided";
    }
}

function save($data)
{
    print_r($data);
    if (isset($data["data"])) {
        $data = $data["data"];
    }

    $conn = connectDatabase();

    foreach ($data as $ds) {
        if (strpos($ds, "mploy") === false) {
            $temp = preg_split("/\,/", $ds);
            if (sizeof($temp) > 4) {

                $sql = "SELECT * FROM pservers WHERE serialnumber = '$temp[0]'";
                $ret = $conn->query($sql);
                if ($ret->num_rows == 0) {
                    $sql0 = "INSERT INTO pservers (serialnumber, assettag, model, make, host, ipaddress) 
                                VALUES ('$temp[0]', '$temp[1]', '$temp[2]', '$temp[3]', '$temp[4]', '$temp[5]')";
                    $stmt = $conn->prepare($sql0);

                    if ($stmt) {
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
    }
}


function searchServer($searchTerm)
{
    $conn = connectDatabase();

    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM pservers WHERE ";
    $condition = array();

    $searchFields = array('serialnumber', 'assettag', 'model', 'make', 'host', 'ipaddress');

    foreach ($searchFields as $field) {
        $condition[] = "$field LIKE ?";
    }
    //combine the conditions with the OR operator
    $sql .= "(" . implode(" OR ", $condition) . ")";
    $stmt = $conn->prepare($sql);

    //bind the searchTerm with the query parameters
    $parameters = array_fill(0, count($searchFields), $searchTerm);
    $stmt->bind_param(str_repeat("s", count($searchFields)), ...$parameters);
    $stmt->execute();
    $results = $stmt->get_result();

    $searchResults = array();

    //process the results
    if ($results) {
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                //extract the relevant data from the row
                $serialnumber = $row["serialnumber"];
                $assettag = $row["assettag"];
                $model = $row["model"];
                $make = $row["make"];
                $host = $row["host"];
                $ipaddress = $row["ipaddress"];

                //add the results to the search result array
                $searchResults = array(
                    "serial number" => $serialnumber,
                    "asset_tag" => $assettag, "model" => $model,
                    "make" => $make, "host" => $host, "ipaddress" => $ipaddress
                );
            }
        } else {
            //no results found
            $searchResults = array();
        }
    } else {
        //error executing query
        die("Error executing the searchQuerry: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();

    header('Content-type: application/json');
    echo json_encode($searchResults);
    return json_encode($searchResults);
}
