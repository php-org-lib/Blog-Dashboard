</main>
<footer class="footer py-4 custom-bg-dark position-relative mt-7">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    &copy; &nbsp; <script type="text/javascript">
                        document.write(new Date().getFullYear())
                    </script>, by Kevin Stradtman
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="../index.php" class="nav-link text-ubuntu-condensed-bold text-gradient text-primary">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="../about.php" class="nav-link text-ubuntu-condensed-bold text-gradient text-primary">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="../privacy_policy.php" class="nav-link text-ubuntu-condensed-bold text-gradient text-primary">Privacy Policy</a>
                    </li>
                    <li class="nav-item">
                        <a href="../terms_and_conditions.php" class="nav-link text-ubuntu-condensed-bold text-gradient text-primary">Terms &amp; Conditions</a>
                    </li>
                    <li class="nav-item">
                        <a href="../contact.php" class="nav-link text-ubuntu-condensed-bold text-gradient text-primary">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>


<script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/dragula/dragula.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/jkanban/jkanban.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/chartjs.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/datatables.js" type="text/javascript"></script>
<script src="../assets/js/app.js" type="text/javascript"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>

<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="../assets/js/material-dashboard.min.js" type="text/javascript"></script>
<script type="text/javascript">
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', () => {
            console.log('Accordion toggled:', button.textContent.trim());
        });
    });
</script>
</body>
</html>