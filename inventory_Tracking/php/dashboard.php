<!DOCTYPE html>
<html lang="en">

<head>
    <title>TRACKING_PORTAL</title>
    <meta name="keyword" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/inventory_Tracking/styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="/inventory_Tracking/scripts/shared.js"></script>
    <style>
    #bargraph {
        position: relative;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
    }
    </style>
</head>

<body class="body">
    <div class="header">
        <button class="openSidenav" type="button" onclick="opens()">&#9776 </button>
        <div class="top">
            <a href="/inventory_Tracking/php/dashboard.php" class="logo"><img
                    src="/inventory_Tracking/assets/interswitch_logo.svg" width="200px" height="70px"></a>
        </div>
        <a href="/inventory_Tracking/php/dashboard.php"><strong>DASHBOARD</strong></a>
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
                <button class="dropbtn" id="userbutton">JOHN DOE
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="/inventory_Tracking/php/logout.php" id="out"><i
                            class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
                </div>
            </div>
        </div>
    </div>
    <div id="main">
        <div class="sidenav" id="Sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closes()" style="color: white;">&times;</a><br><br>
            <div class="bottom">
                <a href="/inventory_Tracking/php/dashboard.php" id="dashboard"><i class="fa fa-tachometer"
                        aria-hidden="true"></i>DASHBOARD</a>
                <button class="dropdown-btn" id="dropdownbtn">
                    <i class="fa-solid fa-info"></i>DEVICE CATALOG<i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container" id="dropdowncont">
                    <a href="/inventory_Tracking/php/catalog.php" id="laptops"><i
                            class="fa-solid fa-laptop"></i>LAPTOPS</a>
                    <a href="/inventory_Tracking/php/devices.php" id="networking_devices"><i
                            class="fa-solid fa-network-wired"></i>NETWORKING DEVICES</a>
                    <a href="/inventory_Tracking/php/pservers.php" id="physical_servers"><i
                            class="fa-solid fa-server"></i>PHYSICAL SERVERS</a>
                    <a href="/inventory_Tracking/php/vservers.php" id="virtual_servers"><i
                            class="fa-solid fa-server"></i></i>VIRTUAL SERVERS</a>
                    <a href="/inventory_Tracking/php/phone.php" id="phones"><i class="fa-solid fa-phone"></i>PHONES</a>
                </div>
                <a href="/inventory_Tracking/php/history.php" id="device_history"><i class="fa fa-history"
                        aria-hidden="true"></i> DEVICE HISTORY</a>
                <a href="/inventory_Tracking/php/users.php" id="users"><i class="fa fa-users"
                        aria-hidden="true"></i></i>
                    USERS</a>
                <a href="/inventory_Tracking/php/allocation.php" id="allocation_checkout"><i
                        class="fa fa-credit-card-alt" aria-hidden="true"></i> ALLOCATION& CHECKOUT</a>
                <a href="/inventory_Tracking/php/conditions.php" id="condition_monitoring"><i class="fa fa-desktop"></i>
                    CONDITION MONITORING</a>
                <a href="/inventory_Tracking/php/usermanagement.php" id="user_management"><i class="fa fa-desktop"></i>
                    USERMANAGEMENT</a>
                <a href="/inventory_Tracking/php/logout.php" id="out" onclick="logout()"><i
                        class="fa-solid fa-right-from-bracket"></i>LOGOUT</a>
            </div>
        </div>
        <div class="element">
            <h2>INVENTORY SUMMARY</h2>
            <button type="submit" value="addNewDevice" class="invent"><i class="fa fa-plus" aria-hidden="true"></i>ADD
                INVENTORY
            </button>
            <div class="change">
                <a href="/inventory_Tracking/php/catalog.php" id="laptops"><i class="fa-solid fa-laptop fa-3x"></i></a>
                <a href="/inventory_Tracking/php/devices.php" id="devices"><i
                        class="fa-solid fa-network-wired fa-3x"></i></a>
                <a href="/inventory_Tracking/php/phone.php" id="phones"><i class="fa-solid fa-phone fa-3x"></i></a>
                <a href="/inventory_Tracking/php/devices.php" id="printers"><i class="fa fa-print fa-3x"
                        aria-hidden="true"></i></a>
                <a href="/inventory_Tracking/php/pservers.php" id="monitors"><i
                        class="fa-solid fa-server fa-3x"></i></a>
            </div>
        </div>
        <div class="devicelist">
            <div class="devicelist1">
                <strong>
                    <h1>USERS</h1>
                </strong><br>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'inventory');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT COUNT(*) AS count FROM users";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $count3 = $row['count'];
                ?>
                <i class="fa fa-users fa-7x" aria-hidden="true"></i>
                <p>The total number of users: <?php echo $count3; ?></p>
            </div>
            <div class="devicelist2">
                <strong>
                    <h1>SERVERS</h1>
                </strong>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'inventory');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT COUNT(*) AS count FROM pservers";
                $sql1 = "SELECT COUNT(*) AS count FROM vservers";
                $result = $conn->query($sql);
                $result1 = $conn->query($sql1);
                $row2 = $result->fetch_assoc();
                $row3 = $result1->fetch_assoc();
                $count6 = $row2['count'];
                $count5 = $row3['count'];
                ?>
                <i class="fa-solid fa-server fa-7x"></i>
                <i class="fa fa-cloud-upload fa-7x" aria-hidden="true"></i><br>
                <p>The number of physical servers is <?php echo $count6; ?> <br> the number of
                    virtual servers is <?php echo $count5; ?></p>
            </div>
        </div>
        <section class="box">
            <div class="device_count">
                <?php
                $conn = new mysqli('localhost', 'root', '', 'inventory');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT COUNT(*) AS count FROM laptops";
                $sql1 = "SELECT COUNT(*) AS count FROM devices WHERE type= 'PRINTER' ";
                $result = $conn->query($sql);
                $result1 = $conn->query($sql1);
                $row = $result->fetch_assoc();
                $row1 = $result1->fetch_assoc();
                $count = $row['count'];
                $count1 = $row1['count'];
                ?>
                <div>
                    <h2><u>DEVICE COUNT</u></h2>
                    <div class="chart-container">
                        <canvas id="chartjs_pie"></canvas>
                    </div>
                </div>
            </div>
            <div class="available_devices">
                <h2><u>DEVICES IN USE<u></h2>
            </div>
            <div class="devices_in_use">
                <?php
                $conn = new mysqli('localhost', 'root', '', 'inventory');
                $sql0 = "SELECT COUNT(*) AS count0 FROM laptops WHERE allocation = 'ALLOCATED'";
                $sql2 = "SELECT COUNT(*) AS count0 FROM laptops WHERE allocation = 'FREE'";
                $retval = $conn->query($sql0);
                $retval1 = $conn->query($sql2);
                $row2 = $retval->fetch_assoc();
                $row0 = $retval1->fetch_assoc();
                $count0 = $row2['count0'];
                $count2 = $row0['count0'];
                ?>
                <div>
                    <div class="chart-container">
                        <canvas id="bargraph"></canvas>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <div class="footer-content">
                <p>&copy; 2023 TRACKING PORTAL. All rights reserved.</p>
                <div class="social-media-icons">
                    <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-messenger"></i></a>
                    <a href="https://twitter.com/home?lang=en"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
        </footer>
    </div>
</body>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/inventory_Tracking/scripts/shared.js"></script>
<script>
//retrieve data from php variables
var laptopCount = <?php echo $count; ?>;
var printerCount = <?php echo $count1; ?>;

//prepare the data for a pie chart
var pieData = {
    labels: ['Laptops', 'Printers'],
    datasets: [{
        data: [laptopCount, printerCount],
        backgroundColor: ['#1A237E', '#212121']
    }]
};

//create a pie chart
var ctxpie = document.getElementById("chartjs_pie").getContext('2d');
new Chart(ctxpie, {
    type: 'pie',
    data: pieData,
    options: {
        maintainAspectRatio: false
    }
});

var laptopuse = <?php echo $count0; ?>;
var laptopnot = <?php echo $count2; ?>;

var Data = {
    labels: ['laptops_in_use', 'laptops_not_in_use'],
    datasets: [{
        data: [laptopuse, laptopnot],
        backgroundColor: ['#1A237E', '#666666']
    }]
};

var xtcpie = document.getElementById("bargraph").getContext('2d');
new Chart(xtcpie, {
    type: 'pie',
    data: Data,
    options: {
        maintainAspectRatio: false
    }
});
</script>

</html>