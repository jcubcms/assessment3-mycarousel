<?php
/*****************************************************
* My Carousel Slider
* https://varunv.sgedu.site/wordpress
* ----------------------------------------------------
* my-settings.php
* Code to handle the Settings page
******************************************************/

///////////////////
// SETTINGS PAGE
///////////////////

// Set up settings defaults
register_activation_hook(__FILE__, 'my_set_options');
function my_set_options (){
    $defaults = array(
        'interval' => '5000',
        'showtitle' => 'true',
        'showcaption' => 'true',
        'showcontrols' => 'true',
        'showindicators' => 'true',
        'customprev' => '',
        'customnext' => '',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'category' => '',
        'before_caption_div' => '',
        'after_caption_div' => '',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
        'before_caption' => '<p>',
        'after_caption' => '</p>',
        'image_size' => 'full',
        'link_button' => '1',
        'link_button_text' => 'Read more',
        'link_button_class' => 'btn btn-default pull-right',
        'link_button_before' => '',
        'link_button_after' => '',
        'id' => '',
        'twbs' => '4',
        'use_background_images' => '0',
        'background_images_height' => '500',
        'background_images_style_size' => 'cover',
        'use_javascript_animation' => '1',
        'use_carousel_fade' => '0',
    );
    add_option('my_settings', $defaults);
}
// Clean up on uninstall
register_activation_hook(__FILE__, 'my_deactivate');
function my_deactivate(){
    delete_option('my_settings');
}


// Render the settings page
class my_settings_page {
    // Holds the values to be used in the fields callbacks
    private $options;

    // Start up
    public function __construct() {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    // Add settings page
    public function add_plugin_page() {
        add_submenu_page('edit.php?post_type=my', __('Settings', 'myCarousel'), __('Settings', 'myCarousel'), 'manage_options', 'myCarousel', array($this,'create_admin_page'));
    }

    // Options page callback
    public function create_admin_page() {
        // Set class property
        $this->options = get_option( 'my_settings' );
        if(!$this->options){
            my_set_options ();
            $this->options = get_option( 'my_settings' );
        }
        ?>
        <div class="wrap">
        <h2>CPT Bootstrap Carousel <?php _e('Settings', 'myCarousel'); ?></h2>
        <p><?php printf(__('You can set the default behaviour of your carousels here. Most of these settings can be overridden by using %s shortcode attributes %s.', 'myCarousel'),'<a href="http://wordpress.org/plugins/myCarousel/" target="_blank">', '</a>'); ?></p>

                <form method="post" action="options.php">
                <?php
                        settings_fields( 'my_settings' );
                        do_settings_sections( 'myCarousel' );
                        submit_button();
                ?>
                </form>
        </div>
        <?php
    }

    // Register and add settings
    public function page_init() {
        register_setting(
                'my_settings', // Option group
                'my_settings', // Option name
                array( $this, 'sanitize' ) // Sanitize
        );

        // Sections
        add_settings_section(
                'my_settings_behaviour', // ID
                __('Carousel Behaviour', 'myCarousel'), // Title
                array( $this, 'my_settings_behaviour_header' ), // Callback
                'myCarousel' // Page
        );
        add_settings_section(
                'my_settings_setup', // ID
                __('Carousel Setup', 'myCarousel'), // Title
                array( $this, 'my_settings_setup' ), // Callback
                'myCarousel' // Page
        );
        add_settings_section(
                'my_settings_link_buttons', // ID
                __('Link Buttons', 'myCarousel'), // Title
                array( $this, 'my_settings_link_buttons_header' ), // Callback
                'myCarousel' // Page
        );
        add_settings_section(
                'my_settings_markup', // ID
                __('Custom Markup', 'myCarousel'), // Title
                array( $this, 'my_settings_markup_header' ), // Callback
                'myCarousel' // Page
        );

        // Behaviour Fields
        add_settings_field(
                'interval', // ID
                __('Slide Interval (milliseconds)', 'myCarousel'), // Title
                array( $this, 'interval_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );
        add_settings_field(
                'showtitle', // ID
                __('Show Slide Titles?', 'myCarousel'), // Title
                array( $this, 'showtitle_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );
        add_settings_field(
                'showcaption', // ID
                __('Show Slide Captions?', 'myCarousel'), // Title
                array( $this, 'showcaption_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );
        add_settings_field(
                'showcontrols', // ID
                __('Show Slide Controls?', 'myCarousel'), // Title
                array( $this, 'showcontrols_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );
        add_settings_field(
                'showindicators', // ID
                __('Show Slide Indicators?', 'myCarousel'), // Title
                array( $this, 'showindicators_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );
        add_settings_field(
                'orderby', // ID
                __('Order Slides By', 'myCarousel'), // Title
                array( $this, 'orderby_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );
        add_settings_field(
                'order', // ID
                __('Ordering Direction', 'myCarousel'), // Title
                array( $this, 'order_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );
        add_settings_field(
                'category', // ID
                __('Restrict to Category', 'myCarousel'), // Title
                array( $this, 'category_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_behaviour' // Section
        );

        // Carousel Setup Section
        add_settings_field(
                'twbs', // ID
                __('Twitter Bootstrap Version', 'myCarousel'), // Title
                array( $this, 'twbs_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_setup' // Section
        );
        add_settings_field(
                'image_size', // ID
                __('Image Size', 'myCarousel'), // Title
                array( $this, 'image_size_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_setup' // Section
        );

        add_settings_field(
                'use_background_images', // ID
                __('Use background images?', 'myCarousel'), // Title
                array( $this, 'use_background_images_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_setup' // Section
        );
        add_settings_field(
                'background_images_height', // ID
                __('Height if using bkgrnd images (px)', 'myCarousel'), // Title
                array( $this, 'background_images_height_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_setup' // Section
        );
        add_settings_field(
                'background_images_style_size', // ID
                __('Background images size style', 'myCarousel'), // Title
                array( $this, 'background_images_style_size_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_setup' // Section
        );
        add_settings_field(
                'use_javascript_animation', // ID
                __('Use Javascript to animate carousel?', 'myCarousel'), // Title
                array( $this, 'use_javascript_animation_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_setup' // Section
        );
        add_settings_field(
                'use_carousel_fade', // ID
                __('Crossfade images instead of slide animation?', 'myCarousel'), // Title
                array( $this, 'use_carousel_fade_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_setup' // Section
        );

        // Link buttons
        add_settings_field(
                'link_button', // ID
                __('Show links as button in caption', 'myCarousel'), // Title
                array( $this, 'link_button_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_link_buttons' // Section
        );
        add_settings_field(
                'link_button_text', // ID
                __('Default text for link buttons', 'myCarousel'), // Title
                array( $this, 'link_button_text_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_link_buttons' // Section
        );
        add_settings_field(
                'link_button_class', // ID
                __('Class for link buttons', 'myCarousel'), // Title
                array( $this, 'link_button_class_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_link_buttons' // Section
        );
        add_settings_field(
                'link_button_before', // ID
                __('HTML before link buttons', 'myCarousel'), // Title
                array( $this, 'link_button_before_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_link_buttons' // Section
        );
        add_settings_field(
                'link_button_after', // ID
                __('HTML after link buttons', 'myCarousel'), // Title
                array( $this, 'link_button_after_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_link_buttons' // Section
        );

        // Markup Section
        add_settings_field(
                'customprev', // ID
                __('Custom prev button class', 'myCarousel'), // Title
                array( $this, 'customprev_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );
        add_settings_field(
                'customnext', // ID
                __('Custom next button class', 'myCarousel'), // Title
                array( $this, 'customnext_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );
        add_settings_field(
                'before_caption_div', // ID
                __('HTML before caption div', 'myCarousel'), // Title
                array( $this, 'before_caption_div_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );
        add_settings_field(
                'after_caption_div', // ID
                __('HTML after caption div', 'myCarousel'), // Title
                array( $this, 'after_caption_div_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );
        add_settings_field(
                'before_title', // ID
                __('HTML before title', 'myCarousel'), // Title
                array( $this, 'before_title_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );
        add_settings_field(
                'after_title', // ID
                __('HTML after title', 'myCarousel'), // Title
                array( $this, 'after_title_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );
        add_settings_field(
                'before_caption', // ID
                __('HTML before caption text', 'myCarousel'), // Title
                array( $this, 'before_caption_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );
        add_settings_field(
                'after_caption', // ID
                __('HTML after caption text', 'myCarousel'), // Title
                array( $this, 'after_caption_callback' ), // Callback
                'myCarousel', // Page
                'my_settings_markup' // Section
        );

    }

    // Sanitize each setting field as needed -  @param array $input Contains all settings fields as array keys
    public function sanitize( $input ) {
        $new_input = array();
        foreach($input as $key => $var){
            if($key == 'twbs' || $key == 'interval' || $key == 'background_images_height'){
                $new_input[$key] = absint( $input[$key] );
            } else if ($key == 'link_button_before' || $key == 'link_button_after' || $key == 'before_title' || $key == 'after_title' || $key == 'before_caption' || $key == 'after_caption' || $key == 'before_caption_div' || $key == 'after_caption_div'){
                $new_input[$key] = $input[$key]; // Don't sanitise these, meant to be html!
            } else {
                $new_input[$key] = sanitize_text_field( $input[$key] );
            }
        }
        return $new_input;
    }

    // Print the Section text
    public function my_settings_behaviour_header() {
            echo '<p>'.__('Basic setup of how each Carousel will function, what controls will show and which images will be displayed.', 'myCarousel').'</p>';
    }
    public function my_settings_setup() {
            echo '<p>'.__('Change the setup of the carousel - how it functions.', 'myCarousel').'</p>';
    }
    public function my_settings_link_buttons_header() {
            echo '<p>'.__('Options for using a link button instead of linking the image directly.', 'myCarousel').'</p>';
    }
    public function my_settings_markup_header() {
            echo '<p>'.__('Customise which CSS classes and HTML tags the Carousel uses.', 'myCarousel').'</p>';
    }

    // Callback functions - print the form inputs
    // Carousel behaviour
    public function interval_callback() {
            printf('<input type="text" id="interval" name="my_settings[interval]" value="%s" size="15" />',
                    isset( $this->options['interval'] ) ? esc_attr( $this->options['interval']) : '');
            echo '<p class="description">'.__('How long each image shows for before it slides. Set to 0 to disable animation.', 'myCarousel').'</p>';
    }
    public function showtitle_callback() {
        if(isset( $this->options['showtitle'] ) && $this->options['showtitle'] == 'false'){
            $my_showtitle_t = '';
            $my_showtitle_f = ' selected="selected"';
        } else {
            $my_showtitle_t = ' selected="selected"';
            $my_showtitle_f = '';
        }
        print '<select id="showtitle" name="my_settings[showtitle]">
            <option value="true"'.$my_showtitle_t.'>'.__('Show', 'myCarousel').'</option>
            <option value="false"'.$my_showtitle_f.'>'.__('Hide', 'myCarousel').'</option>
        </select>';
    }
    public function showcaption_callback() {
        if(isset( $this->options['showcaption'] ) && $this->options['showcaption'] == 'false'){
            $my_showcaption_t = '';
            $my_showcaption_f = ' selected="selected"';
        } else {
            $my_showcaption_t = ' selected="selected"';
            $my_showcaption_f = '';
        }
        print '<select id="showcaption" name="my_settings[showcaption]">
            <option value="true"'.$my_showcaption_t.'>'.__('Show', 'myCarousel').'</option>
            <option value="false"'.$my_showcaption_f.'>'.__('Hide', 'myCarousel').'</option>
        </select>';
    }
    public function showcontrols_callback() {
        if(isset( $this->options['showcontrols'] ) && $this->options['showcontrols'] == 'false'){
            $my_showcontrols_t = '';
            $my_showcontrols_f = ' selected="selected"';
            $my_showcontrols_c = '';
        } else if(isset( $this->options['showcontrols'] ) && $this->options['showcontrols'] == 'true'){
            $my_showcontrols_t = ' selected="selected"';
            $my_showcontrols_f = '';
            $my_showcontrols_c = '';
        } else if(isset( $this->options['showcontrols'] ) && $this->options['showcontrols'] == 'custom'){
            $my_showcontrols_t = '';
            $my_showcontrols_f = '';
            $my_showcontrols_c = ' selected="selected"';
        }
        print '<select id="showcontrols" name="my_settings[showcontrols]">
            <option value="true"'.$my_showcontrols_t.'>'.__('Show', 'myCarousel').'</option>
            <option value="false"'.$my_showcontrols_f.'>'.__('Hide', 'myCarousel').'</option>
            <option value="custom"'.$my_showcontrols_c.'>'.__('Custom', 'myCarousel').'</option>
        </select>';
    }
    public function showindicators_callback() {
        if(isset( $this->options['showindicators'] ) && $this->options['showindicators'] == 'false'){
            $my_showindicators_t = '';
            $my_showindicators_f = ' selected="selected"';
        } else if(isset( $this->options['showindicators'] ) && $this->options['showindicators'] == 'true'){
            $my_showindicators_t = ' selected="selected"';
            $my_showindicators_f = '';
        }
        print '<select id="showindicators" name="my_settings[showindicators]">
            <option value="true"'.$my_showindicators_t.'>'.__('Show', 'myCarousel').'</option>
            <option value="false"'.$my_showindicators_f.'>'.__('Hide', 'myCarousel').'</option>
        </select>';
    }
    public function orderby_callback() {
        $orderby_options = array (
            'menu_order' => __('Menu order, as set in Carousel overview page', 'myCarousel'),
            'date' => __('Date slide was published', 'myCarousel'),
            'rand' => __('Random ordering', 'myCarousel'),
            'title' => __('Slide title', 'myCarousel')
        );
        print '<select id="orderby" name="my_settings[orderby]">';
        foreach($orderby_options as $val => $option){
            print '<option value="'.$val.'"';
            if(isset( $this->options['orderby'] ) && $this->options['orderby'] == $val){
                print ' selected="selected"';
            }
            print ">$option</option>";
        }
        print '</select>';
    }
    public function order_callback() {
        if(isset( $this->options['order'] ) && $this->options['order'] == 'DESC'){
            $my_showcontrols_a = '';
            $my_showcontrols_d = ' selected="selected"';
        } else {
            $my_showcontrols_a = ' selected="selected"';
            $my_showcontrols_d = '';
        }
        print '<select id="order" name="my_settings[order]">
            <option value="ASC"'.$my_showcontrols_a.'>'.__('Ascending', 'myCarousel').'</option>
            <option value="DESC"'.$my_showcontrols_d.'>'.__('Decending', 'myCarousel').'</option>
        </select>';
    }
    public function category_callback() {
        $cats = get_terms('carousel_category');
        print '<select id="orderby" name="my_settings[category]">
            <option value="">'.__('All Categories', 'myCarousel').'</option>';
        foreach($cats as $cat){
            print '<option value="'.$cat->name.'"';
            if(isset( $this->options['category'] ) && $this->options['category'] == $cat->name){
                print ' selected="selected"';
            }
            print ">".$cat->name."</option>";
        }
        print '</select>';
    }

    // Setup Section
    public function twbs_callback() {
        if(isset( $this->options['twbs'] ) && $this->options['twbs'] == '2'){
            $my_twbs4 = '';
            $my_twbs3 = '';
            $my_twbs2 = ' selected="selected"';
        } else if(isset( $this->options['twbs'] ) && $this->options['twbs'] == '3'){
            $my_twbs4 = '';
            $my_twbs3 = ' selected="selected"';
            $my_twbs2 = '';
        } else {
            $my_twbs4 = ' selected="selected"';
            $my_twbs3 = '';
            $my_twbs2 = '';
        }
        print '<select id="twbs" name="my_settings[twbs]">
            <option value="2"'.$my_twbs2.'>2.x</option>
            <option value="3"'.$my_twbs3.'>3.x</option>
            <option value="4"'.$my_twbs4.'>4.x (Default)</option>
        </select>';
        echo '<p class="description">'.__("Set according to which version of Bootstrap you're using.", 'myCarousel').'</p>';
    }
    public function image_size_callback() {
        $image_sizes = get_intermediate_image_sizes();
        print '<select id="image_size" name="my_settings[image_size]">
            <option value="full"';
            if(isset( $this->options['image_size'] ) && $this->options['image_size'] == 'full'){
                print ' selected="selected"';
            }
            echo '>Full (default)</option>';
        foreach($image_sizes as $size){
            print '<option value="'.$size.'"';
            if(isset( $this->options['image_size'] ) && $this->options['image_size'] == $size){
                print ' selected="selected"';
            }
            print ">".ucfirst($size)."</option>";
        }
        print '</select>';
        echo '<p class="description">'.__("If your carousels are small, you can a smaller image size to increase page load times.", 'myCarousel').'</p>';
    }
    public function use_background_images_callback() {
        print '<select id="use_background_images" name="my_settings[use_background_images]">';
        print '<option value="0"';
        if(isset( $this->options['use_background_images'] ) && $this->options['use_background_images'] == 0){
            print ' selected="selected"';
        }
        echo '>No (default)</option>';
        print '<option value="1"';
        if(isset( $this->options['use_background_images'] ) && $this->options['use_background_images'] == 1){
            print ' selected="selected"';
        }
        echo '>Yes</option>';
        print '</select>';
        echo '<p class="description">'.__("Experimental feature - Use CSS background images so that pictures auto-fill the space available.", 'myCarousel').'</p>';
    }
    public function background_images_height_callback() {
        printf('<input type="text" id="background_images_height" name="my_settings[background_images_height]" value="%s" size="15" />',
                isset( $this->options['background_images_height'] ) ? esc_attr( $this->options['background_images_height']) : '500px');
        echo '<p class="description">'.__("If using background images above, how tall do you want the carousel to be?", 'myCarousel').'</p>';
    }

    public function use_javascript_animation_callback() {
        print '<select id="use_javascript_animation" name="my_settings[use_javascript_animation]">';
        print '<option value="1"';
        if(isset( $this->options['use_javascript_animation'] ) && $this->options['use_javascript_animation'] == 1){
            print ' selected="selected"';
        }
        echo '>Yes (default)</option>';
        print '<option value="0"';
        if(isset( $this->options['use_javascript_animation'] ) && $this->options['use_javascript_animation'] == 0){
            print ' selected="selected"';
        }
        echo '>No</option>';
        print '</select>';
        echo '<p class="description">'.__("The Bootstrap Carousel is designed to work usign data-attributes. Sometimes the animation doesn't work correctly with this, so the default is to include a small portion of Javascript to fire the carousel. You can choose not to include this here.", 'myCarousel').'</p>';
    }
    public function use_carousel_fade_callback() {
        print '<select id="use_carousel_fade" name="my_settings[use_carousel_fade]">';
        print '<option value="0"';
        if(isset( $this->options['use_carousel_fade'] ) && $this->options['use_carousel_fade'] == 0){
            print ' selected="selected"';
        }
        echo '>No (Default)</option>';
        print '<option value="1"';
        if(isset( $this->options['use_carousel_fade'] ) && $this->options['use_carousel_fade'] == 1){
            print ' selected="selected"';
        }
        echo '>Yes</option>';
        print '</select>';
        echo '<p class="description">'.__("If selected, images will cross-fade instead of sliding. BS4 only.", 'myCarousel').'</p>';
    }
    public function background_images_style_size_callback() {
        print '<select id="select_background_images_style_size" name="my_settings[select_background_images_style_size]">';
        print '<option value="cover"';
        if(isset( $this->options['select_background_images_style_size'] ) && $this->options['select_background_images_style_size'] === 'cover'){
            print ' selected="selected"';
        }
        echo '>Cover (default)</option>';
        print '<option value="contain"';
        if(isset( $this->options['select_background_images_style_size'] ) && $this->options['select_background_images_style_size'] === 'contain'){
            print ' selected="selected"';
        }
        echo '>Contain</option>';
        print '<option value="auto"';
        if(isset( $this->options['select_background_images_style_size'] ) && $this->options['select_background_images_style_size'] === 'auto'){
            print ' selected="selected"';
        }
        echo '>Auto</option>';
        print '</select>';
        echo '<p class="description">'.__('If you find that your images are not scaling correctly when using background images try switching the style to \'contain\' or \'auto\'', 'myCarousel').'</p>';
    }

    // Link buttons section
    public function link_button_callback(){
        print '<select id="link_button" name="my_settings[link_button]">';
        print '<option value="1"';
        if(isset( $this->options['link_button'] ) && $this->options['link_button'] == 1){
            print ' selected="selected"';
        }
        echo '>Yes</option>';
        print '<option value="0"';
        if(!isset( $this->options['link_button'] ) || $this->options['link_button'] == 0){
            print ' selected="selected"';
        }
        echo '>No (Default)</option>';
        print '</select>';
        echo '<p class="description">'.__("If a URL is set for a carousel image, this option will create a button in the caption instead of linking the image itself.", 'myCarousel').'</p>';
    }
    public function link_button_text_callback() {
            printf('<input type="text" id="link_button_text" name="my_settings[link_button_text]" value="%s" size="20" />',
                    isset( $this->options['link_button_text'] ) ? esc_attr( $this->options['link_button_text']) : 'Read more');
    }
    public function link_button_class_callback() {
            printf('<input type="text" id="link_button_class" name="my_settings[link_button_class]" value="%s" size="20" />',
                    isset( $this->options['link_button_class'] ) ? esc_attr( $this->options['link_button_class']) : 'btn btn-default pull-right');
            echo '<p class="description">'.__("Bootstrap style buttons must have the class <code>btn</code> and then one of the following: <code>btn-default</code>, <code>btn-primary</code>, <code>btn-success</code>, <code>btn-warning</code>, <code>btn-danger</code> or <code>btn-info</code>. No <code>.</code> prefixes. <code>pull-right</code> to float the button on the right. See the ", 'myCarousel').' <a href="http://getbootstrap.com/css/#buttons-options" target="_blank">Bootstrap documentation</a>.</p>';
    }
    public function link_button_before_callback() {
            printf('<input type="text" id="link_button_before" name="my_settings[link_button_before]" value="%s" size="20" />',
                    isset( $this->options['link_button_before'] ) ? esc_attr( $this->options['link_button_before']) : '');
    }
    public function link_button_after_callback() {
            printf('<input type="text" id="link_button_after" name="my_settings[link_button_after]" value="%s" size="20" />',
                    isset( $this->options['link_button_after'] ) ? esc_attr( $this->options['link_button_after']) : '');
    }

    // Markup section
    public function before_title_callback() {
            printf('<input type="text" id="before_title" name="my_settings[before_title]" value="%s" size="15" />',
                    isset( $this->options['before_title'] ) ? esc_attr( $this->options['before_title']) : '<h4>');
    }
    public function customnext_callback() {
            printf('<input type="text" id="customnext" name="my_settings[customnext]" value="%s" size="15" />',
                    isset( $this->options['customnext'] ) ? esc_attr( $this->options['customnext']) : '');
    }
    public function customprev_callback() {
            printf('<input type="text" id="customprev" name="my_settings[customprev]" value="%s" size="15" />',
                    isset( $this->options['customprev'] ) ? esc_attr( $this->options['customprev']) : '');
    }
    public function after_title_callback() {
            printf('<input type="text" id="after_title" name="my_settings[after_title]" value="%s" size="15" />',
                    isset( $this->options['after_title'] ) ? esc_attr( $this->options['after_title']) : '</h4>');
    }
    public function before_caption_callback() {
            printf('<input type="text" id="before_caption" name="my_settings[before_caption]" value="%s" size="15" />',
                    isset( $this->options['before_caption'] ) ? esc_attr( $this->options['before_caption']) : '<p>');
    }
    public function after_caption_callback() {
            printf('<input type="text" id="after_caption" name="my_settings[after_caption]" value="%s" size="15" />',
                    isset( $this->options['after_caption'] ) ? esc_attr( $this->options['after_caption']) : '</p>');
    }
    public function before_caption_div_callback() {
            printf('<input type="text" id="before_caption_div" name="my_settings[before_caption_div]" value="%s" size="15" />',
                    isset( $this->options['before_caption_div'] ) ? esc_attr( $this->options['before_caption_div']) : '');
    }
    public function after_caption_div_callback() {
            printf('<input type="text" id="after_caption_div" name="my_settings[after_caption_div]" value="%s" size="15" />',
                    isset( $this->options['after_caption_div'] ) ? esc_attr( $this->options['after_caption_div']) : '');
    }

}

if( is_admin() ){
        $my_settings_page = new my_settings_page();
}

// Add settings link on plugin page
function my_settings_link ($links) {
    $settings_link = '<a href="edit.php?post_type=my&page=myCarousel">'.__('Settings', 'myCarousel').'</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$my_plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$my_plugin", 'my_settings_link' );
