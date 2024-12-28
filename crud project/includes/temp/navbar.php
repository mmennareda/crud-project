<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.php" class="logo">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li><a href="index.php" class="active">Home</a></li>
                        <li><a href="#">Category</a></li>
                        <li><a href="#">Listing</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li>

                            <?php
                            if (isset($_SESSION['user_login'])) {
                                echo '<div class="main-white-button"><a href="logout.php"> Logout</a></div>';
                            } else {
                                echo '<div class="d-flex">
                                <div class="main-white-button"><a href="login.php"> Login</a></div>'.'<div class="main-white-button"><a href="register.php"> Register</a></div>
                                </div>';
                            }
                            ?>
                        </li>
                    </ul>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>