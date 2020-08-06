<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $assets ?>css/main.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <?php
        if ( ! empty( $_GET[ 'id' ] ) ) {
            $id = intval( $_GET[ 'id' ] );
            echo "<script>window.id = $id;</script>";
        }
    ?>
    <script>
        /**
         * Password comparison for bootstrap
         *
         * @param field
         * @param field2 - second field
         * @param text - invalid text
         */
        function passwordCompare( field, field2, text ) {
            field.setCustomValidity( field.value !== field2.value ? text : "" );
        }
    </script>
    <meta name="description" content="<?php echo $metas[ 'desc' ] ?>">
    <meta name="title" content="<?php echo $metas[ 'title' ] ?>">
    <meta name="keywords" content="<?php echo $metas[ 'keywords' ] ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="icon" href="<?php echo $assets ?>images/home.svg" type="image/svg">
	<title><?php echo $metas[ 'title' ] ?></title>
    <base href="<?php echo $root ?>/">
    <link rel="canonical" href="" />
    <meta name="robots" content="<?php echo $index ? '' : 'no' ?>index" />
</head>
<body>
<div class="canvas">