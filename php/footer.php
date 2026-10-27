<?php

$footerPagePrefix = $footerPagePrefix ?? "../html/";
$footerVariant = $footerVariant ?? "full";
?>
<?php if ($footerVariant === "compact"): ?>
<footer class="studenthub-footer studenthub-footer--compact">
    <nav aria-label="Student account links">
        <a href="<?= $footerPagePrefix ?>index.html">Home</a>
        <a href="<?= $footerPagePrefix ?>dashboard.php">Dashboard</a>
        <a href="<?= $footerPagePrefix ?>profile.php">Profile</a>
        <a href="<?= $footerPagePrefix ?>contact.html">Help</a>
    </nav>
    <p class="studenthub-footer__legal">&copy; <?= date("Y") ?> StudentHub.</p>
</footer>
<?php elseif ($footerVariant === "minimal"): ?>
<footer class="studenthub-footer studenthub-footer--minimal">
    <p class="studenthub-footer__legal">&copy; <?= date("Y") ?> StudentHub.</p>
</footer>
<?php else: ?>
<footer class="studenthub-footer">
    <div class="studenthub-footer__inner">
        <div class="studenthub-footer__columns">
            <section class="studenthub-footer__column">
                <h2>About StudentHub</h2>
                <ul>
                    <li><a href="<?= $footerPagePrefix ?>about.html">About Us</a></li>
                    <li><a href="<?= $footerPagePrefix ?>events.html">Campus Events</a></li>
                    <li><a href="<?= $footerPagePrefix ?>admin.html">Administration</a></li>
                </ul>
            </section>
            <section class="studenthub-footer__column">
                <h2>My StudentHub</h2>
                <ul>
                    <li><a href="<?= $footerPagePrefix ?>profile.php">My Profile</a></li>
                    <li><a href="<?= $footerPagePrefix ?>dashboard.php">Dashboard</a></li>
                    <li><a href="<?= $footerPagePrefix ?>register.html">Create an Account</a></li>
                </ul>
            </section>
            <section class="studenthub-footer__column">
                <h2>Help &amp; FAQs</h2>
                <ul>
                    <li><a href="<?= $footerPagePrefix ?>faq.html">Frequently Asked Questions</a></li>
                    <li><a href="<?= $footerPagePrefix ?>contact.html">Contact Us</a></li>
                    <li><a href="mailto:info@studenthub.com">Email StudentHub</a></li>
                </ul>
            </section>
            <section class="studenthub-footer__column">
                <h2>Explore</h2>
                <ul>
                    <li><a href="<?= $footerPagePrefix ?>events.html">Upcoming Events</a></li>
                    <li><a href="<?= $footerPagePrefix ?>about.html">Student Resources</a></li>
                    <li><a href="<?= $footerPagePrefix ?>index.html">Home</a></li>
                </ul>
            </section>
        </div>
        <div class="studenthub-footer__bottom">
            <div class="studenthub-footer__connect">
                <h2>Connect</h2>
                <nav class="studenthub-footer__icons" aria-label="StudentHub quick links">
                    <a href="<?= $footerPagePrefix ?>index.html" aria-label="Home">⌂</a>
                    <a href="<?= $footerPagePrefix ?>events.html" aria-label="Events">▦</a>
                    <a href="<?= $footerPagePrefix ?>faq.html" aria-label="Help and FAQs">?</a>
                    <a href="mailto:info@studenthub.com" aria-label="Email StudentHub">✉</a>
                </nav>
            </div>
        </div>
        <p class="studenthub-footer__legal">&copy; <?= date("Y") ?> StudentHub. All rights reserved.</p>
    </div>
</footer>
<?php endif; ?>
