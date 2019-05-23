<?php
/**
* Radio Widget Class
*
* @since 1.6.0
* @todo  - Add options
*/

class Sonaar_Music_Widget extends WP_Widget{
    /**
    * Widget Defaults
    */
    
    public static $widget_defaults;
    
    
    private function action_link ( $object_id = null, $ext_link = null, $title= '' ) {
        if ( $object_id || $ext_link ){
            $url = !empty($ext_link) ? $ext_link : get_permalink($object_id);
            $target = !empty($ext_link) ? "_blank" : "_self";
            
            if(!empty($url))
            return '<a target="'.$target.'" href="' . esc_url($url) . '" class="panel-action panel-action__label">' . $title . '</a>';
        }
        
        return '';
    }
    
    
    
    /**
    * Render HTML output
    */
    
    
    public static function get_object_options($selected = null, $post_type = 'page') {
        
        $posts = get_posts(array('post_type' => $post_type, 'posts_per_page' => -1, 'suppress_filters' => false));
        $options = '';
        
        $options .= '<option></option>';
        foreach($posts as $p) {
            
            $options .= '<option value="'.$p->ID.'"'.(!is_array($selected) && ($p->ID == $selected) || is_array($selected) && in_array($p->ID, $selected) ? ' selected="selected"' : '').'>'.esc_attr($p->post_title).'</option>';
            
        }
        
        return $options;
    }
    
    public static function get_taxonomy_options($selected = null, $taxonomy = 'category') {
        
        $terms = get_terms($taxonomy);
        $options = '';
        
        $options .= '<option></option>';
        foreach($terms as $t) {
            
            $options .= '<option value="'.$t->term_id.'"'.(!is_array($selected) && ($t->term_id == $selected) || is_array($selected) && in_array($t->term_id, $selected) ? ' selected="selected"' : '').'>'.esc_attr($t->name).'</option>';
            
        }
        
        return $options;
    }
    
    
    
    /**
    * Register widget with WordPress.
    */
    
    function __construct (){
        
        
        $widget_ops = array(
        'classname'   => 'sonaar_music_widget',
        'description' => esc_html_x('A simple radio that plays a list of songs from selected albums.', 'Widget', 'sonaar')
        );
        
        self::$widget_defaults = array(
            'title'        => '',
            'albums'     	 => array(),
            'show_playlist' => 0,
            'show_album_market' => 0,
            'show_track_market' => 0,
            'remove_player' => 0,
            
            
            );
            
            if ( isset($_GET['load']) && $_GET['load'] == 'playlist.json' ) {
                $this->print_playlist_json();
        }
        
        parent::__construct('sonaar-music', esc_html_x('Sonaar: Music Player', 'Widget', 'sonaar'), $widget_ops);
        
    }
    
    private function get_market($album_id = 0){
        if( $album_id == 0 )
        return;
        
        $firstAlbum = explode(',', $album_id);
        $firstAlbum = $firstAlbum[0];
        
        $storeList = get_post_meta($firstAlbum, 'alb_store_list', true);
        
        if ( $storeList ){
            $output = '<div class="buttons-block"><div class="ctnButton-block">
            <div class="available-now">' . esc_html__("Available now on", 'sonaar') . ':</div>
            <ul class="store-list">';
            foreach ($storeList as $store ) {
                $icon = ( $store['store-icon'] )? '<i class="' . $store['store-icon'] . '"></i>': '';
                $output .= '<li><a class="button" href="' . esc_url( $store['store-link'] ) . '" target="_blank">'. $icon . $store['store-name'] . '</a></li>';
            }
            $output .= '</ul></div></div>';
        }
        
        return $output;
    }
    
    /**
    * Front-end display of widget.
    */
    public function widget ( $args, $instance ){
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
            $args['before_title'] = "<span class='heading-t3'></span>".$args['before_title'];
            $args['before_title'] = str_replace('h2','h3',$args['before_title']);
            $args['after_title'] = str_replace('h2','h3',$args['after_title']);
            /*$args['after_title'] = $args['after_title']."<span class='heading-b3'></span>";*/
            
            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
            $albums = $instance['albums'];
            $show_album_market = (bool) ( isset( $instance['show_album_market'] ) )? $instance['show_album_market']: 0;
            $show_track_market = (bool) ( isset( $instance['show_track_market'] ) )? $instance['show_track_market']: 0;
            $remove_player = (bool) ( isset( $instance['remove_player'] ) )? $instance['remove_player']: false;
            $remove_player = ( $remove_player )? 'style="display: none;"': '' ;
            
            $show_playlist = (bool)$instance['show_playlist'];
            $store_buttons = array();
            /***/
            
            $action = $this->action_link( $action_obj_id, $action_ext_link, $action_title);
            
            $playlist = $this->get_playlist($albums, $title);
            if ( isset($playlist['tracks']) && ! empty($playlist['tracks']) )
                $player_message = esc_html_x('Loading tracks...', 'Widget', 'sonaar');
            else
                $player_message = esc_html_x('No tracks founds...', 'Widget', 'sonaar');
            
            /***/
            
            if ( ! $playlist )
                return;
            
            if($show_playlist) {
                $args['before_widget'] = str_replace("iron_widget_radio", "iron_widget_radio playlist_enabled", $args['before_widget']);
        }
        
        echo $args['before_widget'];
        
        if ( ! empty( $title ) )
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        
        
        if(is_array($albums)) {
            $albums = implode(',', $albums);
        }
        
        $firstAlbum = explode(',', $albums);
        $firstAlbum = $firstAlbum[0];
        
        $classShowPlaylist = '';
        $colShowPlaylist = '';
        $colShowPlaylist2 = '';
        if($show_playlist) {
            $classShowPlaylist = 'show-playlist';
            $colShowPlaylist = 'vc_col-md-6';
            $colShowPlaylist2 = 'vc_col-md-6';
            
            
        }
        
        $show_market = ( $show_album_market )? $albums : 0 ;
        
        $format_playlist ='';
        foreach( $playlist['tracks'] as $track){
            $trackUrl = $track['mp3'] ;
            $showLoading = $track['loading'] ;
            
            
            
            $song_store_list = '<span class="store-list">';
            if ( $show_track_market && is_array($track['song_store_list'] ) ){
                
                
                
                $song_store_list .= '<div class="song-store-list-menu"><i class="fas fa-ellipsis-v"></i><div class="song-store-list-container">';
                
                foreach( $track['song_store_list'] as $store ){
                    $song_store_list .= '<a href="' . $store['store-link'] . '" class="song-store" target="_blank" title="' . $store['store-name'] . '"><i class="' . $store['store-icon'] . '"></i></a>';
                }
                $song_store_list .= '</div></div>';
            }
            $song_store_list .= '</span>';
            
            $store_buttons = ( !empty($track["track_store"]) )? '<a class="button" target="_blank" href="'. esc_url( $track['track_store'] ) .'">'. esc_textarea( $track['track_buy_label'] ).'</a>' : '' ;
            $format_playlist .= '<li
            data-audiopath="' . esc_url( $trackUrl ) . '"
            data-showloading="' . $showLoading .'"
            data-albumTitle="' . esc_attr( $track['album_title'] ) . '"
            data-albumArt="' . esc_url( $track['poster'] ) . '"
            data-trackartists="' . esc_attr( $track['album_artist'] ) . '"
            data-releasedate="' . esc_attr( $track['release_date'] ) . '"
            data-trackTitle="' . $track['track_title'] . '"
            data-trackID="' . $track['id'] . '"
            data-trackTime="' . $track['lenght'] . '"
            >' . $song_store_list . '</li>';
        }

        echo
        '<div class="iron-audioplayer wpb_column vc_column_container ' . $classShowPlaylist . '" id="'. esc_attr( $args["widget_id"] ) .'-' . bin2hex(random_bytes(5)) . '" data-url-playlist="' . esc_url(home_url('?load=playlist.json&amp;title='.$title.'&amp;albums='.$albums.'')) . '" >
        <div class="album">
        <div class="album-art">
        <img alt="album-art">
        </div>
        </div>
        <div class="playlist">
        <h3 class="sr_it-playlist-title">'. get_the_title($firstAlbum) .'</h3><div class="sr_it-playlist-artists">'. esc_html__('By', 'sonaar'). ' <span class="sr_it-artists-value"></span></div>
        <div class="sr_it-playlist-release-date">'. esc_html__('Release date', 'sonaar') .': <span class="sr_it-date-value">'.
        ( ( get_post_meta( $firstAlbum, 'alb_release_date', true ) )? get_post_meta($firstAlbum, 'alb_release_date', true ): '' ) . '</span></div>
        <ul>' . $format_playlist . '</ul>
        </div>
        
        <div class="album-store">' . $this->get_market( $show_market ) . '</div>
        <div class="album-player" ' . $remove_player .'>
        <div class="track-title"></div>
        <div class="album-title"></div>
        
        <div class="player">
        <div class="currentTime"></div>
        <div id="'.esc_attr($args["widget_id"]). '-' . bin2hex(random_bytes(5)) . '-wave" class="wave"></div>
        <div class="totalTime"></div>
        <div class="control">
        <a class="previous" style="opacity:0;">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 10.2 11.7" style="enable-background:new 0 0 10.2 11.7;" xml:space="preserve">
        <polygon points="10.2,0 1.4,5.3 1.4,0 0,0 0,11.7 1.4,11.7 1.4,6.2 10.2,11.7"/>
        </svg>
        </a>
        <a class="play" style="opacity:0;">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 17.5 21.2" style="enable-background:new 0 0 17.5 21.2;" xml:space="preserve">
        <path d="M0,0l17.5,10.9L0,21.2V0z"/>
        
        <rect width="6" height="21.2"/>
        <rect x="11.5" width="6" height="21.2"/>
        </svg>
        </a>
        <a class="next" style="opacity:0;">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 10.2 11.7" style="enable-background:new 0 0 10.2 11.7;" xml:space="preserve">
        <polygon points="0,11.7 8.8,6.4 8.8,11.7 10.2,11.7 10.2,0 8.8,0 8.8,5.6 0,0"/>
        </svg>
        </a>
        </div>
        </div>
        </div>
        </div>';
        
        
        
        echo $action;
        echo $args['after_widget'];
    }
    
    /**
    * Back-end widget form.
    */
    
    public function form ( $instance ){
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
            
            $title = esc_attr( $instance['title'] );
            $albums = $instance['albums'];
            $show_playlist = (bool)$instance['show_playlist'];
            $show_album_market = (bool)$instance['show_album_market'];
            $show_track_market = (bool)$instance['show_track_market'];
            $remove_player = (bool)$instance['remove_player'];
            
            $all_albums = get_posts(array(
            'post_type' => 'album'
            , 'posts_per_page' => -1
            , 'no_found_rows'  => true
            ));
            
            if ( !empty( $all_albums ) ) :?>

  <p>
    <label for="<?php echo $this->get_field_id('title'); ?>">
      <?php _ex('Title:', 'Widget', 'sonaar'); ?>
    </label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" placeholder="<?php _e('Popular Songs', 'sonaar'); ?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('albums'); ?>">
      <?php _ex('Album:', 'Widget', 'sonaar'); ?>
    </label>
    <select class="widefat" id="<?php echo $this->get_field_id('albums'); ?>" name="<?php echo $this->get_field_name('albums'); ?>[]" multiple="multiple">
      <?php foreach($all_albums as $a): ?>

        <option value="<?php echo $a->ID; ?>" <?php echo (in_array($a->ID, $albums) ? ' selected="selected"' : ''); ?>>
          <?php echo esc_attr($a->post_title); ?>
        </option>

        <?php endforeach; ?>
    </select>
  </p>

  <p>
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_playlist'); ?>" name="<?php echo $this->get_field_name('show_playlist'); ?>" <?php checked( $show_playlist ); ?> />
    <label for="<?php echo $this->get_field_id('show_playlist'); ?>">
      <?php _e( 'Show Playlist' ); ?>
    </label>
    <br />
  </p>

  <p>
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_album_market'); ?>" name="<?php echo $this->get_field_name('show_album_market'); ?>" <?php checked( $show_album_market ); ?> />
    <label for="<?php echo $this->get_field_id('show_album_market'); ?>">
      <?php _e( 'Show Album store' ); ?>
    </label>
    <br />
  </p>
  <p>
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_track_market'); ?>" name="<?php echo $this->get_field_name('show_track_market'); ?>" <?php checked( $show_track_market ); ?> />
    <label for="<?php echo $this->get_field_id('show_track_market'); ?>">
      <?php _e( 'Show Track store' ); ?>
    </label>
    <br />
  </p>
  </p>
  <p>
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('remove_player'); ?>" name="<?php echo $this->get_field_name('remove_player'); ?>" <?php checked( $remove_player ); ?> />
    <label for="<?php echo $this->get_field_id('remove_player'); ?>">
      <?php _e( 'Remove Visual Player' ); ?>
    </label>
    <br />
  </p>

  <?php
            else:
                
            echo wp_kses_post( '<p>'. sprintf( _x('No albums have been created yet. <a href="%s">Create some</a>.', 'Widget', 'sonaar'), esc_url(admin_url('edit.php?post_type=album')) ) .'</p>' );
            
            endif;
    }
    
    
    
    
    
    
    /**
    * Sanitize widget form values as they are saved.
    */
    
    public function update ( $new_instance, $old_instance )
    {
        $instance = wp_parse_args( $old_instance, self::$widget_defaults );
            
            $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
            $instance['albums'] = $new_instance['albums'];
            $instance['show_playlist']  = (bool)$new_instance['show_playlist'];
            $instance['show_album_market']  = (bool)$new_instance['show_album_market'];
            $instance['show_track_market']  = (bool)$new_instance['show_track_market'];
            $instance['remove_player']  = (bool)$new_instance['remove_player'];
            
            return $instance;
    }
    
    
    private function print_playlist_json() {
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
        
        
        $albums = get_posts(array(
        'post_type' => 'album'
        , 'post__in' => $album_ids
        ));
        
        $tracks = array();
        foreach($albums as $a) {
            
            $album_tracks =  get_post_meta( $a->ID, 'alb_tracklist', true);

            for($i = 0 ; $i < count($album_tracks) ; $i++) {
                
                $fileOrStream =  $album_tracks[$i]['FileOrStream'];
                $thumb_id = get_post_thumbnail_id($a->ID);
                $thumb_url = ( $thumb_id )? wp_get_attachment_image_src($thumb_id, Sonaar_Music::get_option('music_player_coverSize'), true) : false ;
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
                            $mp3_id = $album_tracks[$i]["track_mp3_id"];
                            $mp3_metadata = wp_get_attachment_metadata( $mp3_id );
                            $track_title = ( isset( $mp3_metadata["title"] ) && $mp3_metadata["title"] !== '' )? $mp3_metadata["title"] : false ;
                            $track_title = ( get_the_title($mp3_id) !== '' && $track_title !== get_the_title($mp3_id))? get_the_title($mp3_id): $track_title;
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
        
        if ( $mp3_metadata ) {
            // $album_tracks[$i]["mp3_metadata"] = $mp3_metadata;
            
        }
        $album_tracks[$i] = array();
        $album_tracks[$i]["id"] = ( $mp3_id )? $mp3_id : NULL ;
        $album_tracks[$i]["mp3"] = $audioSrc;
        $album_tracks[$i]["loading"] = $showLoading;
        $album_tracks[$i]["track_title"] = ( $track_title )? $track_title : "Track title ". $i;
        $album_tracks[$i]["lenght"] = $album_tracks_lenght;
        $album_tracks[$i]["album_title"] = ( $album_title )? $album_title : $a->post_title;
        $album_tracks[$i]["poster"] = $thumb_url[0];
        $album_tracks[$i]["release_date"] = get_post_meta($a->ID, 'alb_release_date', true);
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



}