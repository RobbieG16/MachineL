<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>Crop Calendar</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php">Dashboard</a></li>
        <!-- <li><a href="sensor.php">Sensor</a></li> -->
        <li><a href="analytical.php">Analytic</a></li>
        <li><a href="calendar.php">Calendar</a></li>
        <li><a href="process.php">Processes</a></li>
        <li><a href="deploy.php">Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Hamburger menu button for small screens -->
<div class="hamburger-menu-btn" onclick="toggleSidebar()">
    <span>&#9776;</span>
</div>

<style>
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
        background: forestgreen;
        color: white;
        text-align: center;
    }

    .sidebar-menu {
        list-style-type: none;
        padding: 0;
    }

    .sidebar-menu li a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 18px;
        color: #ddd;
        display: block;
    }

    .sidebar-menu li a:hover {
        color: #fff;
        background-color: forestgreen;
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

<script>
    // JavaScript function to toggle the sidebar
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        sidebar.style.display = (sidebar.style.display === 'none' || sidebar.style.display === '') ? 'block' : 'none';
    }
</script>
