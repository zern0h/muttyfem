<div class="navbar">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link">
                <i class="fas fa-bars" onclick="collapseSidebar()"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link logo">Muttyfem Supermarket</a>
        </li>
    </ul>


    <h2>Welcome, <?= $_SESSION['user_name'] ?></h2>
    <ul class="navbar-nav nav-right">
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="switchTheme()">
                <i class="fas fa-moon dark-icon"></i>
                <i class="fas fa-sun light-icon"></i>
            </a>
        </li>

        <li class="nav-item avt-wrapper">
            <div class="avt dropdown">
                <img src="images/user.png" alt="User image" class="dropdown-toggle" data-toggle="user-menu">
                <ul id="user-menu" class="dropdown-menu">
                    <?php

                    if($_SESSION['type'] === "Super_Admin" ){ 
                        echo '<li class="dropdown-menu-item">
                            <a href="barcode.php" target="_blank" class="dropdown-menu-link">
                                <div>
                                    <i class="fas fa-print"></i>
                                </div>
                                <span>Print Labels</span>
                            </a>
                        </li>
                        <li class="dropdown-menu-item">
                            <a href="batch_price_sticker.php" target="_blank" class="dropdown-menu-link">
                                <div>
                                    <i class="fas fa-print"></i>
                                </div>
                                <span>Batch Print Labels</span>
                            </a>
                        </li>
                        <li class="dropdown-menu-item">
                            <a href="hamper_barcode_generator.php" target="_blank" class="dropdown-menu-link">
                                <div>
                                    <i class="fas fa-file"></i>
                                </div>
                                <span>Print Hamper Labels</span>
                            </a>
                        </li>
                        <li class="dropdown-menu-item">
                            <a href="cashier_daily_report.php" target="_blank" class="dropdown-menu-link">
                                <div>
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <span>Daily Cashier Report</span>
                            </a>
                        </li>
                         <!--li class="dropdown-menu-item">
                            <a href="expiry.php" target="_blank" class="dropdown-menu-link">
                                <div>
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <span>Expiry/Damage Report</span>
                            </a>
                        </li-->
                        <li class="dropdown-menu-item">
                            <a href="hamper_show.php" target="_blank" class="dropdown-menu-link">
                                <div>
                                    <i class="fas fa-shopping-basket"></i>
                                </div>
                                <span>Hamper</span>
                            </a>
                        </li>';
                        
                    }
                    ?>
                    <li class="dropdown-menu-item">
                        <a href="logout.php" class="dropdown-menu-link">
                            <div>
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>

</div>