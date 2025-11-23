<?php
// prefix pentru localhost vs. producție
$BASE = $BASE ?? (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? '/mrspeedy' : '');
?>
<header id="header" class="d-flex align-items-center">
    <div class="container">
        <a href="<?= $BASE ?>/" class="navbar-brand">
            <img src="<?= $BASE ?>/assets/img/navbar-logo2.svg" class="logo" alt="Mr Speedy Locksmith" />
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link" href="<?= $BASE ?>/">Home</a></li>

                <li class="dropdown">
                    <a class="nav-link">
                        <span>
                            Services
                            <i class="fa-solid fa-angle-down" style="margin-left:.25rem;font-size:.8rem"></i>
                        </span>
                    </a>
                    <ul>
                        <li><a class="dropdown-item" href="<?= $BASE ?>/locksmith-birmingham/lock-replacement-and-installation.php">Lock Replacement or Installation</a></li>
                        <li><a class="dropdown-item" href="<?= $BASE ?>/locksmith-birmingham/burglary-repairs-property-boarding.php">Burglary Repairs &amp; Property Boarding</a></li>
                        <!-- fișierul tău are & în nume; folosim %26 -->
                        <li><a class="dropdown-item" href="<?= $BASE ?>/locksmith-birmingham/Non-Destructive-Entry-%26-Snapped-Key-Extraction.php">Non-Destructive Entry &amp; Snapped Key Extraction</a></li>
                        <li><a class="dropdown-item" href="<?= $BASE ?>/locksmith-birmingham/upvc-repairs.php">UPVC Repairs</a></li>
                        <li><a class="dropdown-item" href="<?= $BASE ?>/locksmith-birmingham/Fire-Doors-Roller-Shutters-Gates-Garage-Doors.php">Fire &amp; Garage Doors, Shutters, Gates</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item fw-bold text-primary" href="<?= $BASE ?>/locksmith-birmingham/index.php">See All Services</a></li>
                    </ul>
                </li>

                <li><a class="nav-link" href="<?= $BASE ?>/locksmith-emergency/locksmith-service.php">24/7 Emergency locksmith</a></li>
                <li><a class="nav-link" href="<?= $BASE ?>/local-locksmith/index.php">Areas</a></li>
                <li><a class="nav-link" href="<?= $BASE ?>/locksmith-locks/index.php">Locks</a></li>
                <li><a class="nav-link" href="<?= $BASE ?>/about-locksmith/index.php">About</a></li>
                <li><a class="nav-link" href="<?= $BASE ?>/contact-locksmith/index.php">Contact</a></li>
            </ul>
            <i class="mobile-nav-toggle"><i class="fas fa-bars"></i></i>
        </nav>
    </div>
</header>