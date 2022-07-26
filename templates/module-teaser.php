<?php
global $counter;
global $category_slugs;
global $page_parent_slug;
$parallax_attr = ['',''];

$image_field = get_sub_field('image');
$color_field = get_sub_field('background_color');
if(!empty($image_field)) $image = $image_field['sizes']['large'];
if(!empty($color_field)) $color = $color_field;

// desktop parallax...this is the nth result of the bad specification...and it continues in the js and css too...
if ( !is_page_template('template-trainer.php') && is_page('startseite') ) :
  $teaser = get_sub_field('content_teaser');
  if ( !empty(get_sub_field('id')) ) {
    if ( get_sub_field('id') == 'club' ) {
      $parallax_attr[0] = 'data-0="background-position:left calc(50% + 395px) bottom 0;height: 600px" data-300000="background-position:left calc(50% + 395px) bottom 0;"';
      $parallax_attr[1] = 'data-96="top: 194px;" data-896="top: -600px;"';
    }
    if ( get_sub_field('id') == 'fitness' ) {
      $parallax_attr[0] = 'data-0="background-position: left calc(50% + 395px) top 647px;height: 560px" data-1647="background-position: left calc(50% + 395px) top -87px;"';
      $parallax_attr[1] = 'data-0="top: 1354px;" data-600="top: 194px;" data-647="top: 194px;" data-1360="top: -560px;"';
    }
    if ( get_sub_field('id') == 'soccer' ) {
      $parallax_attr[0] = 'data-1580="background-position: left calc(50% + 395px) top 647px;height: 560px" data-2207="background-position: left calc(50% + 395px) top -87px;"';
      $parallax_attr[1] = 'data-0="top: 1914px;" data-1160="top: 194px;" data-1207="top: 194px;" data-1920="top: -560px;"';
    }
    if ( get_sub_field('id') == 'tennis' ) {
      $parallax_attr[0] = 'data-2140="background-position: left calc(50% + 395px) top 647px;height: 560px" data-2767="background-position: left calc(50% + 395px) top -87px;"';
      $parallax_attr[1] = 'data-0="top: 2474px;" data-1720="top: 194px;" data-1767="top: 194px;" data-2480="top: -560px;"';
    }
    if ( get_sub_field('id') == 'padel' ) {
      $parallax_attr[0] = 'data-2200="background-position: left calc(50% + 395px) top 647px;height: 560px" data-3327="background-position: left calc(50% + 395px) top -20px;"';
      $parallax_attr[1] = 'data-0="top: 3034px;" data-2280="top: 194px;" data-2327="top: 194px;" data-3040="top: -560px;"';
    }
  }
endif;

if ( is_page_template('template-trainer.php') ) :
  $featured_image_id = get_post_thumbnail_id($post->ID);
  $featured_image_meta = wp_get_attachment_image_src($featured_image_id, 'large');
  $image = $featured_image_meta[0];
  $color = pageid2color(the_parent_ID());
endif;

$background_image = isset($image) ? ' background-image: url(' . $image . ')' : '';
$background_color = 'background-color: ' . $color;
?>
<div id="<?php the_sub_field('id'); ?>" class="section teaser<?php if( get_sub_field('teaser') ) echo ' has_event'; ?>" <?= $parallax_attr[0]; ?> style="<?= $background_color; ?>; <?= $background_image; ?>">
  <div class="container">
    <div class="content text-center col-xs-12<?php if ( isset($image) ) { echo ' col-md-6'; }; ?>" <?php //echo $parallax_attr[1]; ?>>
      <h1 class="title text-uppercase"><?= !is_page_template('template-trainer.php') ? get_sub_field('title') : get_the_title(); ?></h1>
      <?php if ($counter < 1) $counter++; ?>
      <h4 class="subtitle"><?= !is_page_template('template-trainer.php') ? get_sub_field('subtitle') : get_field('subtitle');  ?></h4>
      <?= !is_page_template('template-trainer.php') ? get_sub_field('content') : get_the_content(); ?>
      <?php /*if ( is_page_template('template-trainer.php') ) : ?>
          <div class="filters-button-group text-left dropdown col-xs-12 col-sm-10 col-sm-push-1">
            <?php
            $parent_slug = get_post( the_parent_ID() )->post_name;
            $category = get_category_by_slug( $parent_slug );
            $subcategories = get_categories( ['parent' => $category->term_id ] );
            $subcategory_slugs = [];
            $subcategory_classes = [];
            foreach ($subcategories as $subcategory) :
              $subcategory_classes[] = ':not(.' . $subcategory->slug . ')';
              $subcategory_slugs[] = $subcategory->slug;
            endforeach;
            ?>
            <a class="selected dropdown-toggle" data-toggle="dropdown">
              <div>
                <span><?= strtoupper( $category->name ) ?>  - Alle -</span>
                <span class="icon icon-arrow_down"></span>
              </div>
            </a>
            <ul class="dropdown-menu">
              <li data-filter=".<?= $category->slug ?>"><?= strtoupper( $category->name ) ?>  - Alle -</li>
              <?php  ?><li data-filter=".<?= $category->slug . implode("", $subcategory_classes) ?>"><?= strtoupper( $category->name ) ?> TRAINER</li><?php  ?>
              <?php foreach ($subcategories as $subcategory) : ?>
                <?php
                  $hasTrainers = get_posts(['category_name' => $subcategory->slug, 'post_type' => 'trainer']);
                  if ($hasTrainers) :
                ?>
                <li data-filter=".<?= $subcategory->slug ?>"><?= strtoupper( $subcategory->name ) ?> TRAINER</li>
              <?php endif; endforeach; ?>
            </ul>
          </div>
      <?php endif; */?>
    </div>
    <?php if( get_sub_field('teaser') && !is_page_template('template-trainer.php') ) : ?>
      <div class="teaser-container col-xs-12 col-md-6" data-100-bottom="opacity: 0;" data-bottom="opacity: 1;">
        <div class="teaser-content text-center" style="background-color: <?= hex2rgba(get_sub_field('background_color_teaser'), '.9'); ?>">
          <?= $teaser ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if( get_sub_field('iframe') && !is_page_template('template-trainer.php') ) : ?>
    <div class="premiumplaner premiumplaner-1 hidden-xs hidden-sm"></div>
    <script type="text/javascript">
      jQuery(document).ready(function () {
        var url = "https://sportscheckallwetter.premiumplaner.de";
        jQuery.ajax({
          url : url,
          type : "GET",
          dataType : "html"
        }).done(function(data) {
          data = data.split('<link rel="stylesheet" type="text/css" href="').join('<link rel="stylesheet" type="text/css" href="'+url);
          data = data.split('type="text/javascript" src="').join('type="text/javascript" src="'+url);
          data = data.split('/img/pp/site/').join(url + '/img/pp/site/');
          data = data.split('/users/login"').join(url + '/users/login" target="_blank"');
          data = data.split('/files/sessions/').join(url + '/files/sessions/');
          data = data.split("jQuery('#einloggen_button').click();").join('');
          data = data.split('<link type="text/css" href="/frontpage/default/css/style.css" rel="stylesheet">').join('');
          data = data.split('<link type="text/css" href="/frontpage/default/css/bootstrap.css" rel="stylesheet">').join('');
          //jQuery("#pp-embed-login").html(data);
          if (jQuery(window).width() >= 992) {
            jQuery('.premiumplaner.premiumplaner-1').html('<div id="pp-embed-login">' + data + '</div>');
          } else {
            jQuery('.premiumplaner.premiumplaner-2').html('<div id="pp-embed-login">' + data + '</div>');
          }
        });
        jQuery(window).on('load resize', function () {
          if ( jQuery(window).width() >= 992 && jQuery(".premiumplaner.premiumplaner-1").is(':empty') ) {
            jQuery(".premiumplaner.premiumplaner-1").html(jQuery('.premiumplaner-2').contents().clone());
            jQuery('.premiumplaner-2').empty();
          }
          if ( jQuery(window).width() <= 991 && jQuery(".premiumplaner.premiumplaner-2").is(':empty') ) {
            jQuery(".premiumplaner.premiumplaner-2").html(jQuery('.premiumplaner-1').contents().clone());
            jQuery('.premiumplaner-1').empty();
          }
        });
      });
    </script>
    <?php endif; ?>
  </div>
</div>