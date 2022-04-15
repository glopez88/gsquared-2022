<section class="no-results not-found">
    <header class="page-header alignwide">
		<?php if (is_search()): ?>

			<h1 class="page-title">
				<?php
				printf(
					/* translators: %s: Search term. */
					esc_html__('Results for "%s"', 'gsquared2022'),
					'<span class="page-description search-term">' . esc_html(get_search_query()) . '</span>'
				);
				?>
			</h1>

		<?php else: ?>
			<h1 class="page-title"><?php esc_html_e('Nothing here', 'gsquared2022'); ?></h1>
		<?php endif; ?>
	</header><!-- .page-header -->
    
    <div class="page-content default-max-width">
        <?php if (is_search()) : ?>
			<p><?php esc_html_e('Sorry, no matches found. Please try again with another keyword.', 'gsquared2022'); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
            <p><?php esc_html_e('We can\'t find what you are looking for. Try searching below: ', 'gsquared2022'); ?></p>
			<?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>