<?php
/**
 * The theme header. 
 * Displays all of the <head> section until the #main 
 *
 * @since GSquared 2022 1.0
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <title><?php wp_title(); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <?php 
        if (is_singular() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <?php get_template_part('template-parts/header/site-header' ); ?>
        
    	<div id="content" class="site-content">
    		<div id="primary" class="content-area">
    			<main id="main" class="site-main" role="main">