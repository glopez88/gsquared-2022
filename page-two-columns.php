<?php
/*
Template Name: Two-Columns Layout
Template Post Type: page
*/

get_header();

while (have_posts()) {
	the_post();
	get_template_part('template-parts/content/content-two-columns');
}

get_footer();

