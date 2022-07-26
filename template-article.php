<?php
/**
 * Template Name: List Page w/o Filter
 */
?>
<?php use Roots\Sage\Titles; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <div class="section section-header">
    <div class="container">
      <div class="col-xs-12 text-center">
        <h1 class="title text-primary text-uppercase"><?= Titles\title(); ?></h1>
        <?php
        $subtitle = get_field('subtitle');
        if ( !empty($subtitle) && isset($subtitle) ) : ?>
          <h3 class="subtitle"><?= $subtitle ?></h3>
        <?php endif; ?>
      </div>
      <?php
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $filter = get_field('category');
        $filtered_args = array( 'posts_per_page' => 8, 'paged' => $paged, 'category__in' => $filter, 'post_type' => 'post' );
        $news_posts_filtered = new WP_Query( $filtered_args );
      ?>
    </div>
  </div>
  <div id="posts" class="section">
    <div class="container">
      <div class="col-xs-12">
        <?php
        while ( $news_posts_filtered->have_posts() ) : setup_postdata($news_posts_filtered->the_post()); ?>
          <div class="row post">
            <?php if ( has_post_thumbnail() ) : $class_helper = 8; ?>
              <div class="text-right col-xs-4"><?php the_post_thumbnail('full', ['class'=>'img-responsive']); ?></div>
            <?php endif; ?>
            <div class="col-xs-12 col-md-<?= $class_helper; ?>">
              <div class="date">
                <span><?php the_time('d'); ?></span>
                <span><?php the_time('.m'); ?></span>
                <span><?php the_time('.Y'); ?></span>
              </div>
              <h3 class="post-title text-primary text-uppercase"><?php the_title(); ?></h3>
              <div class="content">
                <?php
                if( has_excerpt() ) :
                  the_excerpt();
                  ?>
                  <p><a href="<?= get_permalink(); ?>" class="text-uppercase"><?= __('Read More', 'sage'); ?></a></p>
                  <?php
                else :
                  the_content();
                endif;
                ?>
                <?php if ( get_field('external_link', get_the_ID()) && get_field('external_link', get_the_ID()) != '#' ) : ?>
                  <p><a href="<?= get_field('external_link', get_the_ID() ); ?>" target="_blank" class="text-uppercase"><?= __('Read More', 'sage'); ?></a></p>
                <?php elseif ( get_field('external_link', get_the_ID()) && get_field('external_link', get_the_ID()) == '#' ) : ?>
                <?php else : ?>
                  <p><a href="<?= get_permalink(); ?>" class="text-uppercase"><?= __('Read More', 'sage'); ?></a></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
        <?php numeric_posts_nav($news_posts_filtered); ?>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>