    <!-- Zonele si harta-->
    <div class="page-section-table mt-4" id="areas">
        <div class="container">

            <div class="row ">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-left">
                                <h3 class="section-heading ">Are You Looking For Locksmiths Near You?</h3>
                                <h5 class="section-subheading ">Find our Locksmith service in your Birmingham district:</h5>
                            </div>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search location name or post code...">

                            <!-- Scrollable Table -->
                            <div class="scrollable-table">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>

                                            <th scope="col">Name</th>
                                            <th scope="col">PostCode</th>
                                            <th scope="col">Call</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        $index = 1;
                                        foreach ($zones as $zone) {
                                        ?>
                                            <tr>

                                                <td><?php echo htmlspecialchars($zone['name']); ?></td>
                                                <td><?php echo htmlspecialchars($zone['postCode']); ?></td>
                                                <td><a href="tel:0753434538" class="btn btn-warning">Call Now</a> </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Scrollable Table -->

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-2  d-none d-lg-block">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d155455.61262017404!2d-1.8636314999999999!3d52.497349150000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487080d43225d7fd%3A0x8dc86fdb3abbf3e7!2sWest%20Midlands!5e0!3m2!1sen!2suk!4v1716464495329!5m2!1sen!2suk" width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" data-rocket-lazyload="fitvidscompatible" data-lazy-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d155455.61262017404!2d-1.8636314999999999!3d52.497349150000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487080d43225d7fd%3A0x8dc86fdb3abbf3e7!2sWest%20Midlands!5e0!3m2!1sen!2suk!4v1716464495329!5m2!1sen!2suk" data-ll-status="loaded" class="entered lazyloaded"></iframe>
                </div>

            </div>
        </div>
    </div>