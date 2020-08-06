</div>
<?php if ( $page !== '404' ) : ?>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/<?php echo $page ?>.js" defer></script>
    <?php
    if ( strpos( $page, 'edit' ) !== FALSE ) {
        $page = str_replace( 'edit', 'new', $page );
        echo "<script src=\"assets/js/$page.js\"></script>";
    }
    ?>
<?php endif; ?>
</body>
</html>