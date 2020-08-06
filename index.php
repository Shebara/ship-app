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
        <div id="spinner" class="spinner-border<?php if ( ! $spin ) echo ' d-none' ?>" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div id="message" class="d-none">
            <h3>Error!</h3>
            <p>An unknown error has occurred.</p>
        </div>
        <div id="page" class="<?php if ( $spin || ! $path ) echo ' d-none' ?> container">
		    <?php if ( $path ) require_once $path; ?>
        </div>
    </div>
<?php
//TODO extract spinner and error
// Include footer
require_once 'includes/footer.php';
