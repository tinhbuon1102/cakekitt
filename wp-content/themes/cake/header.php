<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> onload="initialize();">

<?php 
$layout = get_theme_mod('cake_layout_type', 'fullwidth');

?>

<div class="ip-container <?php echo esc_attr($layout); ?>" id="ip-container">

<?php do_action('cake_header_section');?>