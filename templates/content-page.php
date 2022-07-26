<?php //the_content(); ?>
<?php use Roots\Sage\Titles; ?>
<?php
  global $counter;
  global $background_color;
  global $trainer_email;
  global $trainer_name;
  global $trainers;
  $counter = 0;

  // check current page template
  if ( is_page_template('template-trainer.php') ) :
    get_template_part('templates/module', 'teaser');
  endif;

  if ( is_page_template('template-list.php') ) :
    if( have_rows('list_page_modules') ):
      while ( have_rows('list_page_modules') ) : the_row();
        if( get_row_layout() == 'attachments' ):
          $attachments = get_sub_field('attachments');
          $global_category_ids = [];
          if ( !empty($attachments) ) :
            foreach( $attachments as $attachment ) :
              $attachment_id = $attachment['attachment'];
              $categories = get_field('attachment_category', $attachment_id);
              if (!in_array($categories[0], $global_category_ids)) $global_category_ids[] = $categories[0];
            endforeach;
          endif;
        endif;
      endwhile;
    endif;
  ?>
    <div class="section section-header">
      <div class="container">
        <div class="col-xs-12 text-center">
          <h1 class="title text-primary text-uppercase"><?= Titles\title(); ?></h1>
          <?php if(!empty($attachments) && (is_page() && !$post->post_parent)) : ?>
            <div class="filters-button-group text-left dropdown col-xs-12 col-md-6 col-md-push-3" <?php if( !empty($background_color) ) { echo 'style="' . $background_color . '"';} ?>">
              <a class="selected dropdown-toggle" data-toggle="dropdown"><div><span>- Alle -</span><span class="icon icon-arrow_down"></span></div></a>
              <ul class="dropdown-menu">
                <li data-filter=".grid-item">- Alle -</li>
                <?php foreach ($global_category_ids as $category_id) : $category = get_category( $category_id );?>
                    <?php if ( !empty($category->name) ) : ?>
                      <li data-filter=".<?= $category->slug ?>"><?= strtoupper( $category->name ) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <?php if ( is_page('news') ) { ?>
            <?php
              $news_cat_id = 153; // this probably gonna be different on live
              $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
              $filter = !empty($_GET["cat"]) ? [$news_cat_id, $_GET["cat"]] : [$news_cat_id];
              $filtered_args = array( 'posts_per_page' => 8, 'paged' => $paged, 'category__and' => $filter, 'post_type' => 'post' );
              $args = array( 'posts_per_page' => 8, 'paged' => $paged, 'cat' => $news_cat_id, 'post_type' => 'post' );
              $news_posts = new WP_Query( $args );
              $news_posts_filtered = new WP_Query( $filtered_args );
              $news_categories = [];
              while ( $news_posts->have_posts() ) : setup_postdata($news_posts->the_post());
                $news_categories = array_unique (array_merge ($news_categories, wp_get_post_categories(get_the_ID())));
              endwhile;
              wp_reset_postdata();

              $selected_cat = !empty($_GET["cat"]) ? get_category( htmlspecialchars($_GET["cat"]) )->name : 'Thema wählen';
            ?>
            <div class="filters-button-group text-left dropdown col-xs-12 col-md-6 col-md-push-3" <?php if( !empty($background_color) ) { echo 'style="' . $background_color . '"';} ?>">
              <a class="selected dropdown-toggle" data-toggle="dropdown"><div><span><?= $selected_cat ?></span><span class="icon icon-arrow_down"></span></div></a>
              <ul class="dropdown-menu">
                <li><a href="<?= get_permalink( get_page_by_path( 'news' ) ) ?>">- Alle -</a></li>
                <?php foreach ($news_categories as $category_id) : $category = get_category( $category_id );?>
                  <?php if ($category->slug !== 'news') { ?>
                    <li><a href="<?= get_permalink( get_page_by_path( 'news' ) ) . '?cat=' . $category_id ?>"><?= strtoupper( $category->name ) ?></a></li>
                  <?php } ?>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php if ( !empty($attachments) ) : ?>
      <div class="section">
        <div class="container grid">
        <?php
          foreach( $attachments as $attachment ) :
            $attachment_id = $attachment['attachment'];
            $attachment_meta = get_post($attachment_id);
            $category_ids = get_field('attachment_category', $attachment_id);
            $availability = get_field('attachment_availability', $attachment_id);
            $category_slugs = get_categorySlugs($category_ids);
            if (isDownloadAllowed($availability[0]['days'], $availability[0]['from'], $availability[0]['until'])) : ?>
            <div class="grid-item<?php if ($category_slugs) echo ' ' . implode(" ", $category_slugs); ?>" style="width: 100%; margin-left: -15px;">
              <div>
                <div class="icon-container text-right col-xs-3">
                  <svg version="1.1" id="pdf" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                       width="94.987px" height="115.985px" viewBox="0 0 94.987 115.985" style="enable-background:new 0 0 94.987 115.985;"
                       xml:space="preserve">
                    <g>
                      <polygon style="fill:<?= category2color($category_ids[0]); ?>" points="0,0 0,115.985 94.987,115.985 94.987,22.098 72.89,0 	"/>
                      <polygon style="opacity:0.5;fill:#FFFFFF;" points="94.987,22.098 72.89,0 72.89,22.098 	"/>
                    </g>
                    <g><text transform="matrix(1 0 0 1 16.1079 27.4307)" style="fill:#FFFFFF; font-family:'Helvetica'; font-size:18.5266px;">PDF</text></g>
                    <g>
                      <line style="fill:none;stroke:#FFFFFF;stroke-width:3;stroke-miterlimit:10;" x1="16.108" y1="42.617" x2="78.879" y2="42.617"/>
                      <line style="fill:none;stroke:#FFFFFF;stroke-width:3;stroke-miterlimit:10;" x1="16.108" y1="60.617" x2="78.879" y2="60.617"/>
                      <line style="fill:none;stroke:#FFFFFF;stroke-width:3;stroke-miterlimit:10;" x1="16.108" y1="96.617" x2="78.879" y2="96.617"/>
                      <line style="fill:none;stroke:#FFFFFF;stroke-width:3;stroke-miterlimit:10;" x1="16.108" y1="78.617" x2="78.879" y2="78.617"/>
                    </g>
                  </svg>
                </div>
                <div class="col-xs-9">
                  <h4 class="subtitle text-primary text-uppercase"><?= $attachment_meta->post_title ?></h4>
                  <p class="content"><?= $attachment_meta->post_content ?></p>
                  <p><a href="<?= $attachment_meta->guid ?>" target="_blank">DOWNLOAD</a></p>
                </div>
              </div>
            </div>
        <?php
            endif;
          endforeach;
        ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if ( is_page('news') ) { ?>
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
            <?php wp_reset_postdata();
        };
      ?>
        </div>
      </div>
    </div>
  <?php
  endif;

  // check if the flexible content field has rows of data
  if( have_rows('module') ):
    // loop through the rows of data
    while ( have_rows('module') ) : the_row();
      $background_color = get_sub_field('background_color');
      $image = get_sub_field('image');
      if( get_row_layout() == 'section_module' ) :
        get_template_part('templates/module', 'teaser');
      elseif( get_row_layout() == 'news_module' ) :
        get_template_part('templates/module', 'news');
      elseif( get_row_layout() == 'keybenefits' ):
        $images = get_sub_field('gallery');
        $counter = 0;
        ?>
        <div class="section keybenefits" style="background-color: <?= $background_color; ?>; color: white">
          <div class="container">
            <?php if( $images ): ?>
              <div class="slick-carousel">
                <?php foreach( $images as $image ) : ?>
                  <?php $text = !empty($image['caption']) ? $image['caption'] : $image['title']; ?>
                  <div style="background-color: <?= ($counter == 0) ? get_sub_field('primary_color') : get_sub_field('secondary_color') ?> ">
                    <div class="image-container">
                      <img src="<?= $image['sizes']['large']; ?>" alt="<?= $image['title']; ?>" class="center-block" />
                    </div>
                    <div class="text-center"><h4 class="image-caption"><?= nl2br($text); ?></h4></div>
                  </div>
                  <?php ($counter == 0 ) ? $counter++ : $counter = 0; ?>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="section planner">
          <div class="premiumplaner premiumplaner-2"></div>
        </div>
        <?php
      elseif( get_row_layout() == 'quicklinks_module' ):
        ?>
        <div class="section module ql">
          <div class="container">
            <div class="content text-center col-xs-12 col-sm-10 col-sm-push-1">
              <h1 class="title text-uppercase"><?php the_sub_field('title'); ?></h1>
              <h3 class="subtitle"><?php the_sub_field('subtitle');  ?></h3>
              <?php the_sub_field('content');  ?>
            </div>
            <div class="col-xs-12">
              <div class="row">
              <?php
              if( have_rows('columns') ):
                $total = 0;
                while ( have_rows('columns') ) : the_row();
                  if( get_row_layout() == 'column' ) $total++;
                endwhile;

                while ( have_rows('columns') ) : the_row();
                  if( get_row_layout() == 'column' ):
                    $image = get_sub_field('image');
                    $link = get_sub_field('button_link');
                  ?>
                    <div class="eq-height col-xs-12 col-md-<?= 12/$total ?>">
                      <div class="image-container"><img src="<?= $image['sizes']['large']; ?>" title="<?= $image['title']; ?>" /></div>
                      <div><?php the_sub_field('content'); ?></div>
                    <?php if( !empty($link) ) : ?>
                        <?php

                        $link_target = ( strpos($link, get_home_url()) !== false && !endsWith($link, '.pdf') ) ? '' : ' target="_blank"'; ?>
                        <div><a href="<?= $link ?>"<?= $link_target; ?> class="btn btn-primary"><?= endsWith($link, '.pdf') ? 'PDF' : __('Read More', 'sage'); ?></a></div>
                        <?php /*
                        $url1 =  parse_url("http://allwetteranlage.de");
                        //var_dump($url1);
                        $url2 =  parse_url($link);
                        //var_dump($url2);
                        if ($url1['host'] == $url2['host']){
                            $link_target = ( strpos($link, get_home_url()) !== false && !endsWith($link, '.pdf') ) ? '' : ' target="_blank"'; ?>
                            <div><a href="<?= $link ?>"<?= $link_target; ?> class="btn btn-primary"><?= endsWith($link, '.pdf') ? 'PDF' : __('Read More', 'sage'); ?></a></div>
                            <?php
                        } else {
                            ?>
                            <div>
                                <a href="<?= $link ?>"<?= $link_target; ?> class="btn btn-primary"><?= endsWith($link, '.pdf') ? 'PDF' : __('Read More', 'sage'); ?>
                                    <img class="alignnone wp-image-1249" src="http://www.kephost.com/images/2017/01/26/link_extern_white.png" width="13" height="13" style="position:relative; right:62px; top:-2px;" />
                                </a>
                            </div>
                            <?php
                        }*/
                      ?>
                    <?php endif; ?>
                    </div>
                  <?php
                  endif;
                endwhile;
              endif;
              ?>
              </div>
            </div>
          </div>
        </div>
        <?php
      elseif( get_row_layout() == 'gallery_module' ):
        get_template_part('templates/module', 'gallery');
      elseif( get_row_layout() == 'content_module' ):
        get_template_part('templates/static', 'clubcard');
        /*?>
        <div class="section module card" style="background-color: <?= $background_color; ?>;">
          <div class="container">
            <div class="content text-center col-xs-12">
              <h1 class="title"><?php the_sub_field('title'); ?></h1>
              <h3 class="subtitle"><?php the_sub_field('subtitle');  ?></h3>
            </div>
            <div class="col-xs-12 col-sm-6">
              <?php the_sub_field('content_block_1'); ?>
            </div>
            <div class="col-xs-12 col-sm-6">
              <?php the_sub_field('content_block_2'); ?>
            </div>
            <div class="col-xs-12">
              <img src="<?= $image['url']; ?>" class="img-responsive center-block"\>
            </div>
          </div>
        </div>
        <?php
        */
      elseif( get_row_layout() == 'contact_module' ):
        $cat = isset($_GET['c']) ? $_GET['c'] : 'kontakt';
        if ( is_page(9) ) {
          $cat = 'gastronomie';
        }
        $params = array(
          'where' => 'category.name LIKE "%'. $cat . '%"',
          'orderby' => 'SUBSTRING_INDEX((post_title)," ",-1) ASC, id ASC',
          'limit' => '-1'
        );
        $trainers = pods( 'trainer', $params );
        $is_anyAllowed = false;
        while( $trainers->fetch() ) :
          $trainer_id = $trainers->field('id');
          if (isContentAllowed($trainer_id)) {
            $is_anyAllowed = true;
          }
        endwhile;
        if ($is_anyAllowed) :
        ?>
        <div id="contact" class="section contact" style="background-color: <?= $background_color; ?>;">
          <div class="container">
            <div class="row">
              <div class="content text-center col-xs-12">
                <h1 class="title text-uppercase"><?php the_sub_field('title'); ?></h1>
                <h3 class="subtitle"><?php the_sub_field('subtitle');  ?></h3>
              </div>
              <div class="content col-xs-10 col-xs-push-1 slick-outer-container">
                <div class="slick-carousel">
                  <?php $trainers->reset(); ?>
                  <?php while( $trainers->fetch() ) : ?>
                    <?php
                      $trainer_id = $trainers->field('id');
                      $image_id = get_post_thumbnail_id($trainer_id);
                      $image = wp_get_attachment_image_src($image_id,'large');
                      $trainer_phone = get_field('phone_number', $trainer_id);
                      $trainer_email = get_field('email_address', $trainer_id);
                      $trainer_name = $trainers->display('name');
                      $trainer_prof = get_field('trainer_profession', $trainer_id);
                      $primary_id = getPrimaryCategory($trainer_id);
                      if ( isContentAllowed($trainer_id) ) :
                    ?>
                    <div data-color="<?= category2color($primary_id); ?>">
                      <div class="col-xs-12 col-md-6 image-container">
                        <img src="<?= $image[0]; ?>" alt="<?= $trainer_name; ?>" class="img-responsive center-block"<?php if (empty($image_id[0])) echo ' style="visibility: hidden"'; ?> />
                      </div>
                      <div class="col-xs-12 col-md-6">
                        <h4 class="name text-uppercase"><?= $trainer_name; ?></h4>
                        <?php //if ( is_page('sportscheck-kontakt') ) : ?>
                        <?php if (!empty($trainer_prof)) : ?>
                          <?php
                            //$category = get_category(getPrimaryCategory($trainer_id))->name;
                          ?>
                          <h5<?php /* ?> class="text-lowercase"<?php */ ?>><?= $trainer_prof; ?></h5>
                        <?php endif; ?>
                        <?php if ( !empty($trainer_phone) ) : ?>
                          <h4><span class="icon icon-phone"></span> <?= $trainer_phone; ?></h4>
                        <?php endif; ?>
                        <?= do_shortcode('[contact-form-7 id="184" title="Trainer Contact Form"]'); ?>
                      </div>
                    </div>
                  <?php
                      endif;
                    endwhile;
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        endif;
      elseif( get_row_layout() == 'mobile_teaser_module' ):
        $display = get_sub_field('display');
        if ($display == 'true') :
          get_template_part('templates/static', 'mobileteaser');
            /*while ( have_rows('mobile_teaser', 'option') ) : the_row();
              $image = get_sub_field('image');
              ?>
              <div class="section mobile_teaser" style="background-color: <?= $background_color; ?>;">
                <div class="container">
                  <div class="content text-center col-xs-12">
                    <h1 class="title"><?php the_sub_field('title'); ?></h1>
                    <h3 class="subtitle"><?php the_sub_field('subtitle');  ?></h3>
                  </div>
                  <div class="content text-center col-xs-4 col-md-6">
                    <img src="<?= $image['url']; ?>" class="img-responsive"\>
                  </div>
                  <div class="content col-xs-8 col-md-6">
                    <div class="checkmark-list">
                      <?php the_sub_field('content'); ?>
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <a href="<?php the_sub_field('ios_link'); ?>" target="_blank" class="btn btn-primary btn-icon"><span class="icon icon-ios"></span>iOS</a>
                    <a href="<?php the_sub_field('android_link'); ?>" target="_blank" class="btn btn-primary btn-icon"><span class="icon icon-android"></span>android</a>
                  </div>
                </div>
              </div>
              <?php
            endwhile;
            */
        endif;
      endif;
    $color = $background_color;
    endwhile;
  else :
  // no layouts found
  endif;
?>
<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
