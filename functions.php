<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
@ob_start();
$sage_includes = [
  'acf/acf.php',
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php',// Theme customizer
  'lib/wp_bootstrap_navwalker.php',
  'lib/acf_fields.php'
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

if( function_exists('acf_add_options_page') ) {
  // add parent
  $parent = acf_add_options_page(array(
      'page_title' 	=> 'General Content Settings',
      'menu_title' 	=> 'Content Settings',
      'redirect' 		=> false,
      'icon_url' => 'dashicons-clock',
      'position' => 2
  ));
}

function hex2rgba($hex, $alpha = 1) {
  $hex = str_replace("#", "", $hex);

  if(strlen($hex) == 3) {
    $r = hexdec(substr($hex,0,1).substr($hex,0,1));
    $g = hexdec(substr($hex,1,1).substr($hex,1,1));
    $b = hexdec(substr($hex,2,1).substr($hex,2,1));
  } else {
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
  }
  
  $rgba = array($r, $g, $b);
  array_push($rgba, $alpha);

  return 'rgba(' . implode(", ", $rgba) .')'; // returns an array with the rgb values
}

function category2color($category_id) {
  $match = '#f3d9c2';
  while ( have_rows('category_colors', 'option') ) : the_row();
    if ( in_array($category_id, get_sub_field('category'))) {
      $match = get_sub_field('color');
    }
  endwhile;
  return $match;
}

function pageid2color($page_id) {
  $match = '#e6d3d3';
  while ( have_rows('category_colors', 'option') ) : the_row();
    if ( in_array($page_id, get_sub_field('pages')) ) {
      $match = get_sub_field('color');
    }
  endwhile;
  return $match;
}

function isDownloadAllowed($days = [], $start = '', $end = '') {
  $today_name = date('l');
  $today_date = strtotime(date("d/m/Y"));

  $start = empty($start) ? $today_date : strtotime($start);
  $end = empty($end) ? $today_date : strtotime($end);
  if ( (!empty($days) && in_array($today_name, $days)) || ($today_date <= $start && $today_date >= $end)) return true;
  return false;
}

function isContentAllowed($id, $type = true) {
  global $post;
  $page_slug = $post->post_name;

  $filter = array("sportscheck", "muenchen", "ziele", "moeglichkeiten", "trainer", "fussballschule", "tennisschule", "event", "jobs", "-in-", "toplage", "-");

  if ( $page_slug == 'scheck-in-restaurant-muenchen' ) {
    $page_slug = 'gastronomie';
  } else if ( $page_slug == 'scheck-in-restaurant-jobs' ) {
    $page_slug = 'jobs';
  } else if ( $page_slug == 'sportscheck-event-location' ) {
    $page_slug = 'location';
  } else if ( $page_slug == 'sportscheck-hotel-toplage' ) {
    $page_slug = 'toplage';
  } else {
    $page_slug = str_replace($filter, "", $page_slug);
  };

  $content = get_post($id);
  if ( !is_front_page() && !is_home() ) :
    if ( !$type ) {
      $category_ids = get_field('attachment_category', $id);
      $category_slugs = get_categorySlugs($category_ids);
      if (!in_array($page_slug, $category_slugs)) return false;
    } else {
      if ( !has_category( $page_slug, $content )) return false;
    };
  endif;
  if ( (is_page('startseite') ) && has_category( 115, $content ) )  return false;
  if ( (is_page('startseite') && $type ) && !has_category( 108, $content ) )  return false;
  return true;
}

function get_categorySlugs($ids) {
  $slugs = [];
  if ( empty($ids) ) return false;
  foreach ( $ids as $id ) :
    $slugs[] = get_category( $id )->slug;
  endforeach;
  return $slugs;
}

function the_parent_ID() {
  global $post;
  if( $post->post_parent == 0 ) {
    return false;
  }
  $post_data = get_post($post->post_parent);
  return $post_data->ID;
}

function stripped_slug($post) {
  // this is what happens if the slugs has to be changed in the last days >.<
  // self-note: next time prepare for dynamic slugs, the page-id based design still worse
  $slug = $post->post_name;

  if ( $slug == 'scheck-in-restaurant-muenchen' ) {
    $result = 'gastronomie';
  } else if ( $slug == 'scheck-in-restaurant-jobs' ) {
    $result = 'jobs';
  } else {
    if ( strpos($slug, '-') !== false ) {
      // it contains delimeter
      if (is_page() && ( $post->post_parent > 0 || strpos($slug, 'event') !== false ) ) {
        // it's a child page, so we get the last part
        $result = substr($slug, strrpos($slug, '-') + 1);
        if ( $result == 'ziele' ) $result = 'meine-' . $result;
      } else {
        // we get the second part
        $result = strtok(str_replace('sportscheck-', '', $slug), '-');
      }
    } else {
      $result = $slug;
    }
  }
  return $result;
}

function get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page' ) {
  global $wpdb;
  $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, $post_type ) );
  if ( $page )
    return get_page($page, $output);
  return null;
}

function endsWith($haystack, $needle) {
  // search forward starting from end minus needle length characters
  return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function getPrimaryCategory($id) {
  $wpseo_primary_term = new WPSEO_Primary_Term( 'category', $id );
  $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
  $primary = get_term( $wpseo_primary_term );
  $category_ids = wp_get_post_categories($id);
  
  if (is_wp_error($primary)) {
    end($category_ids);
    return $category_ids[key($category_ids)];
  } else {
    return $primary->term_id;
  };
}

add_shortcode('button', 'button_shortcode');
function button_shortcode($atts, $content = null) {
  return str_replace('<a','<a class="btn btn-primary btn-sc" ',$content);
}

add_shortcode('CF7_ADD_RECIPIENT', 'cf7_add_recipient');
function cf7_add_recipient(){
  global $trainer_email;
  return $trainer_email;
}

add_shortcode('CF7_ADD_TEAMMEMBERNAME', 'cf7_add_team_member');
function cf7_add_team_member(){
  global $trainer_name;
  return $trainer_name;
}

add_action('wpcf7_before_send_mail', 'change_recipient' );
function change_recipient( $wpcf7 ) {
  // get current SUBMISSION instance
  $submission = WPCF7_Submission::get_instance();
  if ($submission) {
    $data = $submission->get_posted_data();
    //if (empty($data)) return;
    // extract posted data
    $name = isset($data['team-member']) ? $data['team-member'] : "";
    $recipient = isset($data['recipient']) ? $data['recipient'] : "";
    $mail = $wpcf7->prop('mail');
    $mail['recipient'] = $name . ' <' . $recipient . '>';
    $wpcf7->set_properties(array("mail" => $mail));
  }
  return $wpcf7;
}

add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
  // add the file extension to the array
  $existing_mimes['svg'] = 'mime/type';
  // call the modified list of extensions
  return $existing_mimes;
}

add_filter('acf/settings/path', 'sportscheck_acf_settings_path');
function sportscheck_acf_settings_path( $path ) {
  // update path
  $path = get_stylesheet_directory() . '/acf/';
  // return
  return $path;
}

add_filter('acf/settings/dir', 'sportscheck_acf_settings_dir');
function sportscheck_acf_settings_dir( $dir ) {
  // update path
  $dir = get_stylesheet_directory_uri() . '/acf/';
  // return
  return $dir;
}

// Hide ACF field group menu item
//add_filter('acf/settings/show_admin', '__return_false');

add_action('acf/input/admin_head', 'my_acf_admin_head');
function my_acf_admin_head() {
  ?>
  <style type="text/css">
    //.acf-settings-wrap .acf-field .acf-label {display: none;}
    .acf-gallery-attachment .thumbnail { background: #eee; }
  </style>
  <?php
}

// excerpt field for pages
add_action( 'init', 'excerpts_to_pages' );
function excerpts_to_pages() {
  add_post_type_support( 'page', 'excerpt' );
}

/* Widgets */
function widgets_init() {
  if ( class_exists( 'es_widget_register' ) ) :
    unregister_widget( 'es_widget_register' );
    register_widget( 'Custom_Widget_Email_Subscribers' );
  endif;
}
add_action('widgets_init', 'widgets_init');

function remove_unwanted_js(){
  wp_dequeue_script('es-widget');
}
add_filter('wp_footer', 'remove_unwanted_js');

if ( class_exists( 'es_widget_register' ) ) :
  Class Custom_Widget_Email_Subscribers extends es_widget_register {

    function widget( $args, $instance ) {
      extract( $args, EXTR_SKIP );

      $es_title 	= apply_filters( 'widget_title', empty( $instance['es_title'] ) ? '' : $instance['es_title'], $instance, $this->id_base );
      $es_desc	= $instance['es_desc'];
      $es_name	= $instance['es_name'];
      $es_group	= $instance['es_group'];

      echo '<form class="widget email-subscribers-2 widget_text elp-widget col-xs-12 col-md-10 col-md-push-1">';
      if( $es_title ) echo '<h4>' . $es_title . '</h4>';
      $url = home_url();
      ?>
      <div class="col-xs-12 col-md-6">
        <div class="filters-button-group dropdown">
          <a class="selected dropdown-toggle" data-toggle="dropdown">
            <div>
              <span>Anrede</span>
              <span class="icon icon-arrow_down"></span>
            </div>
          </a>
          <ul class="dropdown-menu">
            <li>Frau</li>
            <li>Herr</li>
          </ul>
        </div>
        <?php if( $es_name == "YES" ) { ?>
          <div class="form-group">
            <div class="controls">
              <input type="text" id="es_first_name" class="form-control" placeholder="<?php _e('First Name', 'sage'); ?>">
            </div>
          </div>
          <div class="form-group">
            <div class="controls">
              <input type="text" id="es_last_name" class="form-control" placeholder="<?php _e('Last Name', 'sage'); ?>">
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="col-xs-12 col-md-6">
        <div class="form-group">
          <input class="es_textbox_class form-control" name="es_txt_email" id="es_txt_email" onkeypress="if(event.keyCode==13) es_submit_page('<?php echo $url; ?>')" value="" maxlength="225" type="text" placeholder="<?php _e('E-Mail Adresse', 'sage'); ?>">
        </div>
        <div class="form-group">
          <div class="checkbox">
            <?php $id = rand(100, 10000); ?>
            <input id="terms_<?= $id ?>" type="checkbox" />
            <label for="terms_<?= $id ?>">Ich akzeptiere die <a href="<?= get_home_url() ?>/wp-content/uploads/2017/03/Datenschutzerklärung_Scheck-Allwetteranlage-GmbH-Co-KG.pdf" target="_blank">Datenschutzerklärung</a> und möchte den Newsletter bestellen</label>
          </div>
        </div>
        <div class="form-group">
          <input class="es_textbox_button btn btn-primary text-uppercase" name="es_txt_button" id="es_txt_button" onClick="return es_submit_page('<?php echo $url; ?>')" value="<?= __('Submit', 'sage') ?>" type="button">
          <input type="reset" value="Abmelden" />
        </div>
        <input name="es_txt_group" id="es_txt_group" value="<?php echo $es_group; ?>" type="hidden">
        <input id="name2_<?= ceil($id/3.33) ?>" type="checkbox" /><?php /* anti-spam */ ?>
      </div>
      <div class="es_textbox">
        <input class="es_textbox_class" name="es_txt_name" id="es_txt_name" value="" maxlength="225" type="hidden" placeholder="<?php _e('Name', ES_TDOMAIN); ?>">
      </div>
      </form>
      <div id="es_msg"></div>
      <script>
        jQuery('#newsletter .es_textbox').append("<input id='name_<?= ceil($id/3.33) ?>' type='checkbox' checked/>");
        var es_submit_page = function (url) {
          es_email = document.getElementById("es_txt_email");
          es_fname = document.getElementById("es_first_name").value;
          es_lname = document.getElementById("es_last_name").value;
          es_name = es_fname.concat(' ',es_lname);
          es_group = document.getElementById("es_txt_group");
          if( es_email.value == "" ) {
            alert(es_widget_page_notices.es_email_notice);
            es_email.focus();
            return false;
          }
          if( es_email.value!="" && ( es_email.value.indexOf("@",0) == -1 || es_email.value.indexOf(".",0) == -1 )) {
            alert(es_widget_page_notices.es_incorrect_email);
            es_email.focus();
            es_email.select();
            return false;
          }
          if ( !document.getElementById('terms_<?= $id ?>').checked ) {
            return false;
          }

          if ( document.getElementById('name2_<?= ceil($id/3.33) ?>').checked ) {
            return false;
          }
          if ( !document.getElementById('name_<?= ceil($id/3.33) ?>').checked ) {
            return false;
          }

          document.getElementById("es_msg").innerHTML = es_widget_page_notices.es_load_more;
          var date_now = "";
          var mynumber = Math.random();
          var str= "es_email="+ encodeURI(es_email.value) + "&es_name=" + encodeURI(es_name) + "&es_group=" + encodeURI(es_group.value) + "&timestamp=" + encodeURI(date_now) + "&action=" + encodeURI(mynumber);
          es_submit_request(url+'/?es=subscribe', str);
        }
        var http_req = false;
        var es_submit_request = function (url, parameters) {
          http_req = false;
          if (window.XMLHttpRequest) {
            http_req = new XMLHttpRequest();
            if (http_req.overrideMimeType) {
              http_req.overrideMimeType('text/html');
            }
          } else if (window.ActiveXObject) {
            try {
              http_req = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
              try {
                http_req = new ActiveXObject("Microsoft.XMLHTTP");
              } catch (e) {

              }
            }
          }
          if (!http_req) {
            alert(es_widget_page_notices.es_ajax_error);
            return false;
          }
          http_req.onreadystatechange = eemail_submitresult;
          http_req.open('POST', url, true);
          http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          // http_req.setRequestHeader("Content-length", parameters.length);
          // http_req.setRequestHeader("Connection", "close");
          http_req.send(parameters);
        }

        var eemail_submitresult = function () {
          //alert(http_req.readyState);
          //alert(http_req.responseText);
          if (http_req.readyState == 4) {
            if (http_req.status == 200) {
              if (http_req.readyState==4 || http_req.readyState=="complete") {
                if( (http_req.responseText).trim() == "subscribed-successfully" || (http_req.responseText).trim() == "subscribed-pending-doubleoptin" ) {
                  if ((http_req.responseText).trim() == "subscribed-successfully") {
                    document.getElementById("es_msg").innerHTML = '<div class="success"><span class="icon icon-smile"></span><h3 class="text-uppercase text-primary">Vielen Dank für deine Anmeldung!</h3></div>';
                    document.getElementById("es_txt_email").value = "";
                  }
                  if ((http_req.responseText).trim() == "subscribed-pending-doubleoptin") {
                    //alert(es_widget_notices.es_success_notice);
                    //document.getElementById("es_msg").innerHTML = es_widget_notices.es_success_message;
                    document.getElementById("es_msg").innerHTML = '<div class="success"><span class="icon icon-smile"></span><h3 class="text-uppercase text-primary">Vielen Dank für deine Anmeldung!</h3></div>';
                    document.getElementById("es_txt_email").value = "";
                    document.getElementById("es_txt_name").value = "";
                  }
                  jQuery('form.elp-widget').invisible();
                  jQuery('#es_msg').show(0);
                } else {
                  if((http_req.responseText).trim() == "already-exist") {
                    document.getElementById("es_msg").innerHTML = es_widget_page_notices.es_email_exists;
                  } else if((http_req.responseText).trim() == "unexpected-error") {
                    document.getElementById("es_msg").innerHTML = es_widget_page_notices.es_error;
                  } else if((http_req.responseText).trim() == "invalid-email") {
                    document.getElementById("es_msg").innerHTML = es_widget_page_notices.es_invalid_email;
                  } else {
                    document.getElementById("es_msg").innerHTML = es_widget_page_notices.es_try_later;
                    document.getElementById("es_txt_email").value="";
                    document.getElementById("es_txt_name").value="";
                  }
                  jQuery('form.elp-widget').invisible().delay(5000).visible();
                  jQuery('#es_msg').show(0).delay(5000).hide(0);
                }
              }
            } else {
              alert(es_widget_notices.es_problem_request);
            }
          }
        }
      </script>
      <?php
    }
  }
endif;

add_action( 'admin_menu', 'custom_menu_page_removing', 999 );
function custom_menu_page_removing() {
  remove_menu_page( 'pods' );
  remove_menu_page( 'edit-comments.php' );
}

// redirecting
if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=769' ) ) {
  wp_redirect( get_permalink(9), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=1' )
    && 0 !== stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=1375' )) {
  wp_redirect( get_permalink(19), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=2' )
    && 0 !== stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=22' )) {
  wp_redirect( get_permalink(17), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/hotelhome.php?id=4' ) ) {
  wp_redirect( get_permalink(11), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/tennishome.php?id=3' ) ) {
  wp_redirect( get_permalink(21), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=22' ) || 
     0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=505' ) || 
     0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=508' )  ) {
  wp_redirect( get_permalink(520), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=1375' ) || 
     0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=441' ) ) {
  wp_redirect( get_permalink(571), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=638' ) ) {
  wp_redirect( get_permalink(25), 301 );
  exit;
}

if ( 
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=69' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=74' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=72' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=383' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=375' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=558' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=921' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=559' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=622' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=432' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=623' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=65' ) ||
       0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=73' )
   ) {
  wp_redirect( get_permalink(268), 301 );
  exit;
}

if ( 0 === stripos( $_SERVER['REQUEST_URI'], '/seite.php?id=803' ) ) {
  wp_redirect( home_url(), 301 );
  exit;
}

function numeric_posts_nav($wp_query) {

  /** Stop execution if there's only 1 page */
  if( $wp_query->max_num_pages <= 1 )
    return;

  $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
  $max   = intval( $wp_query->max_num_pages );
  //$screenWidth = window_screen_width;

    /*if( $screenWidth <= 479 ){
        if ( $paged >= 1 )
            $links[] = $paged;

        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
        }

        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 1;
        }
    } else {*/
        /**	Add current page to the array */
        if ( $paged >= 1 )
            $links[] = $paged;

        /**	Add the pages around the current page to the array */
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
    //}

  echo '<div class="navigation"><ul>' . "\n";

  /**	Link to first page, plus ellipses if necessary */
  if ( ! in_array( 1, $links ) ) {
    $class = 1 == $paged ? ' class="active"' : '';

    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

    if ( ! in_array( 2, $links ) )
      echo '<li>…</li>';
  }

  /**	Link to current page, plus 2 pages in either direction if necessary */
  sort( $links );
  foreach ( (array) $links as $link ) {
    $class = $paged == $link ? ' class="active"' : '';
    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
  }

  /**	Link to last page, plus ellipses if necessary */
  if ( ! in_array( $max, $links ) ) {
    if ( ! in_array( $max - 1, $links ) )
      echo '<li>…</li>' . "\n";

    $class = $paged == $max ? ' class="active"' : '';
    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
  }

  echo '</ul></div>' . "\n";

}

add_action( 'admin_init', 'add_acf_variables' );
function add_acf_variables() {
  if (get_admin_page_parent() == 'email-subscribers') {
    acf_form_head();
  }
}

// Disable auto save acf on newsletter
add_action('acf/save_post', 'my_acf_save_post', 1);
function my_acf_save_post( $post_id ) {
  global $wpdb;
  // Newsletter not saved yet :(
  if (substr( $post_id, 0, 10 ) === "newsletter" && $wpdb->insert_id == 0) {
    return;
  }

  // bail early if no ACF data
  if ( empty($_POST['acf']) ) {
    return;
  }
}

function in_array_r($needle, $haystack, $strict = false) {
  foreach ($haystack as $item) {
    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
      return true;
    }
  }

  return false;
}

function get_meta_values( $meta_key,  $post_type = 'post' ) {
  $posts = get_posts(
    array(
      'post_type' => $post_type,
      'meta_key' => $meta_key,
      'posts_per_page' => -1,
    )
  );

  $meta_values = array();
  foreach( $posts as $post ) {
    $meta_values[] = get_post_meta( $post->ID, $meta_key, true );
  }

  return $meta_values;
}

function noindex_post( $postID ) {
  $selected_categories = get_meta_values( 'category', 'page' );
  $post_categories = get_the_category( $postID );
  $i = 0;
  while ( $i < count($post_categories) ) :
    if ( in_array_r($post_categories[$i]->term_id, $selected_categories) ) :
      return true;
      break;
    endif;
    $i++;
  endwhile;
  return false;
}

/*
function mailtrap($phpmailer) {
  $phpmailer->isSMTP();
  $phpmailer->Host = 'smtp.mailtrap.io';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 2525;
  $phpmailer->Username = 'bdc6c38a29e6c4';
  $phpmailer->Password = '4cb809f814b14a';
}

add_action('phpmailer_init', 'mailtrap');
*/