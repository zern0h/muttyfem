
<div class="sidebar">
    <ul class="sidebar-nav">

        <?php
           
            if($_SESSION['type'] === "Admin" ){ 
                    echo '<li class="sidebar-nav-item">
                        <a href="purchase_order.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-truck-loading"></i>
                            </div>
                            <span>Purchase Order</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                    <a href="pod.php" class="sidebar-nav-link">
                        <div>
                            <i class="fas fa-truck"></i>
                        </div>
                        <span>Delivery</span>
                    </a>
                </li>';
            }

            if($_SESSION['type'] === "Sub_Admin" ){ 
              
                echo  '<li class="sidebar-nav-item">
                    <a href="pod.php" class="sidebar-nav-link">
                        <div>
                            <i class="fas fa-truck"></i>
                        </div>
                        <span>Delivery</span>
                    </a>
                </li>';
            }

            if($_SESSION['type'] === "Super_Admin" ){ 
                echo '<li class="sidebar-nav-item">
                        <a href="index.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="categories.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-bars"></i>
                            </div>
                            <span>Category</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="hamper_order.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <span>Hamper Order</span>
                        </a>               
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="pod.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-truck"></i>
                            </div>
                            <span>Delivery</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="procurements.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span>Procurement</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="products.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-trademark"></i>
                            </div>
                            <span>Products</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="purchase_order.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-truck-loading"></i>
                            </div>
                            <span>Purchase Order</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="payment.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-money-check"></i>
                            </div>
                            <span>Order Payment</span>
                        </a>               
                    </li>

                    <!--li class="sidebar-nav-item">
                        <a href="refund.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-long-arrow-alt-right"></i>
                            </div>
                            <span>Reverse Transaction</span>
                        </a>               
                    </li-->

                    <li class="sidebar-nav-item">
                        <a href="report.php"  class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <span>Reports</span>
                        </a>   
                                
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="orders.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <span>Order</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="suppliers.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <span>Suppliers</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="sub_categories.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-sort-down"></i>
                            </div>
                            <span>Sub Category</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item">
                        <a href="extra.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-arrows-alt-v"></i>
                            </div>
                            <span>Update Quantity</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="users.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-user-alt"></i>
                            </div>
                            <span>Users</span>
                        </a>
                    </li>';
                   
            }

            if($_SESSION['type'] === "Cashier"){ 
                echo '<li class="sidebar-nav-item">
                    <a href="history.php" class="sidebar-nav-link">
                        <div>
                            <i class="fas fa-history"></i>
                        </div>
                        <span>Cashier History</span>
                    </a>
                </li>

                <li class="sidebar-nav-item">
                    <a href="pos.php" class="sidebar-nav-link">
                        <div>
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <span>POS</span>
                    </a>
                </li>

                <li class="sidebar-nav-item">
                    <a href="hamper_pos.php" class="sidebar-nav-link">
                        <div>
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <span>Hamper Sales</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="hamper_invoice_history.php" class="sidebar-nav-link">
                        <div>
                            <i class="fas fa-clock"></i>
                        </div>
                        <span>Hamper Invoice History</span>
                    </a>
                </li>';
        
            }

        ?>
        
        
       
    </ul>
</div>