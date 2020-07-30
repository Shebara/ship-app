<?php
// Check if authenticated
require_once 'includes/auth.php';
// Get universal root URL for asset inclusion
require_once 'includes/root.php';
// Get universal variables
require_once 'includes/vars.php';
// Include header
require_once 'includes/header.php';
// Include menu
require_once 'includes/menu.php';
?>
    <h1 class="text-break"><?php echo $title ?></h1>
    <div class="content">
		<?php
		// Include page path
		require_once $path;
		?>
    </div>
<?php
// Include footer
require_once 'includes/footer.php';
