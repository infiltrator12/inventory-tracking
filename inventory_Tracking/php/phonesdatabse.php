<?php
require_once 'config.php';

if (isset($_GET["function"])) {
    $func = $_GET["function"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($func == "add") {
            add();
        } elseif ($func == "save") {
            save($POST);
        } elseif ($func == "update") {
            update();
        } elseif ($func == "delete") {
            delete();
        } elseif ($func == "search") {
            $searchTerm = $_GET['q'];
            $searchResults = search($searchResults);
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
    //RETRIEVE THE JSON data from the post request
    $data = file_get_contents('php://input');
    if (isset($data) && !empty($data)) {
        $lines = json_decode($data, true);

        if ($lines === null) {
            //JSON decoding failed
            echo "Error: Invalid Json Data";
            exit;
        }
    } else {
        //No data recieved
        echo "Error: No data recieved";
        exit;
    }
    $conn = connectDatabase();

    $csv = $lines['data'];
    for ($i = 1; $i < count($csv); $i++) {
        $tempArray = [];
        $tempArray = preg_split("/\,/", $csv[$i]);
        $ID = $tempArray[0];

        //check if the record already exists
        $chckStmt = $conn->prepare("SELECT id FROM phones WHERE assettag = ?");
        $chckStmt->bind_param("i", $ID);
        $chckStmt->execute();
        $chckStmt->store_result();

        if ($chckStmt->num_rows > 0) {
            //Record with the same primary key already exists
            echo "Error: Duplicate entry for primary key 'id'";
            continue;
        }

        //prepare SQL insert statement
        $stmt = $conn->prepare("INSERT IGNORE INTO phones (assettag, extension, mac_address, model, employeenumber, employeefirstname, department)
                    VALUES(?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssss", $tempArray[0], $tempArray[1], $tempArray[2], $tempArray[3], $tempArray[4], $tempArray[5]);
        // Execute the prepared statement
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}

function add()
{
    $conn = connectdatabase();

    $assettag = $_POST["assettag"];
    $extension = $_POST["extension"];
    $mac_address = $_POST["mac_address"];
    $model = $_POST["model"];
    $employeenumber = $_POST["employeenumber"];
    $employeefirstname = $_POST["employeefirstname"];
    $department = $_POST["department"];

    $stmt = $conn->prepare("INSERT INTO phones(assettag, extension, mac_address, model, employeenumer, employeefirstname, department)
                            VALUES(?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $assettag, $extension, $mac_address, $model, $employeenumber, $employeefirstname, $department);

    if ($stmt->execute()) {
        echo "Data Inserted.";
        exit();
    } else {
        echo "ERROR: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}

function delete()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["assettag"]) && isset($_POST["extension"]) && isset($_POST["macaddress"])) {
            $assettag = $_POST["assettag"];
            $extension = $_POST["extension"];
            $mac_address = $_POST["macaddress"];

            $conn = connectDatabase();

            //Delete Record from the database
            $sql = "DELETE FROM phones WHERE assettag = '$assettag' AND extension = '$extension' AND macaddress = '$mac_address'";

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

    $assettag = $_POST["editassettag"];
    $extension = $_POST["editextension"];
    $mac_address = $_POST["editmac_address"];
    $model = $_POST["editmodel"];
    $employeenumber = $_POST["editemployeenumber"];
    $employeefirstname = $_POST["editemployeefirstname"];
    $department = $_POST["editdepartment"];

    $sql = "UPDATE phones SET
            extension = '$extension',
            mac_address = '$mac_address',
            model = '$model',
            employeenumber = '$employeenumber',
            employeename = '$employeefirstname',
            department = '$department'
            WHERE assettag = $assettag";
    if ($conn->query($sql) === TRUE) {
        echo "Data updated Successfully";
        header("Location: vservers.php");
        exit();
    } else {
        echo "Error updating data: " . $conn->error;
    }

    $conn->close();
}

function search($searchTerm)
{
    $conn = connectDatabase();

    //prepare the search query with the prepare statement to avoid sql injection
    $searchTerm = "%" . $searchTerm . "%";
    $sql = "SELECT * FROM phones WHERE ";
    $conditions = array();

    //Define the fields to search
    $fields = array(
        'assettag',
        'extension',
        'mac_address',
        'model',
        'employeenumber',
        'employeefirstname',
        'department'
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
