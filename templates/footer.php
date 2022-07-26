<footer class="content-info">
  <?php //if( is_home() ):  ?>
  <div id="location" class="section top">
    <div class="container">
      <div class="col-xs-12 col-md-6">
        <h4 class="text-uppercase">Unsere Sponsoren</h4>
        <ul class="sponsoren list-inline list-unstyled">
          <li><a href="http://www.fila.de/" target="_blank"><img src="<?= get_template_directory_uri() ?>/dist/images/partner_fila.png" alt="Fila" class="img-responsive" /></a></li>
          <li><a href="http://www.wilson.com/de-de/" target="_blank"><img src="<?= get_template_directory_uri() ?>/dist/images/partner_wilson.png" alt="Wilson" class="img-responsive" /></a></li>
        </ul>
      </div>
      <div class="col-xs-12 col-md-6">
        <div class="content text-center">
          <?php
          $company_logo = isset($wpseo['company_logo']) ? $wpseo['company_logo'] : '';
          $logo = get_field('logo', 'option');
          ?>
          <img alt="<?= get_bloginfo('name'); ?>" src="<?= wp_get_attachment_url( $logo ) ?>" onerror="this.onerror=null; this.src='<?= $company_logo ?>'" width="172" height="120" />
          <h3 class="title text-uppercase">Top Event Location mit Hotel</h3>
          <p>Mit über 40.000 m² Münchens größtes privates Sport-Resort. Direkt am nördlichen Englischen Garten. SportScheck Hotel und 20 MICE-Locations  für wettersichere Meetings, Incentives, Conventions und Events.</p>
          <p><a href="<?= get_home_url() ?>/sportscheck-event-location/">MEHR</a></p>
        </div>
      </div>
    </div>
  </div>
  <?php //endif; ?>
  <div class="default">
    <div class="container">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <div class="col-xs-12 col-md-3">
        <h4 class="text-uppercase">Adresse</h4>
          <p>Scheck Allwetteranlage GmbH & Co KG<br>
          Münchner Straße 15<br>
          85774 Unterföhring</p>

          <p>Tel. 089 / 99 28 74 - 0<br>
          Fax 089 / 99 28 74 -220<br>
          <a href="mailto:willkommen@SportScheckAllwetter.de">willkommen@SportScheckAllwetter.de</a></p>
      </div>
      <div class="col-xs-12 col-md-3">
        <h4 class="text-uppercase">Lage</h4>
        <a href="<?= get_home_url() ?>/sportscheck-lage/"><img src="<?= get_template_directory_uri() ?>/dist/images/lage.jpg" alt="Lage" class="img-responsive center-block" /></a>
      </div>
      <div class="col-xs-12 col-md-6">
        <h4 class="text-uppercase">Auch auf der Anlage</h4>
        <div class="row partners">
          <div class="col-xs-6 col-md-3">
            <a href="<?= get_home_url() ?>/scheck-in-restaurant-muenchen/">
              <div class="partners-image_container">
                <img src="<?= get_template_directory_uri() ?>/dist/images/partner_scheckin.png" alt="Scheck In" class="img-responsive center-block" />
              </div>
            </a>
            <div class="partners-caption">Restaurant</div>
          </div>
          <div class="col-xs-6 col-md-3">
            <a href="http://www.par-centrum.de/" target="_blank">
              <div class="partners-image_container">
                <img src="<?= get_template_directory_uri() ?>/dist/images/partner_parcentrum.png" alt="P.A.R. Centrum" class="img-responsive center-block" />
              </div>
            </a>
            <div class="partners-caption">Physiotherapie</div>
          </div>
          <div class="col-xs-6 col-md-3">
            <a href="http://www.tennis-point-muenchen.de/" target="_blank">
              <div class="partners-image_container">
                <img src="<?= get_template_directory_uri() ?>/dist/images/partner_tennispoint.png" alt="Tennispoint" class="img-responsive center-block" />
              </div>
            </a>
            <div class="partners-caption">Tennisshop</div>
          </div>
          <div class="col-xs-6 col-md-3">
            <a href="https://www.waveboard.de/" target="_blank">
              <div class="partners-image_container">
                <img src="<?= get_template_directory_uri() ?>/dist/images/partner_waveboard.png" alt="Waveboard" class="img-responsive center-block" />
              </div>
            </a>
            <div class="partners-caption">Functional Ski-  & Outdoorwear</div>
          </div>
        </div>
      </div>
      <div class="col-xs-12">
        <nav class="navbar">
          <?php
          if (has_nav_menu('footer_navigation')) :
            wp_nav_menu(['theme_location' => 'footer_navigation', 'walker' => new wp_bootstrap_navwalker(), 'menu_class' => 'nav navbar-nav']);
          endif;
          ?>
          <div class="facebook-block">
            <a href="https://www.facebook.com/sportscheck.allwetter/?fref=ts" target="_blank">
              <h5 class="text-uppercase hidden-xs hidden-sm">Besuchen sie uns auch auf Facebook</h5> <span class="icon icon-facebook">F</span>
            </a>
          </div>
        </nav>
      </div>
    </div>
    <div id="newsletter" class="transition-down">
      <div class="container">
        <div class="col-xs-12">
          <a href="#newsletter" class="close pull-right"><span class="hidden-xs hidden-sm"><?= __('Close', 'sage') ?></span><span class="icon icon-close"></span></a>
        </div>
        <div class="row">
          <div id="newsletter__header" class="col-xs-12 text-center">
            <h2 class="title text-primary text-uppercase"><?= __('Don\'t miss anything', 'sage') ?></h2>
            <h4 class="subtitle">Melde dich hier an, um alle News & Events zu erfahren</h4>
          </div>
          <div class="col-xs-12">
            <?php the_widget( 'Custom_Widget_Email_Subscribers', ["es_name" => "YES", "es_desc"=>"", "es_group"=>""] ); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
