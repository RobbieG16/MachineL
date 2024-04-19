<div id="sidebar" class="sidebar d-flex flex-column justify-content-between">

<!-- Sidebar -->
    <div>
        <div class="sidebar-header">
            <h3><span>MMSU <img src="./img/wheat-grains.png" alt="Wheat Grains" class="wheat-logo"></span></h3>
            <h6><span>Auto Cropping Calendar</span></h6>
        </div>
        <div class="sidebar-menu-container">
            <ul class="sidebar-menu">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="analytical.php">Analytics</a></li>
                <li><a href="calendar.php">Calendar</a></li>
                <li><a href="process.php">Processes</a></li>
                <li><a href="deploy.php">Settings</a></li>
                <!-- <li><a href="logout.php">Sign out</a></li> -->
                
            </ul>
        </div>
    </div>
    <div class="div">
        <div class="signout-container">
            <ul class="sidebar-menu">
                <li style="text-align: center;"><a href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Hamburger menu button for small screens -->
<div class="hamburger-menu-btn" onclick="toggleSidebar()">
    <span>&#9776;</span>
</div>

<script>
    // JavaScript function to toggle the sidebar
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        sidebar.style.display = (sidebar.style.display === 'none' || sidebar.style.display === '') ? 'block' : 'none';
    }

    // JavaScript to set the active class dynamically
    var currentPage = location.pathname.split("/").pop();
    var links = document.querySelectorAll('.sidebar-menu a');
    for (var i = 0; i < links.length; i++) {
        if (links[i].getAttribute('href') === currentPage) {
            links[i].classList.add('active');
        }
    }
</script>

<style>
    .sidebar-menu-container {
        padding: 15px;
    }
    /* .signout-container {
        padding-top: 170px;
    } */
    /* Sidebar styles */
    .sidebar {
        height: 100%;
        width: 250px;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1;
        background-color: #111;
        overflow-x: hidden;
        padding-top: 20px;
    }

    .sidebar-header {
        padding: 10px;
        color: #0fc041;
        text-align: center;
    }
    .sidebar-header h3 span {
        display: inline-block;
        width: 100%;
        text-align: center;
    }
    .sidebar-menu {
        list-style-type: none;
        padding: 0;
    }

    .sidebar-menu li {
        margin-bottom: 5px;
    }

    .sidebar-menu li a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 18px;
        color: #0fc041;
        display: block;
        border-radius: 5px;
    }

    .sidebar-menu li a.active {
        background-color: #06ba57;
        color: #fff;
    }

    .sidebar-menu li a:hover {
        color: #fff;
        background-color: gray;
    }

    .wheat-logo {
        width: 20px; /* Adjust width as needed */
        height: 20px; /* Match the font height */
        margin-left: 5px; /* Adjust the margin as needed */
        vertical-align: middle; /* Align the logo vertically with the text */
    }

    /* Responsive styles */
    @media screen and (max-width: 700px) {
        .sidebar {
            width: 0;
            display: none;
            height: auto;
            position: relative;
        }

        .sidebar a {
            float: left;
        }

        div.content {
            margin-left: 0;
        }

        /* Hamburger menu button styles */
        .hamburger-menu-btn {
            display: block;
            cursor: pointer;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 2;
            color: black;
            font-size: 24px;
        }
    }

    @media screen and (max-width: 400px) {
        .sidebar a {
            text-align: center;
            float: none;
        }
    }
</style>
