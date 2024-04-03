<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Device History</title>
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <button class="openSidenav" onclick="opens()">&#9776</button>
        <div class="top">
            <a href="/inventory_tracking/php/dashboard.php" class="logo"><img src="/inventory_tracking/assets/interswitch_logo.svg" width="200px" height="70px"></a>
        </div>
        <a href="/inventory_Tracking/history.html" class="a"><strong>DEVICE HISTORY</strong></a>
        <div class="header-right">
            <div class="toggle-container">
                <label class="toggle-switch">
                    <input type="checkbox" id="modeToggle" onclick="toggleMode()">
                    <span class="toggle-slider round"></span>
                </label>
                <span id="icon" class="light-mode-icon"></span>
            </div>
            <img src="/inventory_tracking/assets/Hostess characters.jpg" alt="avatar" class="avatar">
            <div class="dropdown">
                <button class="dropbtn">JOHN DOE
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="/inventory_Tracking/php/logout.php" id="out"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
                </div>
            </div>
        </div>
    </div>
    <div id="main">
        <div class="sidenav" id="Sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closes()" style="color: white;">&times;</a><br><br>
            <div class="bottom">
                <a href="/inventory_Tracking/php/dashboard.php" id="dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i>DASHBOARD</a>
                <button class="dropdown-btn" id="dropdownbtn">
                    <i class="fa-solid fa-info fa-2x"></i>DEVICE CATALOG<i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container" id="dropdowncont">
                    <a href="/inventory_Tracking/php/catalog.php" id="laptops"><i class="fa-solid fa-laptop"></i>LAPTOPS</a>
                    <a href="/inventory_tracking/php/devices.php" id="networking_devices"><i class="fa-solid fa-network-wired"></i>NETWORKING DEVICES</a>
                    <a href="/inventory_Tracking/php/pservers.php" id="physical_servers"><i class="fa-solid fa-server"></i>PHYSICAL SERVERS</a>
                    <a href="/inventory_Tracking/php/vservers.php" id="virtual_servers"><i class="fa-solid fa-server"></i>VIRTUAL SERVERS</a>
                    <a href="/inventory_Tracking/php/phone.php" id="phones"><i class="fa-solid fa-phone"></i>PHONES</a>
                </div>
                <a href="/inventory_Tracking/php/history.php" id="device_history"><i class="fa fa-history" aria-hidden="true"></i> DEVICE HISTORY</a>
                <a href="/inventory_Tracking/php/users.php" id="users"><i class="fa fa-user" aria-hidden="true"></i>
                    USERS</a>
                <a href="/inventory_Tracking/php/allocation.php" id="allocation_checkout"><i class="fa fa-credit-card-alt" aria-hidden="true"></i>ALLOCATION&CHECKOUT</a>
                <a href="/inventory_Tracking/php/conditions.php" id="condition_monitoring"><i class="fa fa-desktop"></i>
                    CONDITION MONITORING</a>
                <a href="/inventory_Tracking/php/logout.php" id="out" onclick="logout()"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
            </div>
        </div>
        <div class="formfunc">
            <div class="impo">
                <input type="file" id="csvFileInput">
                <button onclick="handleFile()" class="u"><i class="fa-solid fa-file-import"></i>Upload CSV FILE</button>
            </div>
            <button onclick="exportTableToCSV('myCSVFile.csv')" class="expo"><i class="fa-solid fa-file-excel"></i>Export to CSV</button>
        </div>
        <div class="data">
            <div class="align">
                <div id="searchResults" class="search-param">
                    <form method="GET" class="searchbar" id="searchForm">
                        <input type="text" placeholder="SEARCH" name="q" id="searchInput">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </div><br><br>
            <div class="marg table-container">
                <h2>DATA DISPLAY</h2><br>
                <table id="mytable">
                    <thead>
                        <tr>
                            <th>DEVICE_SERIALNUMBER</th>
                            <th>DEVICE_ASSETTAG</th>
                            <th>EMPLOYEENUMBER</th>
                            <th>EMPLOYEE NAME</th>
                            <th>START DATE</th>
                            <th>END DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        $query = "SELECT * FROM history";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['serialnumber'] . "</td>";
                            echo "<td>" . $row['assettag'] . "</td>";
                            echo "<td>" . $row['employeenumber'] . "</td>";
                            echo "<td>" . $row['employeesurname'] . "</td>";
                            echo "<td>" . $row['startdate'] . "</td>";
                            echo "<td>" . $row['enddate'] . "</td>";
                            echo "<td>";
                            echo "<span class='edit-icon' onclick='editdata(this)'><i class='fa-solid fa-edit'></i></span><br>";
                            echo "<span class='delete-icon' onclick='deletedata(this)'><i class='fa-solid fa-trash'></i></span>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
<script src="/inventory_Tracking/scripts/shared.js"></script>
<script src="/inventory_Tracking/scripts/history.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>

</html>