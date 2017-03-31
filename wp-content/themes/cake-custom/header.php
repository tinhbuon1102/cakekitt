<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>
<?php wp_head(); ?>
<script src="https://use.typekit.net/ctm3qcw.js"></script>
<script>
var gl_templateUrl = '<?= get_stylesheet_directory_uri(); ?>';
var gl_ajaxUrl = '<?= admin_url('admin-ajax.php');  ?>';
var gl_siteUrl = '<?= get_site_url();  ?>';
var gl_timeAM = '<?= _e('AM', 'cake');  ?>';
var gl_timePM = '<?= _e('PM', 'cake');  ?>';
var gl_stateAllowed = '東京都';
var gl_alertStateNotAllowed = '<?php echo __('Site support Tokyo prefecture only, sorry for inconvenience !', 'cake')?>';
try{Typekit.load({ async: true });}catch(e){}</script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700" rel="stylesheet">
</head>

<body <?php body_class(); ?> >
<?php //global $template;echo '==' .$template;?>
<?php 
$layout = get_theme_mod('cake_layout_type', 'fullwidth');

?>

<div class="ip-container <?php echo esc_attr($layout); ?>" id="ip-container">

<?php do_action('cake_header_section');?>
