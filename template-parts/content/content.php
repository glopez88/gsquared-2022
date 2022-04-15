<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    
    <div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__('Page', 'gsquared2022') . '">',
				'after'    => '</nav>',
				'pagelink' => esc_html__('Page %', 'gsquared2022'),
			)
		);
		?>
	</div><!-- .entry-content -->
    
    <?php if (get_edit_post_link()): ?>
		<footer class="entry-footer default-max-width">
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
		</footer><!-- .entry-footer -->
	<?php endif; ?>
    
</article>