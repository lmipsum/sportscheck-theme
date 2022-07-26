<?php
/**
 * Template Name: Trainer Template
 */

global $category_ids;
global $category_slugs;
global $page_parent_slug;
$parent_page = get_post( the_parent_ID() );
$page_parent_slug = stripped_slug($parent_page);
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>

<div class="container">
  <div class="grid">
    <?php
      // last names order from post_title column
      $params = array(
        'orderby' => 'SUBSTRING_INDEX((post_title)," ",-1) ASC',
        'limit' => '-1'
      );
      $trainers = pods( 'trainer', $params );
    ?>
    <?php while( $trainers->fetch() ) : ?>
      <?php
      $category_ids = [];
      $category_slugs = [];

      $trainer_id = $trainers->field('id');
      $image_id = get_post_thumbnail_id($trainer_id);
      $tags = wp_get_post_tags($trainer_id);
      $category_ids = wp_get_post_categories($trainer_id);
      $trainer_bg = get_field('trainer_background_color', $trainer_id);
      $trainer_prof = get_field('trainer_profession', $trainer_id);
      $trainer_pdf = get_field('setcard', $trainer_id);

      foreach ($category_ids as $category_id) :
        $category_slugs[] = get_category( $category_id )->slug;
      endforeach;
      $primary_id = getPrimaryCategory($trainer_id);
      if ( in_array($page_parent_slug, $category_slugs) && !in_array('kontakt', $category_slugs) ) :
      ?>
      <div class="grid-item trainer col-xs-12 col-sm-6 col-md-4<?= ' ' . implode(" ", $category_slugs); ?>">
        <?php
          if (!empty($image_id)) :
          $image = wp_get_attachment_image_src($image_id,'large');
        ?>
          <div class="image-container" style="background-color: <?= !empty($trainer_bg) ? $trainer_bg : category2color($primary_id); ?>">
            <img src="<?= $image[0]; ?>" class="center-block"\>
          </div>
        <?php endif; ?>
        <div class="content col-xs-12">
          <h2 class="title text-primary text-uppercase"><?= $trainers->display('name'); ?></h2>
          <?php if (!empty($trainer_prof)) : ?>
            <h4 class="text-uppercase"><?= $trainer_prof; ?></h4>
          <?php endif; ?>
          <ul>
            <?php
              foreach ($tags as $tag) :
                echo '<li>' . str_replace('--',',',$tag->name) . '</li>';
              endforeach;
            ?>
          </ul>
          <?php if ( !empty($trainer_pdf) ) : ?>
            <a class="btn btn-primary" href="<?= wp_get_attachment_url( $trainer_pdf ); ?>" target="_blank"><?= __('Setcard', 'sage'); ?></a>
          <?php endif; ?>
        </div>
      </div>
    <?php
      endif;
      endwhile;
    ?>
  </div>
</div>
