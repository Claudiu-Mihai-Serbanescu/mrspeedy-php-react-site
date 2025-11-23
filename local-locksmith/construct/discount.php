    <!-- discount-->
    <section class="page-section mt-4" id="discount" style="margin: 0;">
      <div class="container">
        <div class="text-center">
          <h2 class="section-heading-discount text-uppercase">BOOK NOW! OAP & NHS</h2>
          <h3 class="section-discount">10% OFF DISCOUNT</h3>
          <a class="btn btn-warning btn-lg text-uppercase" href="tel:+4407846716954">Call now</a>
        </div>
      </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Explore Our Lock & Security Categories</h2>
            <div class="row g-4">

                <?php
                $categories = [
                    ["name" => "Nightlatches", "slug" => "nightlatches"],
                    ["name" => "Mortice Locks", "slug" => "mortice-locks"],
                    ["name" => "Window Locks", "slug" => "window-locks"],
                    ["name" => "Locking Bolts", "slug" => "locking-bolts"],
                    ["name" => "Door Entry Systems", "slug" => "door-entry-systems"],
                    ["name" => "Burglar Bars & Grilles", "slug" => "burglar-bars-grilles"],
                    ["name" => "Digital Locks", "slug" => "digital-locks"],
                    ["name" => "CISA Locks", "slug" => "cisa-locks"],

                ];

                foreach ($categories as $cat):
                    $img = "https://mrspeedy.co.uk/assets/img/categories/" . $cat['slug'] . ".jpg";
                    $link = "https://mrspeedy.co.uk/locksmith-locks/" . $cat['slug'] . "/index.php";
                ?>

                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="<?php echo $link; ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm border-0">
                                <img src="<?php echo $img; ?>" class="card-img-top" alt="<?php echo $cat['name']; ?>" style="object-fit: cover; height: 180px;">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo $cat['name']; ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </section>
