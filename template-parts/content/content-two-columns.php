<?php 
    $contentBeforeMain = get_field('content_before_two_columns');
    
    if ($contentBeforeMain) {
        $contentBeforeMain = apply_filters('content_before_two_columns', $contentBeforeMain);
        
        echo $contentBeforeMain;
    }
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('container'); ?>>
    <div class="row">
        <div class="col-md-4 offset-md-1 col-sm-10 offset-sm-1">
            <?php if (has_post_thumbnail()): ?>
                <!-- <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="img-fluid" /> -->
                <?php // echo get_the_post_thumbnail(null, 'large'); ?>
                <div class="post-featured-image" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');"></div>
            <?php endif; ?>
        </div>
        <div class="col-md-6 col-sm-10 offset-md-1 content-container">
            <div class="d-flex align-items-center h-100">
                <div class="entry-container">
                    <?php if (get_field('show_page_title')): ?>
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    <?php endif; ?>
                    
                    <div class="entry-content">
                		<?php
                        $quoteBlock = get_field('page_quote_block'); 
                        
                        if ($quoteBlock) {
                            echo '<p class="page-quote-block">' . esc_html($quoteBlock) . '</p>'; 
                        }
                        
                		the_content();

                		wp_link_pages(
                			array(
                				'before'   => '<nav class="page-links" aria-label="' . esc_attr__('Page', 'gsquared2022') . '">',
                				'after'    => '</nav>',
                				'pagelink' => esc_html__('Page %', 'gsquared2022'),
                			)
                		);
                		?>
                    </div>
                    
                    <?php if (get_edit_post_link()): ?>
                		<p class="edit-content-link text-end">
                			<?php
                			edit_post_link(
                				sprintf(
                					esc_html__('Edit %s', 'gsquared2022'),
                					'<span class="screen-reader-text">' . get_the_title() . '</span>'
                				),
                				'<span class="edit-link">',
                				'</span>'
                			);
                			?>
                		</p><!-- .entry-footer -->
                	<?php endif; ?>
            	</div><!-- .entry-content -->
            </div>
        </div>
    
</article>