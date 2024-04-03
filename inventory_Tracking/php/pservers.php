<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css">
    <meta name="keyword" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

    </style>
</head>

<body>
    <div class="header">
        <button class="openSidenav" onclick="opens()">&#9776</button>
        <div class="top">
            <a href="/inventory_tracking/php/dashboard.php" class="logo"><img src="/inventory_tracking/assets/interswitch_logo.svg" width="300px" height="100px"></a>
        </div>
        <a href="/inventory_Tracking/php/pservers.php"><strong>PHYSICAL SERVERS</strong></a>
        <div class="header-right">
            <div class="toggle-container">
                <label class="toggle-switch"><input type="checkbox" id="modeToggle" onclick="toggleMode()">
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
                    <a href="/inventory_tracking/php/logout.php" id="out"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
                </div>
            </div>
        </div>
    </div>
    <div id="main">
        <div class="sidenav" id="Sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closes()" style="color: white;">&times;</a><br><br>
            <div class="bottom">
                <a href="/inventory_Tracking/php/dashboard.php" id="dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i>DASHBOARD</a>
                <button class="dropdown-btn" id="device_catalog">
                    <i class="fa-solid fa-info"></i>DEVICE CATALOG<i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container">
                    <a href="/inventory_Tracking/php/catalog.php" id="laptops"><i class="fa-solid fa-laptop"></i>LAPTOPS</a>
                    <a href="/inventory_tracking/php/devices.php" id="networking_devices"><i class="fa-solid fa-network-wired"></i>NETWORKING
                        DEVICES</a>
                    <a href="/inventory_tracking/php/pservers.php" id="physical_servers"><i class="fa-solid fa-server"></i>PHYSICAL SERVERS</a>
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
                <button onclick="handleFile()" class="u"><i class="fa-solid fa-file-import"></i>Upload CSV
                    FILE</button>
            </div>
            <button type="submit" value="addNewDevice" class="button1" onclick="openModal()"><i class="fa fa-plus" aria-hidden="true"></i>ADD NEW DEVICE
            </button>
            <button onclick="exportTableToCSV('myCSVFile.csv')" class="expo"><i class="fa-solid fa-file-excel"></i>Export to CSV</button>
        </div>
        <div class="modal" id="id01" style="display: none;">
            <form method="post" class="modal-content animate container-add" id="disp" action="/inventory_Tracking/php/pserversdatabase.php">
                <a href="javascript:void(0)" class="closebtn" onclick="closeModal()">&times;</a><br>
                <label for="ASSET_TAG">ASSET TAG:</label>
                <input type="text" id="assetTag" name="asset_tag"><br><br>

                <label for="MODEL">MODEL:</label>
                <input type="text" id="model" make="model"><br><br>

                <label for="MAKE">MAKE:</label>
                <input type="text" id="make" name="make"><br><br>

                <label for="SERIAL">SERIALNUMBER:</label>
                <input type="text" id="serialnumber" name="serialnumber"><br><br>

                <label for="HOST">HOST:</label>
                <input type="text" id="host" name="host"><br><br>

                <label for="IP">IP ADDRESS</label>
                <input type="text" id="ip_address" name="ipaddress"><br><br>

                <input type="submit" value="Submit" id="Submit">
            </form>
        </div>
        <div id="editFormContainer" style="display: none;" class="modal">
            <h3>Edit Data</h3>
            <form id="editForm" method="POST" action="/inventory_Tracking/php/pserversdatabase.php?function=update" class="modal-content animate container-add">
                <a href="javascript:void(0)" class="closebtn" onclick="closeForm()">&times;</a><br>
                <label for="SERIAL">SERIALNUMBER:</label><br>
                <input type="text" id="editserialnumber" name="editserialnumber"><br><br>

                <label for="ASSET_TAG">ASSET TAG:</label>
                <input type="text" id="editassettag" name="editassettag"><br><br>

                <label for="MODEL">MODEL:</label>
                <input type="text" id="editmodel" make="editmodel"><br><br>

                <label for="MAKE">MAKE:</label>
                <input type="text" id="editmake" name="editmake"><br><br>

                <label for="HOST">HOST:</label>
                <input type="text" id="edithost" name="edithost"><br><br>

                <label for="IP">IP ADDRESS</label>
                <input type="text" id="editipaddress" name="editipaddress"><br><br>

                <button type="submit">Update</button>
            </form>
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
            <table id="mytable">
                <h2>DATA DISPLAY</h2>
                <thead>
                    <tr>
                        <th>SERIAL</th>
                        <th>ASSET TAG</th>
                        <th>MODEL</th>
                        <th>MAKE</th>
                        <th>HOST</th>
                        <th>IP ADDRESS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //fetch data from the database and iterate iver the rows
                    $conn = mysqli_connect('localhost', 'root', '', 'inventory');
                    $query = "SELECT * FROM pservers";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['serialnumber'] . "</td>";
                        echo "<td>" . $row['assettag'] . "</td>";
                        echo "<td>" . $row['model'] . "</td>";
                        echo "<td>" . $row['make'] . "</td>";
                        echo "<td>" . $row['host'] . "</td>";
                        echo "<td>" . $row['ipaddress'] . "</td>";
                        echo "<td>";
                        echo "<span class='edit-icon' onclick='editData(this)'><i class='fa-solid fa-edit'></i></span><br>";
                        echo "<span class='delete-icon' onclick='deleteData(this)'><i class='fa-solid fa-trash'></i></span>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/inventory_Tracking/scripts/shared.js"></script>
    <script src="/inventory_Tracking/scripts/pservers.js"></script>
</body>

</html>