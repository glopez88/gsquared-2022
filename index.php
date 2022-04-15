<?php
/**
 * The default page template 
 * Displays all of the <head> section until the #main 
 *
 * @since GSquared 2022 1.0
 */

get_header();

if (have_posts()) {
	// Load posts loop.
	while (have_posts()) {
		the_post();
        
		get_template_part('template-parts/content/content');
	}
    ?>
    <div class="nav-previous alignleft"><?php previous_posts_link('Older posts'); ?></div>
    <div class="nav-next alignright"><?php next_posts_link('Newer posts'); ?></div>
    <?
} else {
	get_template_part('template-parts/content/no-content');
}

get_footer();

