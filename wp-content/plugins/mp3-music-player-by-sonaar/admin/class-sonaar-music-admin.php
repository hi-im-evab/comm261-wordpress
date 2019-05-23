<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       sonaar.io
* @since      1.0.0
*
* @package    Sonaar_Music
* @subpackage Sonaar_Music/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Sonaar_Music
* @subpackage Sonaar_Music/admin
* @author     Edouard Duplessis <eduplessis@gmail.com>
*/
class Sonaar_Music_Admin {
    
    /**
    * The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_name    The ID of this plugin.
    */
    private $plugin_name;
    
    /**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
    private $version;
    
    /**
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_name       The name of this plugin.
    * @param      string    $version    The version of this plugin.
    */
    public function __construct( $plugin_name, $version ) {
        
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
        
    }
    
    /**
    * Load the required dependencies for the admin area.
    *
    * Include the following files that make up the plugin:
    *
    * @since		1.0.0
    */
    
    public function load_dependencies(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2/init.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-calltoaction/cmb2-calltoaction.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-conditionals/cmb2-conditionals.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-attached-posts/cmb2-attached-posts-field.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-store-list/song-store-field-type.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-typography/typography-field-type.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/cmb2-multiselect/cmb2-multiselect.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-music-widget.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/Shortcode_Button/shortcode-button.php';
        
    }
    /**
    * Register the stylesheets for the admin area.
    *
    * @since    1.0.0
    */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sonaar-music-admin.css', array(), $this->version, 'all' );
    }
    
    /**
    * Register the JavaScript for the admin area.
    *
    * @since    1.0.0
    */
    public function enqueue_scripts( $hook ) {
        if ($hook == 'album_page_iron_music_player' || $hook == 'album_page_sonaar_music_promo') {
            wp_enqueue_script( 'vuejs', "//cdn.jsdelivr.net/npm/vue/dist/vue.js" , array(), $this->version, false );
            wp_enqueue_script( 'polyfill', "//unpkg.com/babel-polyfill@latest/dist/polyfill.min.js" , array(), $this->version, false );
            wp_enqueue_script( 'bootstrap-vue', "//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js" , array(), $this->version, false );
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sonaar-music-admin.js', array( 'jquery','vuejs','polyfill','bootstrap-vue' ), $this->version, true );
        }
        
    }
    
    /**
    * Registering the option page
    *
    * @since 	1.0.0
    **/
    public function init_options() {
        
        
        $cmb_options = new_cmb2_box( array(
        
        'id'           		=> 'sonaar_music_network_option_metabox',
        'title'        		=> esc_html__( 'Sonaar Music', 'sonaar' ),
        'object_types' 		=> array( 'options-page' ),
        'option_key'      => 'iron_music_player', // The option key and admin menu page slug.
        'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'      => esc_html__( 'Settings', 'sonaar' ), // Falls back to 'title' (above).
        'parent_slug'     => 'edit.php?post_type=album', // Make options page a submenu item of the themes menu.
        'capability'      => 'manage_options', // Cap required to view options-page.
        'position' 				=> 1,
        ) );
        
        $cmb_options->add_field(
        array(
        'name' => esc_html__('Music Player Color Setting', 'sonaar'),
        'type' => 'title',
        'id'   => 'banner_settings',
        'render_row_cb' => 'banner_row',
        ) );
        
        
        /**
        * Manually render a field.
        *
        * @param  array      $field_args Array of field arguments.
        * @param  CMB2_Field $field      The field object
        */
        function banner_row( $field_args, $field ) {
            require_once plugin_dir_path( __FILE__ ) . 'partials/sonaar-music-admin-display.php';
        }
        
        
        $cmb_options->add_field(
        array(
        'name' => esc_html__('Music Player Color Setting', 'sonaar'),
        'type' => 'title',
        'id'   => 'music_player_title'
        ));
        $cmb_options->add_field(
        array(
        'id' => 'music_player_playlist',
        'type' => 'typography',
        'name' => esc_html__('Playlist', 'sonaar'),
        'description' => esc_html__('Choose a font, font size and color', 'sonaar'),
        'fields' => array(
        'font-weight' 		=> false,
        'background' 			=> false,
        'text-align' 			=> false,
        'text-transform' 	=> false,
        'line-height' 		=> false,
        )
        ));
        
        $cmb_options->add_field(
        array(
        'id' => 'music_player_album_title',
        'type' => 'typography',
        'name' => esc_html__('Album Title', 'sonaar'),
        'description' => esc_html__('Choose a font, font size and color', 'sonaar'),
        'fields' => array(
        'font-weight' 		=> false,
        'background' 			=> false,
        'text-align' 			=> false,
        'text-transform' 	=> false,
        'line-height' 		=> false,
        )
        ));
        $cmb_options->add_field(
        array(
        'id' => 'music_player_date',
        'type' => 'typography',
        'name' => esc_html__('Release date', 'sonaar'),
        'description' => esc_html__('Choose a font, font size and color', 'sonaar'),
        'fields' => array(
        'font-weight' 		=> false,
        'background' 			=> false,
        'text-align' 			=> false,
        'text-transform' 	=> false,
        'line-height' 		=> false,
        )
        ));
        
        
        $music_player_coverSize = array();
        $imageSizes = get_intermediate_image_sizes();
        foreach ($imageSizes as $value) {
            $music_player_coverSize[$value] = $value;
        }
        $cmb_options->add_field(
        array(
        'id' => 'music_player_coverSize',
        'type' => 'select',
        'name' => esc_html('Album cover size image source', 'sonaar'),
        'show_option_none' => false,
        'default' => 'custom',
        'options' => $music_player_coverSize,
        )
        );
        $cmb_options->add_field(
        array(
        'id' => 'music_player_featured_color',
        'type' => 'colorpicker',
        'name' => esc_html__('Button Colors', 'sonaar'),
        'class' => 'color',
        'default' => 'rgba(0, 0, 0, 1)',
        'options' => array(
        'alpha' => true, // Make this a rgba color picker.
        ),
        ));
        $cmb_options->add_field(
        array(
        'id' => 'music_player_store_drawer',
        'type' => 'colorpicker',
        'name' => esc_html__('Track Store Drawer Colors', 'sonaar'),
        'class' => 'color',
        'default' => '#BBBBBB',
        'options' => array(
        'alpha' => true, // Make this a rgba color picker.
        ),
        ));
        $cmb_options->add_field(
        array(
        'id' => 'music_player_icon_color',
        'type' => 'colorpicker',
        'name' => esc_html__('Player Control', 'sonaar'),
        'class' => 'color',
        'default' => 'rgba(127, 127, 127, 1)',
        'options' => array(
        'alpha' => true, // Make this a rgba color picker.
        ),
        ));
        $cmb_options->add_field(
        array(
        'id' => 'music_player_timeline_color',
        'type' => 'colorpicker',
        'name' => esc_html__('SoundWave Background', 'sonaar'),
        'class' => 'color',
        'default' => 'rgba(31, 31, 31, 1)',
        'options' => array(
        'alpha' => true, // Make this a rgba color picker.
        ),
        ));
        $cmb_options->add_field(
        array(
        'id' => 'music_player_progress_color',
        'type' => 'colorpicker',
        'name' => esc_html__('SoundWave Progress Bar', 'sonaar'),
        'class' => 'color',
        'default' => 'rgba(13, 237, 180, 1)',
        'options' => array(
        'alpha' => true, // Make this a rgba color picker.
        ),
        ));
        
        $cmb_promo = new_cmb2_box( array(
        
        'id'           		=> 'sonaar_music_promo',
        'title'        		=> esc_html__( 'Premium', 'sonaar' ),
        'object_types' 		=> array( 'options-page' ),
        'option_key'      => 'sonaar_music_promo', // The option key and admin menu page slug.
        'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'      => esc_html__( 'Premium', 'sonaar' ), // Falls back to 'title' (above).
        'parent_slug'     => 'edit.php?post_type=album', // Make options page a submenu item of the themes menu.
        'capability'      => 'manage_options', // Cap required to view options-page.
        'enqueue_js' 			=> false,
        'cmb_styles' 			=> false,
        'display_cb'			=> 'sonaar_music_promo_display',
        'position' 				=> 9999,
        ) );
        
        
        
        function sonaar_music_promo_display(){
            require_once plugin_dir_path( __FILE__ ) . 'partials/sonaar-music-promo-display.php';
        }
        ;
        
        
    }
    
    
    
    /**
    * Register post fields
    **/
    public function init_postField(){
        
        
        $cmb_album = new_cmb2_box( array(
        'id'           		=> 'acf_albums_infos',
        'title'        		=> esc_html__( 'Album infos', 'sonaar' ),
        'object_types' 		=> array( 'album' ),
        'context'       => 'normal',
        'priority'      => 'low',
        'show_names'    => true,
        'capability'      => 'manage_options', // Cap required to view options-page.
        ) );
        
        $cmb_album->add_field(
        array(
        'id' => 'alb_release_date',
        'name'	=> __('Release Date', 'sonaar'),
        'type' => 'text'
        ));
        
        $cmb_album->add_field( array(
        'id'          => 'alb_store_list',
        'type'        => 'store_list',
        'name' 				=> __('Where can we buy this album?','sonaar'),
        'repeatable'  => true,
        'text' => array(
        'add_row_text' => 'Add store',
        ),
        'icon'=> true
        ));
        
        $tracklist = $cmb_album->add_field( array(
        'id'          => 'alb_tracklist',
        'type'        => 'group',
        'name' 				=> __('Tracklists','sonaar'),
        'repeatable'  => true, // use false if you want non-repeatable group
        'options'     => array(
        'group_title'   => __( 'Track {#}', 'sonaar' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another track', 'sonaar' ),
        'remove_button' => __( 'Remove track', 'sonaar' ),
        'sortable'      => false, // beta
        'closed'     => false, // true to have the groups closed by default
        ),
        ) );
        
        $cmb_album->add_group_field( $tracklist ,array(
        'name'             => esc_html__('Source File', 'sonaar'),
        'description' 		 => 'Please select which type of audio source you want for this track',
            'id'               => 'FileOrStream',
        'type'             => 'radio',
        'show_option_none' => false,
        'options'          => array(
        'mp3' => 'Local MP3',
        'stream' => 'External MP3'
        ),
        'default' => 'mp3'
        ));
        
        $cmb_album->add_group_field($tracklist, array(
        'id' => 'track_mp3',
        'name' => __('MP3 File','sonaar'),
        'type' => 'file',
        'description' => __('Only .MP3 file accepted','sonaar'),
        'query_args' => array(
        'type' => 'audio/mpeg',
        ),
        'attributes' => array(
        'required'               => true, // Will be required only if visible.
        'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
        'data-conditional-value' => 'mp3',
        )
        
        ));
        
        $cmb_album->add_group_field($tracklist, array(
        'id' => 'stream_link',
        'name' => __('External Audio link','sonaar'),
        'type' => 'text',
        'description' => __('Add link to your external MP3 file','sonaar'),
        'attributes' => array(
        'required'               => true, // Will be required only if visible.
        'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
        'data-conditional-value' => 'stream',
        )
        
        ));
        $cmb_album->add_group_field($tracklist, array(
        'id' => 'stream_title',
        'name' => __('Track title','sonaar'),
        'type' => 'text',
        'attributes' => array(
        'required'               => true, // Will be required only if visible.
        'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
        'data-conditional-value' => 'stream',
        )
        
        ));
        $cmb_album->add_group_field($tracklist,
        array(
        'id' => 'stream_artist',
        'name' => __('Track Artist(s)','sonaar'),
        'description' => __("Leave blank if it's the same as the playlist",'sonaar'),
            'type' => 'text',
        'attributes' => array(
        'required'               => false, // Will be required only if visible.
        'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
        'data-conditional-value' => 'stream',
        )
        ));
        $cmb_album->add_group_field($tracklist, array(
        'id' => 'stream_album',
        'name' => __('Track Album','sonaar'),
        'description' => __("Leave blank if it's the same as the playlist",'sonaar'),
            'type' => 'text',
        'attributes' => array(
        'required'               => false, // Will be required only if visible.
        'data-conditional-id'    => wp_json_encode( array( $tracklist, 'FileOrStream' )),
        'data-conditional-value' => 'stream',
        )
        ));
        
        $cmb_album->add_group_field( $tracklist, array(
        'id'          => 'song_store_list',
        'type'        => 'store_list',
        'name' 				=> __('Where to buy/download this track?','sonaar'),
        'repeatable'  => true,
        'options'=> array(
        'sortable'      => true, // beta
        ),
        'text' => array(
        'add_row_text' => 'Add store',
        'store_icon_text' => '',
        'store_name_desc' => __('Examples : iTunes, Bandcamp, Soundcloud, etc.', 'sonaar'),
        'store_link_desc' => __('Link to the online store', 'sonaar'),
        
        ),
        'icon'=> true
        ));
        
        $cmb_album_promo = new_cmb2_box( array(
        'id'           		=> 'sonaar_promo',
        'title'        		=> esc_html__( 'Looking for stats?', 'sonaar' ),
            'object_types' 		=> array( 'album' ),
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'	 => false,
        'capability'      => 'manage_options', // Cap required to view options-page.
        ) );
        
        $cmb_album_promo->add_field(
        array(
        'id' => 'calltoaction',
        'name'	=> __('sonaar pro', 'sonaar'),
        'type' => 'calltoaction',
        /*'txt'  => 'Lenna',*/
        'href' => 'https://goo.gl/mVUJEJ',
        'img' => 'https://assets.sonaar.io/marketing/sonaar-music-pro/sonaar-music-pro-banner-cpt.jpg'
        ));
        
    }
    
    /**
    * Create custom posttype
    **/
    public function create_postType(){
        
        $album_args = array(
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'has_archive'         => true,
        'query_var'           => true,
        'exclude_from_search' => false,
        );
        
        $album_args['labels'] = array(
        'name'               => esc_html__('Playlists', 'sonaar'),
        'singular_name'      => esc_html__('Playlist', 'sonaar'),
        'name_admin_bar'     => esc_html_x('Playlist', 'add new on admin bar', 'sonaar'),
        'menu_name'          => esc_html__('Playlist', 'sonaar'),
        'all_items'          => esc_html__('All Playlists', 'sonaar'),
        'add_new'            => esc_html__('Add New', 'playlist', 'sonaar'),
        'add_new_item'       => esc_html__('Add New Playlist', 'sonaar'),
        'edit_item'          => esc_html__('Edit Playlist', 'sonaar'),
        'new_item'           => esc_html__('New Playlist', 'sonaar'),
        'view_item'          => esc_html__('View playlist', 'sonaar'),
        'search_items'       => esc_html__('Search Playlists', 'sonaar'),
        'not_found'          => esc_html__('No playlists found.', 'sonaar'),
        'not_found_in_trash' => esc_html__('No playlists found in the Trash.', 'sonaar'),
        'parent'             => esc_html__('Parent Playlist:', 'sonaar')
        );
        
        $album_args['supports'] = array('title','editor','thumbnail','revisions');
        // $slugMusic = ( get_ironMusic_option( 'discography_slug_name', '_iron_music_discography_options' ) )? get_ironMusic_option( 'discography_slug_name', '_iron_music_discography_options' ): 'playlist';
        // $album_args['rewrite'] = array( 'slug'=> 'Discography' );
        
        $album_args['menu_icon'] = 'dashicons-format-audio';
        
        register_post_type( 'album' , $album_args);
        
    }
    
    public function register_widget(){
        register_widget( 'Sonaar_Music_Widget' );
    }
    
    
    
    
    
    public function print_playlist_json() {
        $jsonData = array();
        
        $title = !empty($_GET["title"]) ? $_GET["title"] : null;
        $albums = !empty($_GET["albums"]) ? $_GET["albums"] : array();
        
        $playlist = $this->get_playlist($albums, $title);
        
        if(!is_array($playlist) || empty($playlist['tracks']))
        return;
        
        wp_send_json($playlist);
        
    }
    
    
    
    private function get_playlist($album_ids = array(), $title = null) {
        global $post;
        
        $playlist = array();
        if(!is_array($album_ids)) {
            $album_ids = explode(",", $album_ids);
        }
        
        
        $albums = get_posts(
        array(
        'post_type' => 'album',
        'post__in' => $album_ids
        ));
        
        $tracks = array();
        foreach($albums as $a) {
            
            $album_tracks =  get_post_meta( $a->ID, 'alb_tracklist' );
            
            for($i = 0 ; $i < count($album_tracks) ; $i++) {
                
                $fileOrStream =  $album_tracks[$i]['FileOrStream'];
                $thumb_id = get_post_thumbnail_id($a->ID);
                $thumb_url = wp_get_attachment_image_src($thumb_id, 'medium', true);
                
                $track_title = false;
                $album_title = false;
                $album_artist = false;
                $mp3_id = false;
                $audioSrc = '';
                $song_store_list = $album_tracks[$i]["song_store_list"];
                $showLoading = false;
                $album_tracks_lenght = false;
                
                switch ($fileOrStream) {
                    case 'mp3':
                        
                        if ( isset( $album_tracks[$i]["track_mp3"] ) ) {
                            $mp3_id = $album_tracks[$i]["track_mp3"]["id"];
                            $mp3_metadata = wp_get_attachment_metadata( $mp3_id );
                            $track_title = ( isset( $album_tracks[$i]["track_mp3"]["title"] ) && $album_tracks[$i]["track_mp3"]["title"] !== '' )? $album_tracks[$i]["track_mp3"]["title"] : false ;
                            $album_title = ( isset( $mp3_metadata['album'] ) && $mp3_metadata['album'] !== '' )? $mp3_metadata['album'] : false;
                            $album_artist = ( isset( $mp3_metadata['artist'] ) && $mp3_metadata['artist'] !== '' )? $mp3_metadata['artist'] : false;
                            $album_tracks_lenght = ( isset( $mp3_metadata['length_formatted'] ) && $mp3_metadata['length_formatted'] !== '' )? $mp3_metadata['length_formatted'] : false;
                            $audioSrc = wp_get_attachment_url($mp3_id);
                            $showLoading = true;
                    }
                    
                    break;
                case 'stream':
                    
                    $audioSrc = ( $album_tracks[$i]["stream_link"] !== '' )? $album_tracks[$i]["stream_link"] : false;
                    $track_title = ( $album_tracks[$i]["stream_title"] !== '' )? $album_tracks[$i]["stream_title"] : false;
                    $album_artist = ( $album_tracks[$i]["stream_artist"] !== '' )? $album_tracks[$i]["stream_artist"] : false;
                    $album_title = ( $album_tracks[$i]["stream_album"] !== '' )? $album_tracks[$i]["stream_album"] : false;
                    break;
                
                default:
                    $album_tracks[$i] = array();
                    break;
        }
        
        // if ( $mp3_metadata ) {
        // 	$album_tracks[$i]["mp3_metadata"] = $mp3_metadata;
        
        // }
        $album_tracks[$i] = array();
        $album_tracks[$i]["id"] = ( $mp3_id )? $mp3_id : $i ;
        $album_tracks[$i]["mp3"] = $audioSrc;
        $album_tracks[$i]["loading"] = $showLoading;
        $album_tracks[$i]["track_title"] = ( $track_title )? $track_title : "Track title ". $i;
        $album_tracks[$i]["lenght"] = $album_tracks_lenght;
        $album_tracks[$i]["album_title"] = ( $album_title )? $album_title : $a->post_title;
        $album_tracks[$i]["album_artist"] = ( $album_artist )? $album_artist : get_artists($a->ID);
        $album_tracks[$i]["poster"] = $thumb_url[0];
        $album_tracks[$i]["release_date"] = get_field('alb_release_date', $a->ID);
        $album_tracks[$i]["song_store_list"] = $song_store_list;
    }
    $tracks = array_merge($tracks, $album_tracks);
    
}

$playlist['playlist_name'] = $title;
if ( empty($playlist['playlist_name']) ) $playlist['playlist_name'] = "";

$playlist['tracks'] = $tracks;
if ( empty($playlist['tracks']) ) $playlist['tracks'] = array();

return $playlist;
}


public function add_shortcode(){
    
    function sonaar_shortcode_audioplayer( $atts ) {
        
        extract( shortcode_atts( array(
        'title' => '',
        'albums' => '',
        'show_playlist' => '',
        'show_album_market' => '',
        'show_track_market' => '',
        'remove_player' => '',
        ), $atts ) );
        
        ob_start();
        
        
        the_widget('Sonaar_Music_Widget', $atts, array('widget_id'=>'arbitrary-instance-'.uniqid(), 'before_widget'=>'<article class="widget '.$isuppercase.' iron_widget_radio '. (( function_exists('getCSSAnimation') )? getCSSAnimation( $css_animation ):'') .'">', 'after_widget'=>'</article>'));
            $output = ob_get_contents();
            ob_end_clean();
            
            return $output;
    }
    add_shortcode( 'sonaar_audioplayer', 'sonaar_shortcode_audioplayer' );
    
    
}

public function init_my_shortcode_button() {
    
    $button_slug = 'sonaar_audioplayer';
    
    $js_button_data = array(
    'qt_button_text' => __( 'Sonaar Music AudioPlayer', 'shortcode-button' ),
    'button_tooltip' => __( 'Sonaar Music AudioPlayer', 'shortcode-button' ),
    'icon'           => 'dashicons-format-audio',
    
    'author'         => 'Edouard Duplessis',
    'authorurl'      => 'https://sonaar.io',
    'infourl'        => 'https://sonaar.io',
    'version'        => '1.0.0',
    'include_close'  => true, // Will wrap your selection in the shortcode
    'mceView'        => false, // Live preview of shortcode in editor. YMMV.
    'l10ncancel'     => __( 'Cancel', 'sonaar' ),
    'l10ninsert'     => __( 'Insert AudioPlayer', 'sonaar' ),
    
    );
    
    $all_albums = get_posts(array(
    'post_type' => 'album'
    , 'posts_per_page' => -1
    , 'no_found_rows'  => true
    ));
    $album_options = array();
    foreach ($all_albums as $album ) {
        $album_options[$album->ID] = $album->post_title;
    }
    
    
    
    $additional_args = array(
    // Can be a callback or metabox config array
    'cmb_metabox_config'   => array(
    'id'     => 'shortcode_'. $button_slug,
    'fields' => array(
    array(
    'name'    => __( 'Title of your Playlist', 'sonaar' ),
    'id'      => 'title',
    'type'    => 'text',
    ),
    array(
    'name'    => __( 'Albums', 'sonaar' ),
    'id'      => 'albums',
    'type'    => 'select_multiple',
    'show_option_none' => false,
    'options'          => $album_options,
    ),
    array(
    'name'    => __( 'Show Playlist', 'sonaar' ),
    'id'      => 'show_playlist',
    'type'    => 'checkbox',
    ),
    array(
    'name'    => __( 'Show track store', 'sonaar' ),
    'id'      => 'show_track_market',
    'type'    => 'checkbox',
    ),
    array(
    'name'    => __( 'Show album store', 'sonaar' ),
    'id'      => 'show_album_market',
    'type'    => 'checkbox',
    ),
    array(
    'name'    => __( 'Remove Visual Player', 'sonaar' ),
    'id'      => 'remove_player',
    'type'    => 'checkbox',
    ),
    ),
    'show_on' => array( 'key' => 'options-page', 'value' => $button_slug ),
    ),
    // Set the conditions of the shortcode buttons
    'conditional_callback' => 'shortcode_button_only_pages',
    );
    
    
    
    $button = new Shortcode_Button( $button_slug, $js_button_data, $additional_args );
}


/**
* Callback dictates that shortcode button will only display if we're on a 'page' edit screen
*
* @return bool Expects a boolean value
*/
function shortcode_button_only_pages() {
    if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
        return false;
    }
    
    $current_screen = get_current_screen();
    
    if ( ! isset( $current_screen->parent_base ) || $current_screen->parent_base != 'edit' ) {
        return false;
    }
    
    if ( ! isset( $current_screen->post_type ) || $current_screen->post_type != 'page' ) {
        return false;
    }
    
    // Ok, guess we're on a 'page' edit screen
    return true;
}



public function manage_album_columns ($columns){
    $iron_cols = array(
    'alb_tracklist'    => esc_html__('# Tracks', 'sonaar'),
    'alb_store_list'   => esc_html__('# Stores', 'sonaar'),
    'alb_shortcode'   => esc_html__('', 'sonaar')
    );
    
    $columns = Sonaar_Music::array_insert($columns, $iron_cols, 'date', 'before');
    
    $iron_cols = array('alb_icon' => '');
    
    $columns = Sonaar_Music::array_insert($columns, $iron_cols, 'title', 'before');
    
    $columns['date'] = esc_html__('Published', 'sonaar');   // Renamed date column
    
    return $columns;
}


public function manage_album_custom_column ($column, $post_id){
    switch ($column){
        
        case 'alb_tracklist':
            if ( $list = get_post_meta($post_id, 'alb_tracklist', true) )
                echo count($list);
            else
                echo esc_html__('N/A', 'sonaar');
            break;
        
        case 'alb_store_list':
            if ( $list = get_post_meta($post_id, 'alb_store_list', true) )
                echo count($list);
            else
                echo esc_html__('N/A', 'sonaar');
            break;
        
        case 'alb_shortcode':
            add_thickbox();
            echo '<div id="my-content-' . $post_id . '" style="display:none;">
            <h1>Playlist Shorcode</h1>
            <p>Here you can copy and paste the following shortcode anywhere your page</p>
            <textarea name="" id="" style="width:100%; height:150px;"> [sonaar_audioplayer title="' . get_the_title( $post_id ) . '" albums="' . $post_id . '" show_playlist="true" show_track_market="true" show_album_market="true" remove_player="true"][/sonaar_audioplayer]</textarea>
            </div>';
            echo '<a href="#TB_inline?width=600&height=300&inlineId=my-content-' . $post_id . '" class="thickbox"><span class="dashicons dashicons-format-audio"></span></a>';
            break;
        case 'alb_icon':
            $att_title = _draft_or_post_title();
            
            echo '<a href="' . esc_url(get_edit_post_link( $post_id, true )) . '" title="' . esc_attr( sprintf( esc_html__('Edit &#8220;%s&#8221;', 'sonaar'), $att_title ) ) . '">';
            
            if ( $thumb = get_the_post_thumbnail( $post_id, array(64, 64) ) ){
                echo $thumb;
        }else{
            echo '<img width="46" height="60" src="' . wp_mime_type_icon('image/jpeg') . '" alt="">';
        }
        
        echo '</a>';
        
        break;
}
}



public function adminfooter(){
    echo '<div id="sonaar-music-footer"><p>Please rate <a href="https://wordpress.org/support/plugin/mp3-music-player-by-sonaar/reviews/#new-post" target="_blank">Sonaar Music ★★★★★</a> on WordPress.org to help us spread the word.</p>
    <p>Thank you from the Sonaar Music team!</p></div>';
}


public function checkAlbumVersion(){
    $albums = get_posts( array(
			'post_type' => 'album',
			'post_status' => 'publish',
			'posts_per_page' => -1

		));
		foreach ( $albums as $album ) {
			$oldVersion = ( get_post_meta($album->ID,'_alb_tracklist', true) !== '');

			if ( $oldVersion ) {
                $meta = get_post_meta( $album->ID );
                $newList = array();

                for ($i=0; $i < $meta['alb_tracklist'][0] ; $i++) { 
                    
                    $newStructure = array(
                        'FileOrStream' =>  $meta['alb_tracklist_'. $i .'_FileOrStream'][0],
                        'track_mp3_id' =>  $meta['alb_tracklist_0_track_mp3'][0],
                        'track_mp3' =>  $meta['alb_tracklist_'. $i .'_track_mp3'][0],
                        'stream_link' =>  $meta['alb_tracklist_'. $i .'_stream_link'][0],
                        'stream_title' =>  $meta['alb_tracklist_'. $i .'_stream_title'][0],
                        'stream_artist' =>  $meta['alb_tracklist_'. $i .'_stream_artist'][0],
                        'stream_album' =>  $meta['alb_tracklist_'. $i .'_stream_album'][0],
                        'song_store_list' => array()
                    );

                    for ($a=0; $a < $meta['alb_tracklist_' . $i . '_song_store_list'][0] ; $a++) {
                        $newStructure['song_store_list'][$a] = array(
                            'store-icon'=> 'fab ' . $meta['alb_tracklist_' . $i . '_song_store_list_' . $a . '_song_store_icon'][0],
                            'store-name'=> $meta['alb_tracklist_' . $i . '_song_store_list_' . $a . '_song_store_name'][0],
                            'store-link'=> $meta['alb_tracklist_' . $i . '_song_store_list_' . $a . '_store_link'][0]
                        );
                    }
                    $newList[$i] = $newStructure; 
                }
                    
                delete_post_meta( $album->ID, '_alb_tracklist' );
                update_post_meta( $album->ID, 'alb_tracklist', $newList );

            }
        }
}

}