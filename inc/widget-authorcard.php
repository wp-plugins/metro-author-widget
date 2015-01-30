<?php

//Widget Registration.

function radlabs_load_widget() {
  register_widget( 'RL_Metro_Author_Widget' );
}

class RL_Metro_Author_Widget extends WP_Widget {

  // Widget Class Constructor
  function __construct() {
    parent::__construct(
      'rl_metro_widget',
      __( 'Metro Author', RADLABS_TEXTDOMAIN ),
      array( 'description' => __( 'Show Metro style Author Widget in sidebar or footer.', RADLABS_TEXTDOMAIN ), )
    );

    add_action('wp_enqueue_scripts', array($this, 'metro_card_register_scripts'));
    add_action('admin_enqueue_scripts', array(&$this, 'mac_admin_scripts'));
  }

  function metro_card_register_scripts() {
    // JS
    wp_enqueue_script('jquery');
    wp_register_script('metro_card_script', RLMAC_URL . '/assets/js/MetroJs.min.js', array('jquery'), RLMAC_VERSION);
    wp_register_script('metro_card_js', RLMAC_URL . '/assets/js/script.js', array('jquery'), RLMAC_VERSION);

    // CSS
    wp_register_style('metro_card_style', RLMAC_URL . '/assets/css/MetroJs.min.css', array(), RLMAC_VERSION);
  }
  function mac_admin_scripts($hook) {
    if ($hook != 'widgets.php')
        return;
    wp_enqueue_media();
    wp_register_style( 'mac_style', RLMAC_URL . '/assets/css/admin.css', false, SIW_VER );
    wp_enqueue_style( 'mac_style' );
    wp_register_script('mac_widget_admin', RLMAC_URL . '/assets/js/admin.js', array('jquery'), RLMAC_VERSION, true);
    wp_register_script('mac_widget_img', RLMAC_URL . '/assets/js/image-uploader.js', array('jquery'), RLMAC_VERSION, true);
    wp_enqueue_script('mac_widget_admin');
    wp_enqueue_script('mac_widget_img');
  }

  // Front-end View
  public function widget( $args, $instance ) {
    wp_enqueue_script('metro_card_script');
    wp_enqueue_script('metro_card_js');
    wp_enqueue_style('metro_card_style');

    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
    $bgtile_color = $instance['me_bg_color'];
    if ( empty($bgtile_color) ){ $bgtile_color = "#005dff"; }
    ?>
    <div class="mac-wrap">
      <div id="tile1" class="live-tile" data-stack="true" data-stops="50%,100%,0" data-delay="3000">
        <div>
        <?php
          if( $instance['image'] ){
            echo '<a href="'.get_author_posts_url($instance['author_list']).'"><img src="'.esc_url($instance['image']).'" alt="'.$instance['title'].'" class="avatar avatar-200 photo"/></a>';
            } else {
              echo '<a href="'.get_author_posts_url($instance['author_list']).'">'. get_avatar( $instance['author_list'], apply_filters( 'radlabs_author_bio_avatar_size', 200 )) .'</a>';
            }
        ?>
      </div>
      <div class="mac-author-info" style="background: <?php echo $bgtile_color; ?>">
        <?php
          if($instance['social_icon'] == true) {
            include( "svg-icons.php" );
          }
        ?>
        <h3 class="mac-me">
          <?php
            $mac_a_firstname = get_the_author_meta( 'first_name', $instance['author_list'] );
            $mac_a_username = get_the_author_meta( 'user_login', $instance['author_list'] );
            if($instance['me_firstname'] == true) {
              if(empty($mac_a_firstname)){
                echo $mac_a_username;
                }else{
                  echo $mac_a_firstname;
                }
            }else{ echo 'Me'; }
          ?>
        </h3>
      </div>
    </div>
  </div>
  <?php echo $args['after_widget']; }

  // Widget Layout
  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '', RADLABS_TEXTDOMAIN );
    $me_bg_color = ! empty( $instance['me_bg_color'] ) ? $instance['me_bg_color'] : __( '#005dff', RADLABS_TEXTDOMAIN );
    $author_list = $instance['author_list'];
    $social_icon = isset( $instance[ 'social_icon' ] ) ? esc_attr( $instance[ 'social_icon' ] ) : 1;
    $me_firstname = isset( $instance[ 'me_firstname' ] ) ? esc_attr( $instance[ 'me_firstname' ] ) : 1;
    $ic_facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : __( '', RADLABS_TEXTDOMAIN );
    $ic_twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : __( '', RADLABS_TEXTDOMAIN );
    $ic_linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : __( '', RADLABS_TEXTDOMAIN );
    $ic_gplus = ! empty( $instance['gplus'] ) ? $instance['gplus'] : __( '', RADLABS_TEXTDOMAIN );
    $ic_instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : __( '', RADLABS_TEXTDOMAIN );
    $ic_github = ! empty( $instance['github'] ) ? $instance['github'] : __( '', RADLABS_TEXTDOMAIN );
    $ic_youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : __( '', RADLABS_TEXTDOMAIN );
    $image = ( isset( $instance['image'] ) ? $instance['image'] : '' );
  ?>

  <div class="mac_options_form">
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', RADLABS_TEXTDOMAIN ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('author_list'); ?>"><?php _e('Select Author:', RADLABS_TEXTDOMAIN); ?></label><br/>
      <?php $siteusers = get_users(); ?>
      <select id="<?php echo $this->get_field_id('author_list'); ?>" name="<?php echo $this->get_field_name('author_list'); ?>">
      <?php foreach ($siteusers as $user) {?>
        <option value="<?php echo $user->ID; ?>" <?php selected($author_list, $user->ID, true); ?>><?php _e($user->display_name, RADLABS_TEXTDOMAIN); ?></option>
        <?php } ?>
      </select>
    </p>


    <script type="text/javascript">
      jQuery(document).ready(function($) {
        jQuery('.color-picker').on('focus', function(){
            var parent = jQuery(this).parent();
            jQuery(this).wpColorPicker()
            parent.find('.wp-color-result').click();
        });
      });
    </script>
    <p>
      <label for="<?php echo $this->get_field_id( 'me_bg_color' ); ?>" style="display:block;"><?php _e( 'Background Color', RADLABS_TEXTDOMAIN ); ?></label>
      <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'me_bg_color' ); ?>" name="<?php echo $this->get_field_name( 'me_bg_color' ); ?>" type="text" value="<?php echo esc_attr( $me_bg_color ); ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image:', RADLABS_TEXTDOMAIN ); ?> <span class="mac-info" title="<?php _e('Select image or enter external image url.', RADLABS_TEXTDOMAIN); ?>"></span></label>
      <input class="widefat mac-img" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>" />
      <span class="submit">
        <input type="button" name="submit" id="submit" class="button delete button-primary mac-upload_image_button" value="Select image">
        <input type="button" name="submit" id="submit" class="button delete button-secondary mac-remove-image" value="X">
      </span>
      <span><em>*This image will replace default Gravatar image</em></span>
    </p>
    <hr/>

    <p class="check">
      <label for="<?php echo $this->get_field_id("me_firstname"); ?>" />
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_name("me_firstname"); ?>" name="<?php echo $this->get_field_name("me_firstname"); ?>" value="1" <?php checked( 1, $instance['me_firstname'], true ); ?> />
        <strong><?php _e( 'Show Author First Name', RADLABS_TEXTDOMAIN); ?></strong>
      </label><br/>
      <span>This will replace 'Me' text with Author first name.</span>
    </p>

    <p class="check">
      <label for="<?php echo $this->get_field_id("social_icon"); ?>">
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_name("social_icon"); ?>" name="<?php echo $this->get_field_name("social_icon"); ?>" value="1" <?php checked( 1, $instance['social_icon'], true ); ?> />
        <strong><?php _e( 'Show Social Icons', RADLABS_TEXTDOMAIN); ?></strong>
      </label>
    </p>

    <h4 class="mac-social-settings"><a href="#">Social Media Settings</a></h4>
    <p>
      <div id="mac-social-box" style="display: none;">
        <p>
          <strong><label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook:', RADLABS_TEXTDOMAIN ); ?></label></strong>
          <input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_attr( $ic_facebook ); ?>">
          <span><em>example: http://facebook.com/<strong>username</strong></em></span>
        </p>
        <p>
          <strong><label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter:', RADLABS_TEXTDOMAIN ); ?></label></strong>
          <input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $ic_twitter ); ?>">
          <span><em>Enter your Twitter username.</em></span>
        </p>
        <p>
          <strong><label for="<?php echo $this->get_field_id( 'linkedin' ); ?>"><?php _e( 'LinkedIn:', RADLABS_TEXTDOMAIN ); ?></label></strong>
          <input class="widefat" id="<?php echo $this->get_field_id( 'linkedin' ); ?>" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" type="text" value="<?php echo esc_attr( $ic_linkedin ); ?>">
        <span><em>Enter your LinkedIn username.</em></span>
        </p>
        <p>
          <strong><label for="<?php echo $this->get_field_id( 'gplus' ); ?>"><?php _e( 'Google Plus:', RADLABS_TEXTDOMAIN ); ?></label></strong>
          <input class="widefat" id="<?php echo $this->get_field_id( 'gplus' ); ?>" name="<?php echo $this->get_field_name( 'gplus' ); ?>" type="text" value="<?php echo esc_attr( $ic_gplus ); ?>">
          <span><em>Enter your Google Plus Profile ID.</em></span>
        </p>
        <p>
          <strong><label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'Instagram:', RADLABS_TEXTDOMAIN ); ?></label></strong>
          <input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" type="text" value="<?php echo esc_attr( $ic_instagram ); ?>">
          <span><em>Enter your Instagram username.</em></span>
        </p>
        <p>
          <strong><label for="<?php echo $this->get_field_id( 'github' ); ?>"><?php _e( 'GitHub:', RADLABS_TEXTDOMAIN ); ?></label></strong>
          <input class="widefat" id="<?php echo $this->get_field_id( 'github' ); ?>" name="<?php echo $this->get_field_name( 'github' ); ?>" type="text" value="<?php echo esc_attr( $ic_github ); ?>">
          <span><em>Enter your GitHub username.</em></span>
        </p>
        <p>
          <strong><label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'YouTube:', RADLABS_TEXTDOMAIN ); ?></label></strong>
          <input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo esc_attr( $ic_youtube ); ?>">
          <span><em>Enter your YouTube username.</em></span>
        </p>
      </div>
    </p>
  </div>

<?php
  }
  // Save Data
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['me_bg_color'] = ( ! empty( $new_instance['me_bg_color'] ) ) ? strip_tags( $new_instance['me_bg_color'] ) : '';
    $instance['author_list'] = $new_instance['author_list'];
    $instance['social_icon'] = $new_instance['social_icon'];
    $instance['me_firstname'] = $new_instance['me_firstname'];
    $instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ) : '';
    $instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? strip_tags( $new_instance['twitter'] ) : '';
    $instance['linkedin'] = ( ! empty( $new_instance['linkedin'] ) ) ? strip_tags( $new_instance['linkedin'] ) : '';
    $instance['gplus'] = ( ! empty( $new_instance['gplus'] ) ) ? strip_tags( $new_instance['gplus'] ) : '';
    $instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? strip_tags( $new_instance['instagram'] ) : '';
    $instance['github'] = ( ! empty( $new_instance['github'] ) ) ? strip_tags( $new_instance['github'] ) : '';
    $instance['youtube'] = ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ) : '';
    $instance['image'] = ( ! empty( $new_instance['image'] ) ) ? esc_url( strip_tags( $new_instance['image'] ) ) : '';
    return $instance;
  }
}
add_action( 'widgets_init', 'radlabs_load_widget' );