<!DOCTYPE html>
<html lang="en">

<head>
    <title>USERS</title>
    <meta charset="UTF-8">
    <meta name="keyword" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css">
</head>

<body>
    <div class="header">
        <button class="openSidenav" onclick="opens()"> &#9776</button>
        <div class="top">
            <a href="/inventory_tracking/php/dashboard.php" class="logo"><img src="/inventory_tracking/assets/interswitch_logo.svg" width="300px" height="100px"></a>
        </div>
        <a href="/inventory_Tracking/php/users.php"><strong>USERS</strong></a>
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
                    <a href="/invenotry_Tracking/php/logout.php" id="out"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
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
                    <a href="/inventory_Tracking/php/catalog.php" id="laptops"><i class="fa-solid fa-laptop fa-2x"></i>LAPTOPS</a>
                    <a href="/inventory_tracking/php/devices.php" id="networking_devices"><i class="fa-solid fa-network-wired fa-2x"></i>NETWORKING DEVICES</a>
                    <a href="/inventory_tracking/php/pservers.php" id="physical_servers"><i class="fa-solid fa-server fa-2x"></i>PHYSICAL SERVERS</a>
                    <a href="/inventory_Tracking/php/vservers.php" id="virtual_servers"><i class="fa-solid fa-server fa-2x"></i>VIRTUAL SERVERS</a>
                    <a href="/inventory_Tracking/php/phone.php" id="phones"><i class="fa-solid fa-phone fa-2x"></i>PHONES</a>
                </div>
                <a href="/inventory_Tracking/php/history.php" id="device_history"><i class="fa fa-history fa-2x" aria-hidden="true"></i> DEVICE HISTORY</a>
                <a href="/inventory_Tracking/php/users.php" id="users"><i class="fa fa-user fa-2x" aria-hidden="true"></i> USERS</a>
                <a href="/inventory_Tracking/php/allocation.php" id="allocation_checkout"><i class="fa fa-credit-card-alt fa-2x" aria-hidden="true"></i>ALLOCATION&CHECKOUT</a>
                <a href="/inventory_Tracking/php/conditions.php" id="condition_monitoring"><i class="fa fa-desktop fa-2x"></i> CONDITION MONITORING</a>
                <a href="/inventory_Tracking/php/logout.php" id="out" onclick="logout()"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
            </div>
        </div>
        <div class="formfunc">
            <div class="impo">
                <input type="file" id="csvFileInput">
                <button onclick="handle()" class="u"><i class="fa-solid fa-file-import"></i>Upload CSV
                    FILE</button>
            </div>
            <button type="submit" value="addNewDevice" class="button1" onclick="openModal()"><i class="fa fa-plus" aria-hidden="true"></i>ADD NEW USER
            </button>
            <button onclick="exportTableToCSV('myCSVFile.csv')" class="expo"><i class="fa-solid fa-file-excel"></i>Export to
                CSV</button>
        </div>
        <div class="add_user" id="id01">
            <form method="POST" class="modal-content animate container-add" id="disp" action="/inventory_Tracking/php/usersdatabase.php?function=add">
                <span onclick="closeModal()" class="closebtn" title="Close Modal">&times;</span>
                <label for="Employee_Number">EMPLOYEE NUMBER:</label><br>
                <input type="text" id="employeenumber" name="employeenumber" required /><br><br>

                <label for="Employee_Surname">EMPLOYEE SURNAME:</label><br>
                <input type="text" id="employeesurname" name="employeesurname" /><br><br>

                <label for="Employee_FirstName">EMPLOYEE FIRST NAME:</label><br>
                <input type="text" id="employeefirstname" name="employeefirstname" /><br><br>

                <label for="Group">GROUP:</label><br>
                <select id="groups" name="groups">
                    <option value="Group">GROUP</option>
                    <option value="ISWKE">ISWKE</option>
                    <option value="SUPPORT">SUPPORT</option>
                </select><br><br>

                <label for="Department">DEPARTMENT:</label><br>
                <select id="department" name="department">
                    <option value="Department">Department</option>
                    <option value="Shared Technology">SHARED TECHNOLOGY</option>
                    <option value="Business Value Realization">BUSINESS VALUE REALIZATION</option>
                    <option value="HR & Admin">HR& ADMIN</option>
                    <option value="Executive">EXECUTIVE</option>
                    <option value="Finance and Supply Chain">FINANCE AND SUPPLY CHAIN</option>
                    <option value="Risk Management& Control">RISK MANAGEMENT & CONTROL</option>
                    <option value="Business Development Products">BUSINESS DEVELOPMENT PRODUCTS</option>
                    <option value="">SALES NETWORKS& MARKETING</option>
                </select><br><br>

                <input type="submit" value="Submit" id="Submit">
            </form>
        </div>
        <div id="editFormContainer" style="display: none;" class="modal">
            <h3>Edit Data</h3>
            <form id="editForm" method="POST" action="/inventory_Tracking/php/usersdatabase.php?function=update">
                <a href="javascript:void(0)" class="closebtn" onclick="closeForm()">&times;</a><br>
                <label for="EmployeeNumber">EMPLOYEE NUMBER:</label><br>
                <input type="text" id="editemployeenumber" name="editemployeenumber" /><br><br>

                <label for="EmployeeSurname">EMPLOYEE SURNAME:</label><br>
                <input type="text" id="editemployeesurname" name="editemployeesurname" /><br><br>

                <label for="EmployeeFirstName">EMPLOYEE FIRST NAME:</label><br>
                <input type="text" id="editemployeefirstname" name="editemployeefirstname" /><br><br>

                <label for="Group">GROUP:</label><br>
                <select id="editgroups" name="editgroups">
                    <option value="Group">GROUP</option>
                    <option value="ISWKE">ISWKE</option>
                    <option value="SUPPORT">SUPPORT</option>
                </select><br><br>

                <label for="Department">DEPARTMENT:</label><br>
                <select id="editdepartment" name="editdepartment">
                    <option value="Department">Department</option>
                    <option value="Shared Technology">SHARED TECHNOLOGY</option>
                    <option value="Business Value Realization">BUSINESS VALUE REALIZATION</option>
                    <option value="HR & Admin">HR& ADMIN</option>
                    <option value="Executive">EXECUTIVE</option>
                    <option value="Finance and Supply Chain">FINANCE AND SUPPLY CHAIN</option>
                    <option value="Risk Management& Control">RISK MANAGEMENT & CONTROL</option>
                    <option value="Business Development Products">BUSINESS DEVELOPMENT PRODUCTS</option>
                    <option value="">SALES NETWORKS& MARKETING</option>
                </select><br><br>

                <button type="submit">Update</button>
            </form>
        </div>
        <div class="data">
            <div class="align">
                <div id="searchResults" class="search-param">
                    <form method="GET" class="searchbar" id="searchForm">
                        <input type="text" placeholder="SEARCH" id="searchInput">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </div><br><br>
            <div class="marg table-container">
                <h2>DATA DISPLAY</h2><br>
                <table id="mytable">
                    <thead>
                        <tr>
                            <th>EmployeeNumber</th>
                            <th>EmployeeSurname</th>
                            <th>EmployeeFirstName</th>
                            <th>Group</th>
                            <th>Department</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch data from the database and iterate over the rows
                        $connection = mysqli_connect('localhost', 'root', '', 'inventory');
                        $query = "SELECT * FROM users";
                        $result = mysqli_query($connection, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['employeenumber'] . "</td>";
                            echo "<td>" . $row['employeesurname'] . "</td>";
                            echo "<td>" . $row['employeefirstname'] . "</td>";
                            echo "<td>" . $row['groups'] . "</td>";
                            echo "<td>" . $row['department'] . "</td>";
                            echo "<td>";
                            echo "<span class='edit-icon' onclick='edit(this)'><i class='fa-solid fa-edit'></i></span><br>";
                            echo "<span class='delete-icon' onclick='deleted(this)'><i class='fa-solid fa-trash'></i></span>";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
<script src="/inventory_Tracking/scripts/users.js"></script>
<script src="/inventory_Tracking/scripts/shared.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>

</html>