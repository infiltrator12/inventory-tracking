<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css">
    <meta name="keyword" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <button class="openSidenav" onclick="opens()">&#9776</button>
        <div class="top">
            <a href="/inventory_tracking/php/dashboard.php" class="logo"><img src="/inventory_tracking/assets/interswitch_logo.svg" width="200px" height="70px"></a>
        </div>
        <a href="/inventory_Tracking/php/vservers.php" class="a"><strong>VIRTUAL SERVERS</strong></a>
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
        <div class="sidenav" id="Sidenav" style="display: none;">
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
                <a href="/inventory_Tracking/php/allocation.php" id="allocation_checkout"><i class="fa fa-credit-card-alt" aria-hidden="true"></i>ALLOCATION & CHECKOUT</a>
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
            <form method="POST" class="modal-content animate container-add" id="disp" action="/inventory_Tracking/php/vserversdatabase.php?function=add">
                <span onclick="closeModal()" class="close" title="Close Modal" style="float: right;">&times;</span><br>
                <label for="NAME">NAME:</label><br>
                <input type="text" name="name" id="input"><br><br>

                <label for="HOST">HOST:</label><br>
                <input type="text" id="input" name="host"><br><br>

                <label for="PROVINSIONED SPACE">PROVINSIONED SPACE:</label><br>
                <input type="text" id="input" name="provisionedspace"><br><br>

                <label for="USED SPACE">USED SPACE:</label><br>
                <input type="text" id="input" name="usedspace"><br><br>

                <label for="OS">OPERATING SYSTEM:</label><br>
                <input type="text" id="input" name="os"><br><br>

                <label for="OS">MEMORY SIZE:</label><br>
                <input type="text" id="input" name="memorysize"><br><br>

                <label for="IP">IP ADDRESS:</label><br>
                <input type="text" id="input" name="ipaddress"><br><br>

                <label for="APPLICATION">APPLICATION:</label><br>
                <input type="text" id="input" name="application"><br><br>

                <label for="ENVIRONMENT">ENVIRONMENT:</label><br>
                <select name="environment" id="environment">
                    <option value="environment">ENVIRONMENT</option>
                    <option value=""></option>
                    <option value="cde">CDE</option>
                    <option value="non-cde">NON-CDE</option>
                    <option value="dmz">DMZ</option>
                    <option value="offsite">OFFSITE</option>
                </select><br><br>
                <button value="submit" id="submit">SUBMIT</button>

            </form>
        </div>
        <div id="editFormContainer" style="display: none;" class="modal">
            <h3>Edit Data</h3>
            <form id="editForm" method="POST" action="/inventory_Tracking/php/vserversdatabase.php?function=update" class="modal-content animate container-add">
                <a href="javascript:void(0)" class="closebtn" onclick="closeForm()">&times;</a><br>
                <label for="ID">ID:</label><br>
                <input type="text" id="editID" name="editID"><br>

                <label for="editName">Name:</label><br>
                <input type="text" id="editName" name="editName"><br>

                <label for="editHost">Host:</label><br>
                <input type="text" id="editHost" name="editHost"><br>

                <label for="PROVINSIONED SPACE">PROVINSIONED SPACE(GB):</label><br>
                <input type="text" id="editProvisionedspace" name="editProvisionedspace"><br>

                <label for="USED SPACE">USED SPACE(GB):</label><br>
                <input type="text" id="editUsedspace" name="editUsedspace"><br>

                <label for="OS">OPERATING SYSTEM:</label><br>
                <input type="text" id="editos" name="editos"><br>

                <label for="MEMORY SIZE">MEMORY SIZE:</label><br>
                <input type="text" id="editMemorysize" name="editMemorysize"><br>

                <label for="IP">IP ADDRESS:</label><br>
                <input type="text" id="editIPAddress" name="editIPAddress"><br>

                <label for="APPLICATION">APPLICATION:</label><br>
                <input type="text" id="editApplication" name="editApplication"><br>

                <label for="ENVIRONMENT">ENVIRONMENT:</label><br>
                <select name="editEnvironment" id="editEnvironment">
                    <option value="environment">ENVIRONMENT</option>
                    <option value=""></option>
                    <option value="cde">CDE</option>
                    <option value="non-cde">NON-CDE</option>
                    <option value="dmz">DMZ</option>
                    <option value="offsite">OFFSITE</option>
                </select><br><br>
                <button type="submit">Update</button>
            </form>
        </div>
        <div class="data">
            <div class="align">
                <div id="searchResults" class="search-param">
                    <form method="POST" class="searchbar" id="searchForm">
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Host</th>
                            <th>Provisioned Space</th>
                            <th>Used Space</th>
                            <th>Operating System</th>
                            <th>MEMORY SIZE</th>
                            <th>IP Address</th>
                            <th>Application</th>
                            <th>Environment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $connection = mysqli_connect('localhost', 'root', '', 'inventory');
                        $query = "SELECT * FROM vservers";
                        $result = mysqli_query($connection, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['host'] . "</td>";
                            echo "<td>" . $row['provisionedspace'] . "</td>";
                            echo "<td>" . $row['usedspace'] . "</td>";
                            echo "<td>" . $row['operatingsystem'] . "</td>";
                            echo "<td>" . $row['memorysize'] . "</td>";
                            echo "<td>" . $row['ipaddress'] . "</td>";
                            echo "<td>" . $row['application'] . "</td>";
                            echo "<td>" . $row['environment'] . "</td>";
                            echo "<td>";
                            echo "<span class='edit-icon' onclick='editData(this)'><i class='fa-solid fa-edit'></i></span><br>";
                            echo "<span class='delete-icon' onclick='deleteData(this)'><i class='fa-solid fa-trash'></i></span>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        mysqli_close($connection);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.core.min.js"></script>
    <script src="/inventory_Tracking/scripts/script.js"></script>
    <script src="/inventory_Tracking/scripts/shared.js"></script>
</body>

</html>