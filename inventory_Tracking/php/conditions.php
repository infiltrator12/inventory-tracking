<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONDITION MONITORING</title>
    <meta name="keyword" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class="header">
        <i class="openSidenav" onclick="opens()">&#9776;</i>
        <div class="top">
            <a href="/inventory_tracking/php/dashboard.php" class="logo"><img src="/inventory_Tracking/assets/interswitch_logo.svg" width="300px" height="100px"></a>
        </div>
        <a href="/inventory_Tracking/php/conditions.php"><strong>CONDITION MONITORING</strong></a>
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
                </button>
            </div>
        </div>
        <div class="formfunc">
            <div class="impo">
                <input type="file" id="csvFileInput">
                <button onclick="upload()" class="u"><i class="fa-solid fa-file-import"></i>Upload CSV
                    FILE</button>
            </div>
            <button onclick="exportTableToCSV('myCSVFile.csv')" class="expo"><i class="fa-solid fa-file-excel"></i>Export to CSV</button>
        </div>
        <!-- <div class="modal" id="id01">
            <form method="post" class="modal-content animate container-add" id="disp" action="/inventory_Tracking/php/catalogdatabase.php?function=add">
                <a href="javascript:void(0)" class="closebtn" onclick="closeModal()">&times;</a><br>
                <label for="SERIAL_NUMBER">SERIAL NUMBER:</label><br>
                <input type="text" id="serialnumber" name="serialnumber" required /><br>


                <label for="ASSET_TAG">ASSET TAG:</label><br>
                <input type="text" id="assetTag" name="asset_tag" required /><br>

                <label for="MODEL">MODEL:</label><br>
                <input type="text" id="model" name="model"><br>

                <label for="STORAGE">STORAGE CAPACITY(IN GB)</label><br>
                <input type="text" id="storage" name="storage_capacity"><br>

                <label for="STORAGE_TYPE">STORAGE TYPE:</label><br>
                <input type="radio" id="SSD" name="storage_type" value="SSD">
                <label for="SSD">SSD</label><br>
                <input type="radio" id="HDD" name="storage_type" value="HDD">
                <label for="HDD">HDD</label><br><br>

                <label for="RAM_CAPACITY">RAM CAPACITY:</label><br>
                <input type="text" id="ramcapacity" name="ram_capacity" required /><br>

                <label for="RAM_CAPACITY">EMPLOYEE NAME:</label><br>
                <input type="text" id="employeename" name="employeename" required /><br>

                <label for="RAM_CAPACITY">EMPLOYEE NUMBER:</label><br>
                <input type="text" id="employeenumber" name="employeenumber" required /><br>

                <label for="DEPARTMENT">DEPARTMENT:</label><br>
                <input type="text" id="department" name="department" required /><br>

                <label for="DEPARTMENT">DATE ISSUED:</label><br>
                <input type="date" id="dateissued" name="dateissued" /><br>

                <label for="DEVICE_CONDITION">DEVICE CONDITION:</label><br>
                <input type="textbox" id="device_condition" name="device_condition"><br>

                <label for="ALLOCATION">ALLOCATION:</label><br>
                <input type="textbox" id="allocation" name="allocation"><br>

                <label for="DESCRIPTION">DESCRIPTION:</label><br>
                <input type="textbox" placeholder="Accessories tied to device e.g charger" id="description" name="description">
                <br><br>

                <input type="submit" value="Submit" id="Submit">
            </form>
        </div> -->
        <!-- <div id="editFormContainer" style="display: none;" class="modal">
            <h3>Edit Data</h3>
            <form id="editForm" method="POST" action="/inventory_Tracking/php/catalogdatabase.php?function=update">
                <a href="javascript:void(0)" class="closebtn" onclick="closeForm()">&times;</a><br>
                <label for="ID">SERIAL NUMBER:</label><br>
                <input type="text" id="editserialnumber" name="editserialnumber"><br><br>

                <label for="editName">ASSET TAG:</label><br>
                <input type="text" id="editassetTag" name="editassetTag"><br><br>

                <label for="editHost">MODEL:</label><br>
                <input type="text" id="editmodel" name="editmodel"><br><br>

                <label for="MAKE/TYPE">MAKE/TYPE:</label><br>
                <input type="text" id="editmake" name="editmake"><br><br>

                <label for="STORAGE TYPE">STORAGE CAPACITY:</label><br>
                <input type="text" id="editstoragecapacity" name="editstoragecapacity"><br><br>

                <label for="OS">STORAGE TYPE:</label><br>
                <select name="editstoragetype" id="editstoragetype">
                    <option value="environment">STORAGE TYPE</option>
                    <option value=""></option>
                    <option value="SSD">SSD</option>
                    <option value="HDD">HDD</option>
                </select><br><br>

                <label for="RAM CAPACITY">RAM CAPACITY:</label><br>
                <input type="text" id="editramcapacity" name="editramcapacity"><br><br>

                <label for="DEVICE CONDITION">DEVICE CONDITION:</label><br>
                <input type="text" id="editdevicecondition" name="editdevicecondition"><br><br>

                <label for="Allocation">ALLOCATION:</label><br>
                <input type="text" id="editallocation" name="editAllocation"><br><br>

                <label for="DESCRIPTION">DESCRIPTION:</label><br>
                <input type="text" id="editdescription" name="editdescription">

                <button type="submit">Update</button>
            </form>
        </div> -->
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
                            <th>EMPLOYEENUMBER</th>
                            <th>EMPLOYEENAME</th>
                            <th>CONDITION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = mysqli_connect('localhost', 'root', '', 'inventory');
                        $query = "SELECT * FROM conditions";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['serialnumber'] . "</td>";
                            echo "<td>" . $row['assettag'] . "</td>";
                            echo "<td>" . $row['employeenumber'] . "</td>";
                            echo "<td>" . $row['employeename'] . "</td>";
                            echo "<td>" . $row['conditions'] . "</td>";
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
<script src="/inventory_Tracking/scripts/conditions.js"></script>

</html>