<?php
global $background_color;
$images = (!is_page() /*|| is_page_template('template-contentpage.php')*/) ? get_field('post_gallery') : get_field('general_gallery_module', 'option');
$title = (!is_page() /*|| is_page_template('template-contentpage.php')*/) ? get_field('post_gallery_title') : get_sub_field('title');
$subtitle = (!is_page() /*|| is_page_template('template-contentpage.php')*/) ? get_field('post_gallery_subtitle') : get_sub_field('subtitle');

if ( is_page_template('template-contentpage.php') ) {
  $title = get_the_title();
}

if( $images ):
?>
<?php
  $isanyimageallowed = false;
  foreach( $images as $image ) :
    if ( isContentAllowed($image['id'], false) || is_front_page() ) :
    //if ( (is_front_page() || is_home()) || (is_page() && is_page_template('template-contentpage.php') && isContentAllowed($image['id'], false)) || !is_page() ) :
      $isanyimageallowed = true;
    endif;
  endforeach;
?>
    <?php if ($isanyimageallowed) { ?>
<div class="section module gallery" style="background-color: <?= $background_color; ?>;">
  <div class="top hidden-xs hidden-sm">
    <div class="container">
      <div class="row">
        <div class="content text-center col-xs-12">
          <?php if ( !empty($title) ) : ?>
            <h1 class="title text-uppercase"><?= $title; ?></h1>
          <?php endif; ?>
          <?php /*if ( !empty($subtitle) ) : ?>
            <h3 class="subtitle"><?= $subtitle;  ?></h3>
          <?php endif;*/ ?>
        </div>
      </div>
    </div>
  </div>
  <div class="slick-outer-container">
    <div class="slick-carousel">
      <?php
      foreach( $images as $image ) :
        if ( isContentAllowed($image['id'], false) || is_front_page() ) :
        //if ( (is_front_page() || is_home()) || (is_page() && is_page_template('template-contentpage.php') && isContentAllowed($image['id'], false)) || !is_page() ) :
          ?>
          <div>
            <div class="img-inner" data-bg="<?= $image['url']; ?>">
              <?php if ( !empty($image['caption']) ) : ?>
                <div class="container hidden-xs hidden-sm">
                  <div class="row">
                    <div class="content text-center col-xs-12">
                      <?php if ( !empty($title) ) : ?>
                        <h1 class="title text-uppercase"><?= $title; ?></h1>
                      <?php endif; ?>
                      <h3 class="subtitle"><?= $image['caption']; ?></h3>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <?php
        endif;
      endforeach;
      ?>
    </div>
  </div>
</div>
<div class="section module gallery-caption hidden-md hidden-lg">
  <div class="container">
    <div class="content text-center col-xs-12">
      <?php if ( !empty($title) ) : ?>
        <h1 class="title text-uppercase"><?= $title; ?></h1>
      <?php endif; ?>
    </div>
  </div>
  <div class="slick-outer-container">
    <div class="slick-carousel">
      <?php
      foreach( $images as $image ) :
        if ( isContentAllowed($image['id'], false) || is_front_page() ) :
        //if ( (is_front_page() || is_home()) || (is_page() && is_page_template('template-contentpage.php') && isContentAllowed($image['id'], false)) || !is_page() ) :
          ?>
          <div>
            <?php if ( !empty($image['caption']) ) : ?>
              <div class="container">
                <div class="content text-center col-xs-12">
                  <h3 class="subtitle"><?= $image['caption']; ?></h3>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <?php
        endif;
      endforeach;
      ?>
    </div>
  </div>
</div>
<?php }
endif;
