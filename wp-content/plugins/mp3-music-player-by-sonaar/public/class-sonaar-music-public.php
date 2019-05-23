<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       sonaar.io
 * @since      1.0.0
 *
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/public
 * @author     Edouard Duplessis <eduplessis@gmail.com>
 */
class Sonaar_Music_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sonaar-music-public.css', array(), $this->version, 'all' );
		$data = "";
		$font = Sonaar_Music::get_option('music_player_playlist');
		$fontTitle = Sonaar_Music::get_option('music_player_album_title');
		$fontdate = Sonaar_Music::get_option('music_player_date');
		$data .= ( $font['font-family'] !== '' && !strpos($font['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $font['font-family'] .');':'';
		$data .= ( $fontTitle['font-family'] !== '' && !strpos($fontTitle['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $fontTitle['font-family'] .');':'';
		$data .= ( $fontdate['font-family'] !== '' && !strpos($fontdate['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $fontdate['font-family'] .');':'';



		if( $font['font-family'] !== ''){
			$formatedFontfamily = str_replace('_safe_', '',$font['font-family']);
			$formatedFontfamily = str_replace('+', ' ',$font['font-family']);
			$formatedFontfamily = ( strstr( $formatedFontfamily, ':' ) )? strstr( $formatedFontfamily, ':', true ): $formatedFontfamily;
			
			$data .= '.iron-audioplayer  .playlist .audio-track, .iron-audioplayer .track-title, .iron-audioplayer .album-store, .iron-audioplayer  .playlist .track-number, .iron-audioplayer .sr_it-playlist-title{ font-family:' . $formatedFontfamily . ';}';
		}
		
		
		$data .= ( $font['font-size'] !== '' )? '.iron-audioplayer  .playlist .audio-track, .iron-audioplayer .track-title, .iron-audioplayer .album-store, .iron-audioplayer  .playlist .track-number, .iron-audioplayer .sr_it-playlist-title{ font-size:' . $font['font-size'] . ';}' :'';
		$data .= ( $font['color'] !== '' )? '.iron-audioplayer  .playlist .audio-track, .iron-audioplayer .track-title, .iron-audioplayer .album-store, .iron-audioplayer  .playlist .track-number, .iron-audioplayer .sr_it-playlist-title{ color:' . $font['color'] . ';}' :'';
		
		
		if( $fontTitle['font-family'] !== ''){
			$formatedFontfamily = str_replace('_safe_', '',$fontTitle['font-family']);
			$formatedFontfamily = str_replace('+', ' ',$fontTitle['font-family']);
			$formatedFontfamily = ( strstr( $formatedFontfamily, ':' ) )? strstr( $formatedFontfamily, ':', true ): $formatedFontfamily;
			
			$data .= '.iron-audioplayer .sr_it-playlist-title{ font-family:' . $formatedFontfamily . ';}';
		}	
		$data .= ( $fontTitle['font-size'] !== '' )? '.iron-audioplayer .sr_it-playlist-title{ font-size:' . $fontTitle['font-size'] . ';}' :'';
		$data .= ( $fontTitle['color'] !== '' )? ' .iron-audioplayer .sr_it-playlist-title{ color:' . $fontTitle['color'] . ';}' :'';
		
		
		
		if( $fontdate['font-family'] !== ''){
			$formatedFontfamily = str_replace('_safe_', '',$fontdate['font-family']);
			$formatedFontfamily = str_replace('+', ' ',$fontdate['font-family']);
			$formatedFontfamily = ( strstr( $formatedFontfamily, ':' ) )? strstr( $formatedFontfamily, ':', true ): $formatedFontfamily;
			
			$data .= '.iron-audioplayer .sr_it-playlist-release-date{ font-family:' . $formatedFontfamily . ';}';
		}

			
		$data .= ( $fontdate['font-size'] !== '' )? '.iron-audioplayer .sr_it-playlist-release-date{ font-size:' . $fontdate['font-size'] . ';}' :'';
		$data .= ( $fontdate['color'] !== '' )? '.iron-audioplayer .sr_it-playlist-release-date{ color:' . $fontdate['color'] . ';}' :'';



		$data .= ( Sonaar_Music::get_option('music_player_featured_color') !== '' )? '.iron-audioplayer  .playlist a,.iron-audioplayer .playlist .current .audio-track, .playlist .current .track-number{color:' . Sonaar_Music::get_option('music_player_featured_color') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_store_drawer') !== '' )? '.iron-audioplayer  .playlist .song-store-list-menu .fa-ellipsis-v{color:' . Sonaar_Music::get_option('music_player_store_drawer') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_featured_color') !== '' )? '.iron-audioplayer  .playlist .audio-track path, .iron-audioplayer  .playlist .audio-track rect{fill:' . Sonaar_Music::get_option('music_player_featured_color') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_icon_color') !== '' )? '.iron-audioplayer .control rect, .iron-audioplayer .control path, .iron-audioplayer .control polygon{fill:' . Sonaar_Music::get_option('music_player_icon_color') . ';}' : '';
		
		wp_add_inline_style( $this->plugin_name, $data );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sonaar-music-public.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'moments', plugin_dir_url( __FILE__ ) . 'js/iron-audioplayer/00.moments.min.js', array(), true );
		wp_register_script( 'wave', plugin_dir_url( __FILE__ ) . 'js/iron-audioplayer/00.wavesurfer.min.js', array(), true );
		wp_enqueue_script( $this->plugin_name . '-mp3player', plugin_dir_url( __FILE__ ) . 'js/iron-audioplayer/iron-audioplayer.js', array( 'jquery', $this->plugin_name ,'moments', 'wave' ), $this->version, true );

		wp_localize_script( $this->plugin_name . '-mp3player', 'sonaar_music', array(
			'plugin_dir_url'=> plugin_dir_url( __FILE__ ),
			'option' => Sonaar_Music::get_option( 'allOptions' )
		));

	}

	/**
	 * Inline style for the plugin
	 **/
	public function inline_style(){
		$data = "";
		$font = Sonaar_Music::get_option('music_player_playlist');
		// var_dump($font);
		// die();

		

		$data .= ( $font['font-family'] !== '' && !strpos($font['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $font['font-family'] .');':'';
		var_dump($data);
		die();
		return $data;
	}

}
