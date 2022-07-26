<?php
  global $background_color;
  if( !is_page() ) :
    $category_ids = get_the_category();
    $category_parent_id = $category_ids[0]->category_parent;
    $background_color = category2color($category_parent_id);
    $back_link = get_permalink( get_page_by_slug($category_ids[0]->slug) );
  endif;
  if( is_page() ) :
    $parent_page = get_post( the_parent_ID() );
    $parent_slug = $parent_page->post_name;
    $background_color = pageid2color(the_parent_ID());
    $back_link = get_permalink( get_page_by_slug($parent_slug) );
  endif;
?>

<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <?php if( is_page() ) : ?>
    <?php get_template_part('templates/module', 'gallery'); ?>
    <?php endif; ?>
    <div class="section" <?php if( !is_page() ) { ?>style="background-color: <?= $background_color ?>"<?php }; ?>>
      <div class="container">
        <?php //if( !is_page() || ( is_page_template('template-contentpage.php') && !is_page(29) ) ) : ?>
        <?php if( !is_page() ) : ?>
          <div class="nav_back col-xs-12 text-center text-uppercase"><a href="javascript:window.history.go(-1);"><?= __('Back', 'sage'); ?></a></div>
        <?php endif; ?>
        <?php if( !is_page() || is_page(57) ) : ?>
          <header class="text-center text-uppercase col-xs-12">
            <h1 class="entry-title title center-block"><?php the_title(); ?></h1>
            <?php //get_template_part('templates/entry-meta'); ?>
          </header>
        <?php endif; ?>
        <?php if ( !is_page() && has_post_thumbnail() ) : ?>
          <div class="post-featured col-xs-10 col-md-6 col-xs-push-1 col-md-push-0"><?php the_post_thumbnail('full', ['class'=>'img-responsive']); ?></div>
        <?php endif; ?>
        <?php if( !is_page() ) : ?>
        <div class="post-excerpt col-xs-12 col-md-<?= (has_post_thumbnail()) ? 6 : 12; ?>">
          <div><?php the_excerpt() ?></div>
          <?php if( !is_page() || ( is_page_template('template-contentpage.php') && !is_page(57)) ) : ?>
            <div class="text-right">
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?= get_permalink(); ?>" class="fb-button btn btn-info share" style="background-color:#6986c9">
                <span class="icon icon-facebook">F</span><span><?= __('Share', 'sage'); ?></span>
              </a>
              <a rel="canonical" class="twitter-button btn btn-info share" style="background-color: #57cdff"
                 href="https://twitter.com/intent/tweet?url=<?= get_permalink(); ?>">
                <span class="icon icon-twitter">T</span><span>Tweet</span></a>
              <a href="mailto:?subject=<?php the_title(); ?>&body=<?php the_title() ?>%0A%0A<?= esc_url( get_permalink() ) ?>" class="btn icon-share btn-primary">+</a>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="entry-content col-xs-12">
          <?php the_content(); ?>
        </div>
        <footer class="col-xs-12">
          <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
        </footer>
        <?php comments_template('/templates/comments.php'); ?>
      </div>
    </div>
    <?php if( !is_page() ) : ?>
      <?php get_template_part('templates/module', 'gallery'); ?>
    <?php endif; ?>
  </article>
<?php endwhile; ?>
