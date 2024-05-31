<div id="sidebar" class="sidebar">
    <!-- Sidebar -->
    <div class="collapse show" id="sidebarCollapse">
        <div>
            <img src="./img/Frame.svg" alt="" class="img-fluid p-4">
            <div class="sidebar-menu-container mt-2">
                <ul class="sidebar-menu">
                    <?php
                    $currentPage = basename($_SERVER['REQUEST_URI']);
                    ?>
                    <li>
                        <a href="dashboard2.php" <?php if ($currentPage == 'dashboard2.php') echo 'class="active"'; ?>>
                            <img src="./img/menu.png" alt="Menu Icon" class="menu-icon">
                            Dashboard
                        </a>
                    </li>                
                    <li>
                        <a href="analytics.php" <?php if ($currentPage == 'analytics.php') echo 'class="active"'; ?>>
                            <img src="./img/analytics.png" alt="Analytics Icon" class="menu-icon">
                            Analytics
                        </a>
                    </li>
                    <li>
                        <a href="calendar.php" <?php if ($currentPage == 'calendar.php') echo 'class="active"'; ?>>
                            <img src="./img/calendar.png" alt="Calendar Icon" class="menu-icon">
                            Calendar
                        </a>
                    </li>
                    <li>
                        <a href="process.php" <?php if ($currentPage == 'process.php') echo 'class="active"'; ?>>
                            <img src="./img/processes.png" alt="Process Icon" class="menu-icon">
                            Processes
                        </a>
                    </li>
                    <li>
                        <a href="deploy.php" <?php if ($currentPage == 'deploy.php') echo 'class="active"'; ?>>
                            <img src="./img/settings.png" alt="Settings Icon" class="menu-icon">
                            Settings
                        </a>
                    </li>
                    <li>
                        <a href="AboutUs.php" <?php if ($currentPage == 'AboutUs.php') echo 'class="active"'; ?>>
                            <img src="./img/about.png" alt="About Us" class="menu-icon">
                            About Us
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="div">
            <div class="signout-container">
                <ul class="sidebar-menu">
                    <li style="text-align: center;">
                        <a href="logout.php">
                            <img src="./img/out.png" alt="Sign Out Icon" class="menu-icon">
                            Sign out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <button class="btn btn-dark btn-menu" type="button" data-toggle="collapse" data-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></style>
<style>
/* Sidebar styles */
.sidebar {
    width: 250px;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1;
    background-color: #1e2320;
    overflow-x: hidden;
    padding-top: 20px;
    height: 100vh; /* Adjust the height to fill the entire viewport */
}

.sidebar-menu-container {
    padding: 15px;
}

.sidebar-menu {
    list-style-type: none;
    padding: 0;
}

.sidebar-menu li {
    padding: 7px;
}

.sidebar-menu li a {
    padding: 10px 15px;
    text-decoration: none;
    font-size: 18px;
    color: #0fc041;
    display: block;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.sidebar-menu li a.active {
    background-color: #57e387;
    color: #1e2320;
}

.sidebar-menu li a:hover {
    color: #fff;
    border-radius: 0.375rem;
    background: #323232;
}

.menu-icon {
    height: 18px;
    margin-right: 10px;
}

.btn-menu {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 999;
}

/* Responsive styles */
@media (max-width: 991.98px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .btn-menu {
        display: block;
    }
}


</style>