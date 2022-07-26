<?php //die('lol');
  $wpseo = get_option('wpseo');
  $company_logo = isset($wpseo['company_logo']) ? $wpseo['company_logo'] : '';
  $logo = get_field('logo', 'option');
  $opening_times = get_field('opening', 'option');
?>

<div id="opening" class="transition-up">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <a href="#opening" class="close pull-right"><span class="hidden-xs hidden-sm"><?= __('Close', 'sage') ?></span><span class="icon icon-close"></span></a>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 text-center">
        <h2 class="title text-primary text-uppercase"><?= __('Opening times', 'sage') ?></h2>
        <h4 class="subtitle">Wir haben 365 Tage im Jahr für Sie geöffnet!</h4>
      </div>
    </div>
  </div>
  <!--?php
    $holiday_opening_times = [
      [
        "<h4 class=\"text-primary\" style=\"font-size: 20px;\">HEILIG ABEND</h4>
                <p style=\"font-size: 16px;\">Samstag, 24.12.2016 <strong>07.00 - 15.00 Uhr</strong></p>",
        "<h4 class=\"text-primary\" style=\"font-size: 20px;\">1. WEIHNACHTSFEIRTAG</h4>
                <p style=\"font-size: 16px;\">Sonntag, 25.12.2016 <strong>07.00 - 21.00 Uhr</strong></p>",
        "<h4 class=\"text-primary\" style=\"font-size: 20px;\">2. WEIHNACHTSFEIRTAG</h4>
                <p style=\"font-size: 16px;\">Montag, 26.12.2016 <strong>07.00 - 21.00 Uhr</strong></p>"
      ],
        [
            "<h4 class=\"text-primary\" style=\"font-size: 20px;\">SILVESTER</h4>
                <p style=\"font-size: 16px;\">Samstag, 31.12.2016 <strong>07.00 - 15.00 Uhr</strong></p>",
            "<h4 class=\"text-primary\" style=\"font-size: 20px;\">NEUJAHR</h4>
                <p style=\"font-size: 16px;\">Sonntag, 01.01.2017 <strong>13.00 - 21.00 Uhr</strong></p>",
            "<h4 class=\"text-primary\" style=\"font-size: 20px;\">HEILIGE DREI KÖNIGE</h4>
                <p style=\"font-size: 16px;\">Freitag, 06.01.2017 <strong>07.00 - 21.00 Uhr</strong></p>"
        ],
    ]
  ?-->
  <!--div class="holiday">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 text-center"><h4 class="holiday-title"><i class="icon icon-xmas"></i>In den Weihnachtsferien vom 24.12.2016 - 08.01.2017</h4></div>
        <div class="col-xs-12">
          <div class="row">
            <?php foreach ($holiday_opening_times[0] as $opening) : ?>
              <div class="col-xs-12 col-md-4">
                <?= $opening; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="row">
            <?php foreach ($holiday_opening_times[1] as $opening) : ?>
              <div class="col-xs-12 col-md-4">
                <?= $opening; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div-->
  <div class="container">
    <div class="row">
    <?php foreach ($opening_times[0] as $opening) : ?>
      <div class="col-xs-12 col-md-4">
        <?= $opening; ?>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
  <div class="bg-primary">
    <div class="container">
      <div class="col-xs-12">
        <a href="#opening" class="opened pull-right"><span class="icon icon-clock"></span><span class="hidden-xs hidden-sm"><?= __('Opening times', 'sage') ?></span><span class="icon icon-arrow_down"></span></a>
      </div>
    </div>
  </div>
</div>
<header class="banner navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle navbar-left" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only"><?= __('Toggle navigation', 'sage'); ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>"><?= isset($logo) ? '<img alt="' . get_bloginfo('name') . '" src="' . wp_get_attachment_url( $logo ) . '" onerror="this.onerror=null; this.src=\'' . $company_logo . '\'" width="172" height="120">' : bloginfo('name'); ?></a>
      <a href="#opening" class="btn btn-primary"><span class="icon icon-clock"></span><span class="hidden-xs hidden-sm"><?= __('Opening times', 'sage') ?></span><span class="icon icon-arrow_down hidden-xs hidden-sm"></span></a>
    </div>

    <nav class="collapse navbar-collapse">
      <div class="nav-row sub">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'secondary_navigation', 'walker' => new wp_bootstrap_navwalker(), 'menu_class' => 'nav navbar-nav']);
      endif;
      ?>
      </div>
        <div class="nav-row">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'walker' => new wp_bootstrap_navwalker(), 'menu_class' => 'nav navbar-nav']);
      endif;
      ?>
      </div>
      <div class="nav-row hidden-md hidden-lg">
      <?php
        if (has_nav_menu('footer_navigation')) :
          wp_nav_menu(['theme_location' => 'footer_navigation', 'walker' => new wp_bootstrap_navwalker(), 'menu_class' => 'nav navbar-nav']);
        endif;
      ?>
      </div>
    </nav>
  </div>
</header>
