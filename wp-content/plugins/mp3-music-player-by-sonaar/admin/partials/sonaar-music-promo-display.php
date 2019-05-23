<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       sonaar.io
 * @since      1.0.0
 *
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css"/>
<link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css"/>
<div id="sonaar_music">
  <b-jumbotron class="text-center" bg-variant="dark" text-variant="white">
  <div class="logo"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/sonaar-music-logo-white.png'?>"></div>
  <div class="headertxt">
    <h1>Go Premium. Be happy.</h1>
    <div><p class="text-center tagline">Get real-time stats on your tracks and playlists</p></div>
  </div>
  	</b-jumbotron>

	<b-card-group deck>
		<b-card 
				title="Why Sonaar Music Pro?"
				bg-variant="dark"
				text-variant="white"
		        img-alt="Image"
		        img-top
		        tag="article"
		        class="text-center">
		       
		        <div><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/premium-banner-sonaarmusicpro_smush.png'?>" class="img-fluid" alt="Responsive image"></div>
		        <div class="sr_it_listgroup">
					<ul>
			          	<li><i class="glyphicon glyphicon-chevron-right"></i>Get listen counts on each of your tracks and playlists.</li>
						<li>View how many times a track has been downloaded.</li>
						<li>Know your Top Played Tracks  and Top Page Performance under a single view.</li>
						<li>Stunning charts with numbers of plays filtered by days, weeks and months.</li>
						<li>Get insights reports directly in your dashboard.</li>
						<li>Priority support through our live chat</li>
						<li>Unlimited Updates</li>
			         </ul>
		      	</div>

		        <em slot="footer"><div><a role="button" class="btn btn-primary btn-lg" href="https://goo.gl/mVUJEJ">Only $34 /year. Go Premium</a></div></em>
		    	
		</b-card>

	  	<b-card
	  			title="Explore our Music WordPress Themes"
	  			bg-variant="dark"
	  			text-variant="white"
	        	img-alt="Image"
	        	img-top
	        	tag="article"
	        	class="text-center">
	          	
				<div><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/premium-banner-sonaarthemes_smush.png'?>" class="img-fluid" alt="Responsive image"></div>
			    
				<div class="sr_it_listgroup">
				    <ul>
				      	<li>Sonaar Music Pro included for free ($34 value).</li>
						<li>+15 Stunning WP Themes crafted for Musicians</li>
						<li>Continuous Music Player with Ajax Page Loading</li>
						<li>Events and Gigs manager</li>
						<li>Photos and Videos albums including YouTube and Instagram.</li>
						<li>Priority support through our live chat</li>
						<li>and much more!</li>
				    </ul>
				</div>


	   			 <em slot="footer"><div><a role="button" class="btn btn-primary btn-lg" href="https://goo.gl/yTNhXi">Only $69 /year. Explore our themes</a></div></em>
	  	</b-card>
	</b-card-group deck>
</div>
