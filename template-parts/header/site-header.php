<?php
/**
 * displays the site header
 *
 * @since GSquared 2022 1.0
 */
?>
 
 
<?php 
    $blogInfo = get_bloginfo('name');
    $description = get_bloginfo('description', 'display');
    $showTitle   = (true === get_theme_mod( 'display_title_and_tagline', true));
    $headerClass = $showTitle ? 'site-title' : 'screen-reader-text';
?>
<header id="site-header" role="banner" class="container">
    <div class="row">
        <div class="col-xl-8 offset-xl-2 col-lg-12">
            <?php if (has_custom_logo() && $showTitle): ?>
                <div class="site-logo"><?php the_custom_logo(); ?></div>
            <?php endif; ?>
            
            <h1 class="<?php echo esc_attr($headerClass); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($blogInfo); ?></a>
            </h1>
            
            <?php if ($description && true === get_theme_mod('display_title_and_tagline', true)) : ?>
        		<p class="site-description"><?php echo $description; ?></p>
        	<?php endif; ?>
        </div>
    </div>
</header><!-- #site-header -->
