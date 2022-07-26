<?php
    $categories = get_sub_field('category');
?>
<div class="news-module">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h1 class="title text-uppercase">Neues vom Club</h1>
            </div>
        </div>
        <div class="row">
            <?php
                $args = array( 'posts_per_page' => 3, 'offset'=> 0, 'category' => $categories );
                $news_posts = get_posts( $args );
                foreach ( $news_posts as $post ) : setup_postdata( $post ); ?>
                    <div class="news-module__post col-xs-12 col-md-4">
                        <div class="date">
                            <span><?php the_time('d'); ?></span><span><?php the_time('.m'); ?></span><span><?php the_time('.Y'); ?></span>
                        </div>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="images"><?php the_post_thumbnail('full', ['class'=>'img-responsive']); ?></div>
                    <?php endif; ?>
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
                <?php endforeach;
                wp_reset_postdata();
            ?>
            <div class="col-xs-12">
                <div class="button">
                    <a class="btn btn-primary btn-sc" href="<?= get_permalink(1491); ?>">Alle News</a>
                </div>
            </div>
        </div>
    </div>
</div>
