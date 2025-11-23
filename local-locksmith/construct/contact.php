
<!-- Contact-->
<section class="page-section" id="contact">
    <div class="container">

        <div class="row">
            <div class="col-md-11">
                <div class="text-start">
                    <h2>Contact MrSpeedy Today – Your Local Emergency Locksmith</h2>
                    <a class="btn btn-light btn-lg mb-2" href="tel:+4407846716954">Contact Us</a>
                    <h3 class="contact-description mb-2">For any inquiries or to schedule an appointment, feel free<span style="color: #ffc107;"> to contact us </span>using the form below<span style="color: #ffc107;"> by phone, or by email.</span><br> We strive to respond promptly and<span style="color: #ffc107;"> offer customized solutions</span> tailored to each situation.</h3>
                    <p>Don’t let a lockout ruin your day or night. MrSpeedy is your fast, friendly, and professional locksmith in <?php echo $locationName; ?> . Whether it’s your home or business, we’ll get you secure again in no time.</p>
                </div>
            </div>
        </div>
        <!-- Contact Information Boxes -->
        <div class="row text-center mb-2 mt-4">

            <div class="col-md-4">
                <div class="info-box">
                    <div class="info-box-content">
                        <h4> Call Us</h4>
                        <p>07846 716 954</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <div class="info-box-content">
                        <h4>Email</h4>
                        <p>mrspeedylocksmith@mail.com</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <div class="info-box-content">
                        <h4>Address</h4>
                        <p>172 Gravelly Hill, Birmingham B23 7PF</p>
                    </div>
                </div>
            </div>
        </div>

        <form id="contactForm" method="POST" action="message.php">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <!-- Name input-->
                        <input class="form-control" id="name" name="name" type="text" placeholder="Your Name *" data-sb-validations="required" />
                        <div class="invalid-feedback" data-sb-feedback="name:required">A name is required.</div>
                    </div>
                    <div class="form-group">
                        <!-- Email address input-->
                        <input class="form-control" id="email" name="email" type="email" placeholder="Your Email *" data-sb-validations="required,email" />
                        <div class="invalid-feedback" data-sb-feedback="email:required">An email is required.</div>
                        <div class="invalid-feedback" data-sb-feedback="email:email">Email is not valid.</div>
                    </div>
                    <div class="form-group mb-md-0">
                        <!-- Phone number input-->
                        <input class="form-control" id="phone" name="phone" type="tel" placeholder="Your Phone *" data-sb-validations="required" />
                        <div class="invalid-feedback" data-sb-feedback="phone:required">A phone number is required.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <!-- Message input-->
                        <textarea class="form-control" id="message" name="message" placeholder="Your Message *" data-sb-validations="required"></textarea>
                        <div class="invalid-feedback" data-sb-feedback="message:required">A message is required.</div>
                    </div>
                </div>
            </div>

            <!-- Submit Button-->
            <div class="text-center">
                <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="submit">Send Message</button>
            </div>
        </form>
        <!-- Afișează mesajul de mulțumire dacă mesajul a fost trimis -->
        <?php if (isset($_GET['sent']) && $_GET['sent'] == 1): ?>
            <div class="alert alert-success text-center" role="alert">
                Thank you for your message! We will get back to you shortly.
            </div>
        <?php endif; ?>
        <br>
        <div class="row mt-5">
            <div class="col-md-4">
                <h3>Emergency Service</h3>
                <p>Our support available to help you 24 hours a day, seven days a week.</p>
                <ul>
                    <li>OPEN 24H</li>
                    <li>7 DAYS A WEEK</li>
                </ul>
            </div>
            <div class="col-md-8">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2428.1813952213865!2d-1.854931122885556!3d52.51205623681715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bb36a754e7c1%3A0xaf76a3c6003f79bb!2s172%20Gravelly%20Hill%2C%20Birmingham%20B23%207PF%2C%20Regatul%20Unit!5e0!3m2!1sro!2sro!4v1727820504365!5m2!1sro!2sro" width="100%" height="350" style="border: 15;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" data-rocket-lazyload="fitvidscompatible" data-lazy-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2428.1813952213865!2d-1.854931122885556!3d52.51205623681715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bb36a754e7c1%3A0xaf76a3c6003f79bb!2s172%20Gravelly%20Hill%2C%20Birmingham%20B23%207PF%2C%20Regatul%20Unit!5e0!3m2!1sro!2sro!4v1727820504365!5m2!1sro!2sro" data-ll-status="loaded" class="entered lazyloaded"></iframe>

            </div>
            <!--
                <div class="col-md-3">
                    <h3>WORKING HOURS</h3>
                    <p>Monday: 7:00am – 11:00pm<br>
                        Tuesday:  7:00am – 11:00pm<br>
                        Wednesday: 7:00am – 11:00pm<br>
                        Thursday:  7:00am – 11:00pm<br>
                        Friday:  7:00am – 11:00pm<br>
                        Saturday:  7:00am – 11:00pm<br>
                        Sunday: 8:00am – 6:00pm</p>
                </div>
                -->
        </div>
    </div>
</section>