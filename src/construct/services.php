<?php
// prefix pentru localhost vs. producție (la fel ca în menu.php)
$BASE = $BASE ?? (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? '/mrspeedy' : '');
?>
<section class="page-section" id="services">
    <div class="container">
        <div class="row text-center d-flex justify-content-center">
            <div class="col-md-9 text-center">
                <h3 class="section-heading text-uppercase"><u>Services</u></h3>
                <p class="section-subheading text-muted">
                    I offer a diverse range of services for both domestic and commercial customers. Friendly, approachable and considerate with fair pricing policy. Agreed to all costs
                    prior to work being undertaken - No surprises, No call out fee. All work caries my personal guarantee and manufacturers aswell. DBS checked and fully insured for your
                    peace of mind.
                </p>
                <hr class="mb-4 mt-4" />
            </div>

            <!-- Lock Replacement and Installation -->
            <div class="col-md-4 col-6 mb-4 mt-4">
                <a href="<?= $BASE ?>/locksmith-birmingham/lock-replacement-and-installation.php">
                    <img
                        src="<?= $BASE ?>/assets/img/services/repair-round.jpg"
                        alt="Lock Replacement"
                        class="service-img"
                        loading="lazy"
                        decoding="async" />
                </a>
                <h5>
                    Lock Replacement and
                    <br />
                    Installation
                </h5>
                <a href="<?= $BASE ?>/locksmith-birmingham/lock-replacement-and-installation.php" class="btn btn-outline-primary" style="background-color:#004360">Read More</a>
            </div>

            <!-- Burglary Repairs & Property Boarding -->
            <div class="col-md-4 col-6 mb-4 mt-4">
                <a href="<?= $BASE ?>/locksmith-birmingham/burglary-repairs-property-boarding.php">
                    <img
                        src="<?= $BASE ?>/assets/img/services/burglary-round.jpg"
                        alt="Burglary Repairs"
                        class="service-img"
                        loading="lazy"
                        decoding="async" />
                </a>
                <h5>
                    Burglary Repairs &
                    <br />
                    Property Boarding
                </h5>
                <a href="<?= $BASE ?>/locksmith-birmingham/burglary-repairs-property-boarding.php" class="btn btn-outline-primary" style="background-color:#004360">Read More</a>
            </div>

            <!-- Fire doors, Shutters, Gates, Garages -->
            <div class="col-md-4 col-6 mb-4 mt-4">
                <a href="<?= $BASE ?>/locksmith-birmingham/Fire-Doors-Roller-Shutters-Gates-Garage-Doors.php">
                    <img
                        src="<?= $BASE ?>/assets/img/services/fire-round.jpg"
                        alt="Fire Doors"
                        class="service-img"
                        loading="lazy"
                        decoding="async" />
                </a>
                <h5>
                    Fire doors, Shutters,
                    <br />
                    Gates, Garages
                </h5>
                <a href="<?= $BASE ?>/locksmith-birmingham/Fire-Doors-Roller-Shutters-Gates-Garage-Doors.php" class="btn btn-outline-primary" style="background-color:#004360">Read More</a>
            </div>

            <!-- Non-destructive entry & Snapped Key Extraction -->
            <div class="col-md-6 col-6 mb-4 mt-4">
                <a href="<?= $BASE ?>/locksmith-birmingham/Non-Destructive-Entry-%26-Snapped-Key-Extraction.php">
                    <img
                        src="<?= $BASE ?>/assets/img/services/extraction-round.jpg"
                        alt="Non-Destructive Entry"
                        class="service-img"
                        loading="lazy"
                        decoding="async" />
                </a>
                <h5>
                    Non-destructive entry &
                    <br />
                    Snapped Key Extraction
                </h5>
                <a href="<?= $BASE ?>/locksmith-birmingham/Non-Destructive-Entry-%26-Snapped-Key-Extraction.php" class="btn btn-outline-primary" style="background-color:#004360">Read More</a>
            </div>

            <!-- UPVC Repairs -->
            <div class="col-md-6 col-6 mb-4 mt-4">
                <a href="<?= $BASE ?>/locksmith-birmingham/upvc-repairs.php">
                    <img
                        src="<?= $BASE ?>/assets/img/services/upvc-round.jpg"
                        alt="UPVC Repairs"
                        class="service-img"
                        loading="lazy"
                        decoding="async" />
                </a>
                <h5>UPVC Repairs</h5>
                <a href="<?= $BASE ?>/locksmith-birmingham/upvc-repairs.php" class="btn btn-outline-primary" style="background-color:#004360">Read More</a>
            </div>
        </div>
    </div>
</section>