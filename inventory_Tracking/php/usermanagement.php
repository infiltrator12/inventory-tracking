<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>USER MANAGEMENT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css" />
    <style>
        /* CSS for the form container */
        .form-container {
            max-width: 400px;
            margin-top: 150px;
            margin-left: 400px;
            /* margin: 0 auto; */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* CSS for form labels */
        .form-container label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* CSS for form input fields */
        .form-container input,
        .form-container select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 12px;
        }

        /* CSS for the submit button */
        .form-container button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        /* CSS for the submit button on hover */
        .form-container button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="header">
        <button class="openSidenav" onclick="opens()">&#9776</button>
        <div class="top">
            <a href="/inventory_tracking/php/dashboard.php" class="logo"><img src="/inventory_tracking/assets/interswitch_logo.svg" width="200px" height="70px"></a>
        </div>
        <a href="/inventory_Tracking/usermanagement.html"><strong>USER MANAGEMENT</strong></a>
        <div class="header-right">
            <div class="toggle-container">
                <label class="toggle-switch">
                    <input type="checkbox" id="modeToggle" onclick="toggleMode()">
                    <span class="toggle-slider round"></span>
                </label>
                <span id="icon" class="light-mode-icon"></span>
            </div>
            <img src="/inventory_tracking/assets/profile.png" alt="avatar" class="avatar">
            <div class="dropdown">
                <button class="dropbtn">JOHN DOE
                </button>
            </div>
        </div>
    </div>
    <div id="main">
        <div class="sidenav" id="Sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closes()" style="color: white;">&times;</a><br><br>
            <div class="bottom">
                <a href="/inventory_Tracking/php/dashboard.php" id="dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i>DASHBOARD</a>
                <button class="dropdown-btn" id="dropdownbtn">
                    <i class="fa-solid fa-info"></i>DEVICE CATALOG<i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container" id="dropdowncont">
                    <a href="/inventory_Tracking/php/catalog.php" id="laptops"><i class="fa-solid fa-laptop"></i>LAPTOPS</a>
                    <a href="/inventory_Tracking/php/devices.php" id="networking_devices"><i class="fa-solid fa-network-wired"></i>NETWORKING DEVICES</a>
                    <a href="/inventory_Tracking/php/pservers.php" id="physical_servers"><i class="fa-solid fa-server"></i>PHYSICAL SERVERS</a>
                    <a href="/inventory_Tracking/php/vservers.php" id="virtual_servers"><i class="fa-solid fa-server"></i></i>VIRTUAL SERVERS</a>
                    <a href="/inventory_Tracking/php/phone.php" id="phones"><i class="fa-solid fa-phone"></i>PHONES</a>
                </div>
                <a href="/inventory_Tracking/php/history.php" id="device_history"><i class="fa fa-history" aria-hidden="true"></i> DEVICE HISTORY</a>
                <a href="/inventory_Tracking/php/users.php" id="users"><i class="fa fa-user" aria-hidden="true"></i>
                    USERS</a>
                <a href="/inventory_Tracking/php/allocation.php" id="allocation_checkout"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> ALLOCATION& CHECKOUT</a>
                <a href="/inventory_Tracking/php/conditions.php" id="condition_monitoring"><i class="fa fa-desktop"></i>
                    CONDITION MONITORING</a>
                <a href="/inventory_Tracking/php/usermanagement.php" id="user_management"><i class="fas fa-list-check"></i> USERMANAGEMENT</a>
                <a href="/inventory_Tracking/php/logout.php" id="out" onclick="logout()"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
            </div>
        </div>
        <div class="formfunc">
            <button type="submit" value="addNewDevice" class="button1" onclick="openModal()"><i class="fa fa-plus" aria-hidden="true"></i>ADD NEW USER
            </button>
            <button onclick="exportTableToCSV('myCSVFile.csv')" class="expo"><i class="fa-solid fa-file-excel"></i>Export to
                CSV</button>
        </div>
        <div class="formcontainer" id="id01" style="display: none;">
            <form method="POST" action="/inventory_Tracking/php/validate_credentials.php?function=add" class="modal-content animate container-add" id="disp" onclick="return validateForm()">
                <a href="javascript:void(0)" class="closebtn" onclick="closeModal()">&times;</a><br>

                <label for="USERNAME">USERNAME</label><br>
                <input type="text" name="username" id="username" autocomplete="new-username"><br><br>

                <label for="PASSWORD">PASSWORD</label><br>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" autocomplete="new-password">
                    <label for="showPassword" class="password-toggle" onclick="togglePasswordVisibility()">
                        <span class="eye-icon"></span>
                    </label>
                </div>
                <br><br>

                <label for="PASSWORD">CONFIRM PASSWORD</label><br>
                <input type="password" name="confirmpassword" id="confirmpassword" autocomplete="new-password"><br><br>

                <label for="ROLES">ROLE</label><br>
                <select name="roles" id="roles">
                    <option value=""></option>
                    <option value="ADMIN">ADMINISTRATOR</option>
                    <option value="USER">USER</option>
                </select><br><br>

                <button type="submit">SUBMIT</button><br>
            </form>
        </div>
        <div id="editFormContainer" style="display: none;" class="modal">
            <h3>Edit Data</h3>
            <form id="editForm" method="POST" action="/inventory_Tracking/device.php?function=update">
                <a href="javascript:void(0)" class="closebtn" onclick="closeForm()">&times;</a><br>
                <label for="editusername">USERNAME:</label><br>
                <input type="text" id="editusername" name="editusername" autocomplete="username"><br><br>

                <label for="ediroles">ROLES:</label><br>
                <select id="editroles" name="editroles">
                    <option value="TYPE">TYPE</option>
                    <option value="ADMIN">ADMINISTRATOR</option>
                    <option value="USER">USER</option>
                </select><br><br>

                <button type="submit">Update</button>
            </form>
        </div>
        <div class="data">
            <div class="marg table-container">
                <br><br>
                <h2>DATA DISPLAY</h2>
                <table id="mytable">
                    <thead>
                        <tr>
                            <th>USERNAME</th>
                            <th>ROLE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = mysqli_connect('localhost', 'root', '', 'inventory');
                        $query = "SELECT * FROM logins";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['roles'] . "</td>";
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
        <script src="/inventory_Tracking/scripts/shared.js"></script>
        <script src="/inventory_Tracking/scripts/usermanagement.js"></script>
</body>

</html>