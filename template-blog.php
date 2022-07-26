<?php
/**
 * Template Name: Blog Template
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>

<?php
  $parent_page = get_post( the_parent_ID() );
  $parent_slug = stripped_slug($parent_page);

  $parent_category = get_term_by('slug', $parent_slug, 'category');
  if ( the_parent_ID() ) {
    $args = array(
      'slug' => $post->post_name,
      'parent' => $parent_category->term_id,
    );
  } else {
    $args = array(
      'slug' => $post->post_name
    );
  }
  $subcategory = get_terms(['category'], $args);

  global $post;
  $myposts = ( !empty($subcategory) ) ? new WP_Query(['cat' => $subcategory[0]->term_id]) : [];

  if ( stripped_slug($post) != 'partner' && ( !empty($subcategory) && $myposts->have_posts() ) ) :
?>
    <div class="section">
      <div class="container">
        <div class="col-xs-12">
      <?php
        while ( $myposts->have_posts() ) : $myposts->the_post();
          $class_helper = 12;
      ?>
          <div class="row post">
          <?php if ( has_post_thumbnail() ) : $class_helper = 8; ?>
            <div class="text-right col-xs-4"><?php the_post_thumbnail('full', ['class'=>'img-responsive']); ?></div>
          <?php endif; ?>
            <div class="col-xs-<?= $class_helper; ?>">
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
      <?php
        endwhile;
        wp_reset_postdata();
      ?>
        </div>
      </div>
    </div>
<?php
  endif;
  if ( stripped_slug($post) == 'partner' ) :
    $partners = [
      [
        'name' => 'FILA',
        'text' => 'FILA ist ein weltweit bedeutender Sportartikelhersteller. Das Unternehmen wurde im Jahr 1911 gegründet und wird nun aus Südkorea gelenkt.',
        'link' => 'http://www.fila.de',
        'img' => get_template_directory_uri() . '/dist/images/Fila-Logo-Font.jpg'
      ],
      [
        'name' => 'Wilson',
        'text' => 'Wilson ist der weltweit führende Hersteller von Ballsportausrüstung. Unsere Kernsportarten sind Tennis, Baseball, American Football, Golf, Basketball, Softball, Badminton und Squash. Das Unternehmen ist in drei Geschäftsbereiche aufgeteilt: Racquet Sports, Team Sports und Golf. Wilson ist im Tennisbereich durch Spieler wie Roger Federer und Milos Raonic weltbekannt.',
        'link' => 'http://www.wilson.com/de-de/',
        'img' => get_template_directory_uri() . '/dist/images/Wilson_Logo_PMS186.png'
      ],
      [
        'name' => 'Agentur Zur Schönen Gärtnerin',
        'text' => 'Die Agentur zur Schönen Gärtnerin entwickelt seit über 15 Jahren erfolgreiche Kommunikationkonzepte für Unternehmen, positioniert Marken strategisch im Wettbewerb, kreiert ganzheitliche Ideen für die gewinnbringenden Zielgruppen und realisiert alle Maßnahmen effektiv online, offline wie mobile.',
        'link' => 'http://www.schoenegaertnerin.de/',
        'img' => get_template_directory_uri() . '/dist/images/logo_AZSG.JPG'
      ],
      [
        'name' => 'Tennis People',
        'text' => 'Die Tennis-People GmbH wurde von erfahrenen Experten aus den Bereichen Sportanlagen-Management, Trainerausbildung, Konzeptentwicklung, Controlling und Marketing gegründet. Mit innovativen Kurskonzepten und Angeboten begeistert die Macher neue Spieler für Tennis und ermögichen Tennisschulen, Anlagen und Vereinen neue wirtschaftliche Chancen auf dem Tennis-Markt.',
        'link' => 'https://www.tennis-people.com/',
        'img' => get_template_directory_uri() . '/dist/images/logo_tennis-people.jpg'
      ],
      [
        'name' => 'Möbel Willinger',
        'text' => 'Seit über 123 Jahre gelebte Wohnkultur. Die Leidenschaft für individuelles Einrichten – Generation an Generation weitergegeben. Den Zeitgeist im Auge und das Gespür für Raumgefühl, inspiriert Kunden immer wieder zu wahren Wohnkollagen.',
        'link' => 'http://www.moebel-willinger.de/',
        'img' => get_template_directory_uri() . '/dist/images/Willinger-Logo-Sub.png'
      ],
      [
        'name' => 'Ayinger',
        'text' => 'Die familiengeführte Brauerei Aying steht seit über 130 Jahren für ausgezeichnete Bierkultur. Heute zählt die Spezialitäten-Brauerei zu einer der fortschrittlichsten in Oberbayern und ist die einzige Brauerei im Landkreis München. Seit über 50 Jahren Partner der Sportanlage.',
        'link' => 'http://www.ayinger.de/',
        'img' => get_template_directory_uri() . '/dist/images/ayinger.jpg'
      ],
      [
        'name' => 'VBA events',
        'text' => 'Die connected experts von VBA Events sind mit ihrer 25-jährigen Expertise bei der Konzeption und Realisierung von Motivationsveranstaltungen unser professioneller Partner, um ihr Kick-off, Teambuilding oder auch ihren Gesundheitstag zielführend und professionell auf unserer Anlage umzusetzen. Unter dem Motto SPORTIFY YOUR BUSINESS bringen wir so Ihre Unternehmensaktivitäten mit spannenden und sportlichen Aktivitäten lebendig in Einklang – und das mitten in München direkt am Englischen Garten.',
        'link' => 'http://www.vba-events.com/munich-experts/',
        'img' => get_template_directory_uri() . '/dist/images/VBA_Logo_Claim_unten_RGB.JPG'
      ],
      [
        'name' => 'PREMIUMPLANER',
        'text' => 'Der PREMIUMPLANER ist ein inhabergeführtes Softwareunternehmen, das auf Fitnessstudios spezialisiert ist. Von der Terminbuchung, Kundenverwaltung, Abrechnung bis zum Kassensystem bietet es eine moderne All-In-One Lösung. Hilfreiche Werkzeuge sorgen auch im herausfordernden Alltag für Überblick.',
        'link' => 'https://www.premiumplaner.com/',
        'img' => get_template_directory_uri() . '/dist/images/Premiumplaner.png'
      ],
      [
        'name' => 'DIE RADIOLOGIE',
        'text' => "DIE RADIOLOGIE hat ein umfassendes Versorgungskonzept erschaffen, das höchste individuelle Befundungs- und Beratungskompetenz mit menschlicher Nähe und Fürsorge vereint.\n\nMit schonenden Methoden bieten unsere erfahrene Experten maßgeschneiderte, präzise Diagnostik und Vorsorge in angenehmer Atmosphäre – an acht vernetzten, hochmodern ausgestatteten Standorten in und um München.\n\nFür eine optimal angepasste, individuelle weiterführende Therapie.",
        'link' => 'https://www.die-radiologie.de/',
        'img' => get_template_directory_uri() . '/dist/images/logo_left_4c_beige_grau.png'
      ]
    ];
?>
  <div class="section">
    <div class="container">
      <div class="col-xs-12">
    <?php
      foreach ($partners as $partner) :
        $class_helper = [12, 10];
        ?>
        <div class="row post">
          <?php if ( !empty($partner['img']) ) : $class_helper = [9,8]; ?>
            <div class="icon-container text-right col-xs-3 col-xs-push-0 col-md-2 col-md-push-1">
              <img src="<?= $partner['img']; ?>" alt="<?= $partner['name']; ?>" class="img-responsive">
            </div>
          <?php endif; ?>
          <div class="col-xs-<?= $class_helper[0]; ?> col-md-<?= $class_helper[1]; ?> col-md-push-1">
            <h3 class="post-title text-primary text-uppercase"><?= $partner['name']; ?></h3>
            <div class="content">
              <p><?php echo nl2br($partner['text']); ?></p>
              <a href="<?= $partner['link']; ?>" target="_blank" class="text-uppercase"><?= __('Read More', 'sage'); ?></a>
            </div>
          </div>
        </div>
        <?php
      endforeach;
    ?>
      </div>
    </div>
  </div>
<?php
  endif;
?>

