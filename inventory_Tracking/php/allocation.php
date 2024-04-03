<!DOCTYPE html>
<html lang="en">

<head>
    <title>Allocation & Checkout</title>
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css" />
    <meta name="keyword" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="/inventory_Tracking/scripts/shared.js"></script>
</head>

<body>
    <div class="header">
        <button class="openSidenav" onclick="opens()">&#9776</button>
        <div class="top">
            <a href="/inventory_tracking/php/dashboard.php" class="logo"><img src="/inventory_Tracking/assets/interswitch_logo.svg" width="300px" height="100px"></a>
        </div>
        <a href="/inventory_Tracking/php/catalog.php"><strong>ALLOCATION & CHECKOUT</strong></a>
        <div class="header-right">
            <div class="toggle-container">
                <label class="toggle-switch">
                    <input type="checkbox" id="modeToggle" onclick="toggleMode()">
                    <span class="toggle-slider round"></span>
                </label>
                <span id="icon" class="light-mode-icon">
                </span>
            </div>
            <img src="/inventory_tracking/assets/Hostess characters.jpg" alt="avatar" class="avatar">
            <div class="dropdown">
                <button class="dropbtn">JOHN DOE</button>
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
                    <a href="/inventory_tracking/php/devices.php" id="networking_devices"><i class="fa-solid fa-network-wired"></i>NETWORKING DEVICES</a>
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
                <button onclick="upload()" class="u"><i class="fa-solid fa-file-import"></i>Upload CSV
                    FILE</button>
            </div>
            <button type="submit" value="addNewDevice" class="button1" onclick="openModal()"><i class="fa fa-plus" aria-hidden="true"></i>ADD NEW DEVICE
            </button>
            <button onclick="exportTableToCSV('myCSVFile.csv')" class="expo"><i class="fa-solid fa-file-excel"></i>Export to CSV</button>
        </div>
        <div class="modal" id="id01" style="display: none;">
            <form method="post" class="modal-content animate container-add" id="disp" action="/inventory_Tracking/php/allocationsdatabase.php?function=add">
                <a href="javascript:void(0)" class="closebtn" onclick="closeModal()">&times;</a><br>
                <label for="SERIAL_NUMBER">SERIAL NUMBER:</label><br>
                <input type="text" id="serialnumber" name="serialnumber" required /><br>


                <label for="ASSET_TAG">ASSET TAG:</label><br>
                <input type="text" id="assetTag" name="asset_tag" required /><br>

                <label for="MODEL">EMPLOYEE NUMBER:</label><br>
                <input type="text" id="employeenumber" name="employeenumber"><br>

                <label for="STORAGE">EMPLOYEE NAME:</label><br>
                <input type="text" id="employeename" name="employeename"><br>

                <label for="STORAGE TYPE">ALLOCATION DATE:</label><br>
                <input type="date" id="allocationdate" name="allocationdate"><br><br>

                <label for="OS">DEALLOCATION DATE:</label><br>
                <input type="date" id="deallocationdate" name="deallocationdate"><br><br>

                <input type="submit" value="Submit" id="Submit">
            </form>
        </div>
        <div id="editFormContainer" style="display: none;" class="modal">
            <h3>Edit Data</h3>
            <form id="editForm" method="POST" action="/inventory_Tracking/php/allocationsdatabase.php?function=update" class="modal-content animate container-add">
                <br><br><a href="javascript:void(0)" class="closebtn" onclick="closeForm()">&times;</a><br><br>
                <label for="ID">SERIAL NUMBER:</label><br>
                <input type="text" id="editserialnumber" name="editserialnumber"><br><br>

                <label for="editName">ASSET TAG:</label><br>
                <input type="text" id="editassettag" name="editassettag"><br><br>

                <label for="editHost">EMPLOYEE NUMBER:</label><br>
                <input type="text" id="editemployeenumber" name="editemployeenumber"><br><br>

                <label for="MAKE/TYPE">EMPLOYEE NAME:</label><br>
                <input type="text" id="editemployeename" name="editemployeename"><br><br>

                <label for="STORAGE TYPE">ALLOCATION DATE:</label><br>
                <input type="date" id="editallocationdate" name="editallocationdate"><br><br>

                <label for="OS">DEALLOCATION DATE:</label><br>
                <input type="date" id="editdeallocationdate" name="editdeallocationdate"><br><br>

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
            <div class="marg table-container">
                <h2>DATA DISPLAY</h2><br>
                <table id="mytable">
                    <thead>
                        <tr>
                            <th>SERIALNUMBER</th>
                            <th>ASSET TAG</th>
                            <th>EMLOYEE NUMBER</th>
                            <th>EMPLOYEE NAME</th>
                            <th>ALLOCATION DATE</th>
                            <th>DEALLOCATION DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = mysqli_connect('localhost', 'root', '', 'inventory');
                        $query = "SELECT * FROM allocations";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['serialnumber'] . "</td>";
                            echo "<td>" . $row['assettag'] . "</td>";
                            echo "<td>" . $row['employeenumber'] . "</td>";
                            echo "<td>" . $row['employeename'] . "</td>";
                            echo "<td>" . $row['allocationdate'] . "</td>";
                            echo "<td>" . $row['deallocationdate'] . "</td>";
                            echo "<td>";
                            echo "<span class='edit-icon' onclick='editData(this)'><i class='fa-solid fa-edit'></i></span><br>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
<script src="/inventory_Tracking/scripts/shared.js"></script>
<script src="/inventory_Tracking/scripts/allocation.js"></script>

</html>