<?php

/*
Plugin Name: Pear Subscription Form
Description: Frontpage subscription form
Author: PovioLabs
Version: 1.1.0
*/

class PearSubscriptionForm {

	// class instance
	static $instance;

	// subscriber WP_List_Table object
	public $subscription_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
		add_action( 'admin_post_pr27_export_csv', [ $this, 'pr27_export_csv' ] );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			__( 'Subscriptions', 'pear' ),
			__( 'Subscriptions', 'pear' ),
			'edit_posts',
			'pear-subscriptions',
			[ $this, 'plugin_settings_page' ],
			'dashicons-editor-ul',
			6
		);

		$email = add_menu_page(
			__( 'Email Content', 'pear' ),
			__( 'Email Content', 'pear' ),
			'edit_posts',
			'pear-email',
			[ $this, 'email_content' ],
			'dashicons-email-alt',
			6
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );
		add_action( "load-$email", [ $this, 'email_option' ] );
	}
		
	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Subscriptions',
			'default' => 10,
			'option'  => 'subscribers_per_page'
		];

		add_screen_option( $option, $args );

		$this->subscription_obj = new Subscriptions_List();
	}

	public function email_option() {
       
	}

	public function email_content() {
		?>
		<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  		<script>tinymce.init({ selector:'textarea' });</script>
  		<?php 	
		global $wpdb;
		$table = $wpdb->prefix . 'email';
		$rows = $wpdb->get_results( "SELECT * FROM $table" );
  			if (isset($_POST['submit'])) 
  			{

  					if(empty($_POST['content']))
  					{
  						echo "Please add some content.";
  					}
  					else
  					{
  						if(isset($_POST['id']) && !empty($_POST['id']))
  						{
  							$id = array('ID' => $_POST['id']);
  							$data = array(
				               'email_content' => htmlspecialchars($_POST['content'])
				            );
				            
				          
				            $success=$wpdb->update($table,$data,$id);
				            if($success){
				            	echo "Data updated successfully.";
								$rows = $wpdb->get_results( "SELECT * FROM $table" );
							}
							else{
								echo "Please try again, submittion unsuccessful.";
							}	
  						}
  						else{
		  					$data = array(
				               'email_content' => htmlspecialchars($_POST['content'])
				            );
				          
				            $success=$wpdb->insert( $table,$data);
				            if($success){
				            	echo "Data submitted successfully.";
								$rows = $wpdb->get_results( "SELECT * FROM $table" );
							}
							else{
								echo "Please try again, submittion unsuccessful.";
							}
						}
  					}
		            
					
			}
			
        ?>
			<div class="wrap">
			<form method="POST" action="#">
				<input type="hidden" name="id" value="<?php echo isset($rows[0]->ID)?$rows[0]->ID:''; ?>">
				<div class="row">
					<h2>Email Content</h2>
					 <textarea name="content" cols="3" row="3" placeholder="Enter email content"><?php echo isset($rows[0]->email_content)?$rows[0]->email_content:''; ?></textarea>
				</div>
				<br class="clear">	 
				<button type="submit" name="submit" class="button button-primary"><?php echo isset($rows[0]->ID)?'Update':'Add'; ?></button> 
			</form>	
			<?php  ?>
			</div>	
		<?php
		
	}
	
	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		global $wpdb;
		$psf = $wpdb->prefix . 'psf';
		$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $psf");
		?>
		<div class="wrap">
			<h2>Subscriptions</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div style="float: right;">
					<label>No. of Records</label>
						<select name="option" id="option">
						<option value="" <?php if(!isset($_GET['num'])){echo "selected='selected'";} ?>>Select</option>
						<option value="5" <?php if(isset($_GET['num']) && $_GET['num'] == 5){echo "selected='selected'";} ?>>5</option>
						  <option value="15" <?php if(isset($_GET['num']) && $_GET['num'] == 15){echo "selected='selected'";} ?>>15</option>
						  <option value="50" <?php if(isset($_GET['num']) && $_GET['num'] == 50){echo "selected='selected'";} ?>>50</option>
						  <option value="100" <?php if(isset($_GET['num']) && $_GET['num'] == 100){echo "selected='selected'";} ?>>100</option>
						  <option value="200" <?php if(isset($_GET['num']) && $_GET['num'] == 200){echo "selected='selected'";} ?>>200</option>
						  <option value="<?php echo $rowcount;  ?>" <?php if(isset($_GET['num']) && $_GET['num'] == $rowcount){echo "selected='selected'";} ?>><?php echo $rowcount;  ?></option>
						</select> 
					</div>	
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->subscription_obj->prepare_items();
								$this->subscription_obj->display();
								?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>

			<a href="<?php echo admin_url( 'admin-post.php?action=pr27_export_csv' ); ?>" class="button button-primary">Export</a>
		</div>
		<script type="text/javascript"> 
			jQuery( "#option" ).change(function() {
			  var url = window.location.href;
	   		  //url = url.slice( 0, url.indexOf('&') );
	   		  count = this.value;

			  window.location.href = url+"&paged=1&num=" + count; 
			});
		</script>
		<?php
	}

	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	static function psf_install() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'psf';
		$email_content      = $wpdb->prefix . 'email';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS" .$table_name."(
			`ID` INT NOT NULL AUTO_INCREMENT ,
			`Name` VARCHAR(255) NOT NULL ,
			`Email` VARCHAR(255) NOT NULL ,
			`Age` TINYINT NOT NULL ,
			`Location` VARCHAR(255) ,
			`Country` VARCHAR(255) ,
			`Gender` VARCHAR(255) ,
			`City` VARCHAR(255) ,
			`utm_source` VARCHAR(255),
			'created_date' DATETIME,
			`refferal_link` VARCHAR(400) ,
			`unsubscribe` BOOLEAN NOT NULL DEFAULT FALSE,
			PRIMARY KEY (`ID`),
		  	UNIQUE KEY `Email` (`Email`)) $charset_collate;";

		$content = "CREATE TABLE IF NOT EXISTS" .$email_content."(
			`ID` INT NOT NULL AUTO_INCREMENT ,
			`email_content` VARCHAR(1000) NOT NULL ,
			PRIMARY KEY (`ID`)) $charset_collate;";  	

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		dbDelta( $content );

		update_option( "psf_db_version", '1.1' );
	}

	static function psf_update_db_check() {
		if ( get_site_option( 'jal_db_version' ) != '1.1' ) {
			PearSubscriptionForm::psf_install();
		}
	}

	static function form() {
		ob_start(); ?>
		<div class="mid-section text-center">
			<div class="pear-logo"></div>
			 <h1>Where science and maths meet to find your perfect match</h1>
          <p class="mt16 mb32 join-intro">Join our community to know when Pear will be launched in your city</p>
          <div class="row join-form-container">
          		
				<!-- <div class="col-xs-12 col-sm-4">
					<input id="name" class="input-boxed" type="text" placeholder="Name" required/>
				</div> -->
				<div class="col-xs-12 col-sm-7">
					<input id="email" class="input-boxed" type="text" placeholder="Email" required/>
				</div>
				<!-- <div class="col-xs-12 col-sm-2 select-container" style="z-index:10">
					<select id="age" class="select-age-home">
						<option></option>
					</select>
					
				</div> -->
				<input id="name" type="hidden" placeholder="Name" value="not-set"/>
				<input type="hidden" name="utm_source" id="source" value="<?php echo empty($_GET['utm_source']) ? "direct" : $_GET['utm_source']; ?>">

				<input type="hidden" name="refferal" id="refferal" value="<?php echo empty($_SERVER['HTTP_REFERER']) ? $_GET['utm_source'] : $_SERVER['HTTP_REFERER']; ?>">
				<input id="age" class="input-boxed" type="hidden" value="0"  />
				<input type="text" name="surname" style="display:none;">
				<?php echo wp_nonce_field( 'psf_form_submit', 'psf_nonce', true, false ); ?>
               
				<div id="psf-submit" class="col-xs-12 col-sm-8 col-md-4 button green shadow text-uppercase button-join">Join now
				<!-- <img class="loader inactive" src="<?php echo get_template_directory_uri(); ?>/dist/images/loader.svg"/>  -->
				<div class="loader inactive"></div>
				</div>
			</div>
			
			<!--<div class="comng-soon">Coming soon to</div>
            <p class="coming-soon">
                 <img src="<?php echo get_template_directory_uri(); ?>/dist/images/soon-app-store.png"/>
                 <span class="seprator">&nbsp;</span>
                 <img src="<?php echo get_template_directory_uri(); ?>/dist/images/soon-google-play.svg"/ class="no-border">
            </p>
		</div>-->
		<div class="download-app">
                <h5>Download now</h5>
                <div class="platform-store">
                  <div class="badge app-store">
                    <a href="https://itunes.apple.com/gb/app/pear-algorithmic-matching/id1128297474?mt=8" target="_blank">
                      <svg xmlns="http://www.w3.org/2000/svg" width="135" height="40" viewBox="0 0 135 40">
                        <path fill="#A6A6A6" d="M130.197 40H4.73C2.12 40 0 37.872 0 35.267V4.727C0 2.12 2.122 0 4.73 0h125.467C132.803 0 135 2.12 135 4.726v30.54c0 2.606-2.197 4.734-4.803 4.734z" />
                        <path d="M134.032 35.268c0 2.116-1.714 3.83-3.834 3.83H4.728c-2.118 0-3.838-1.714-3.838-3.83V4.725C.89 2.61 2.61.89 4.73.89h125.467c2.12 0 3.834 1.72 3.834 3.835l.002 30.543z" />
                        <g fill="#FFF">
                          <path d="M30.128 19.784c-.03-3.223 2.64-4.79 2.76-4.864-1.51-2.203-3.852-2.504-4.675-2.528-1.967-.207-3.875 1.177-4.877 1.177-1.022 0-2.565-1.158-4.228-1.124-2.14.033-4.142 1.272-5.24 3.196-2.266 3.923-.576 9.688 1.595 12.86 1.086 1.552 2.355 3.286 4.016 3.225 1.624-.067 2.23-1.036 4.192-1.036 1.943 0 2.513 1.037 4.207.998 1.743-.028 2.84-1.56 3.89-3.127 1.254-1.78 1.758-3.532 1.778-3.622-.04-.014-3.387-1.29-3.42-5.154zM26.928 10.306c.874-1.093 1.472-2.58 1.306-4.09-1.265.057-2.847.876-3.758 1.945-.806.943-1.526 2.487-1.34 3.94 1.42.105 2.88-.718 3.792-1.794z" />
                        </g>
                        <g fill="#FFF">
                          <path d="M53.645 31.504h-2.27l-1.245-3.91h-4.324l-1.185 3.91h-2.21l4.284-13.308h2.646l4.305 13.308zm-3.89-5.55L48.63 22.48c-.12-.355-.342-1.19-.67-2.507h-.04c-.132.566-.343 1.402-.633 2.507l-1.105 3.475h3.573zM64.662 26.588c0 1.632-.44 2.922-1.323 3.87-.79.842-1.772 1.263-2.943 1.263-1.264 0-2.172-.453-2.725-1.36h-.04v5.054H55.5V25.067c0-1.026-.027-2.08-.08-3.16h1.876l.12 1.522h.04c.71-1.147 1.79-1.72 3.237-1.72 1.132 0 2.077.448 2.833 1.343.758.896 1.136 2.074 1.136 3.535zm-2.172.078c0-.934-.21-1.704-.632-2.31-.46-.632-1.08-.948-1.856-.948-.526 0-1.004.176-1.43.523-.43.35-.71.808-.84 1.374-.066.264-.1.48-.1.65v1.6c0 .698.215 1.287.643 1.768s.984.72 1.668.72c.803 0 1.428-.31 1.875-.927.448-.62.672-1.435.672-2.45zM75.7 26.588c0 1.632-.442 2.922-1.325 3.87-.79.842-1.77 1.263-2.94 1.263-1.265 0-2.173-.453-2.725-1.36h-.04v5.054h-2.132V25.067c0-1.026-.027-2.08-.08-3.16h1.876l.12 1.522h.04c.71-1.147 1.788-1.72 3.237-1.72 1.132 0 2.077.448 2.835 1.343.755.896 1.134 2.074 1.134 3.535zm-2.173.078c0-.934-.21-1.704-.633-2.31-.46-.632-1.078-.948-1.855-.948-.528 0-1.005.176-1.433.523-.428.35-.707.808-.838 1.374-.066.264-.1.48-.1.65v1.6c0 .698.214 1.287.64 1.768.428.48.984.72 1.67.72.803 0 1.428-.31 1.875-.927.448-.62.672-1.435.672-2.45zM88.04 27.772c0 1.132-.394 2.053-1.183 2.764-.867.777-2.074 1.165-3.625 1.165-1.432 0-2.58-.275-3.45-.828l.495-1.777c.936.566 1.963.85 3.082.85.802 0 1.427-.182 1.876-.544.447-.36.67-.847.67-1.453 0-.54-.184-.995-.553-1.364-.367-.37-.98-.712-1.836-1.03-2.33-.868-3.494-2.14-3.494-3.815 0-1.094.408-1.99 1.225-2.69.814-.698 1.9-1.047 3.258-1.047 1.21 0 2.217.212 3.02.633l-.533 1.738c-.75-.407-1.598-.61-2.547-.61-.75 0-1.336.184-1.756.552-.355.33-.533.73-.533 1.205 0 .526.203.96.61 1.303.356.316 1 .658 1.937 1.027 1.145.46 1.986 1 2.527 1.618.54.616.81 1.387.81 2.307zM95.088 23.508h-2.35v4.66c0 1.184.414 1.776 1.244 1.776.38 0 .697-.033.947-.1l.058 1.62c-.42.157-.973.236-1.658.236-.842 0-1.5-.257-1.975-.77-.473-.514-.71-1.376-.71-2.587v-4.837h-1.4v-1.6h1.4V20.15l2.093-.633v2.39h2.35v1.6zM105.69 26.627c0 1.475-.42 2.686-1.263 3.633-.883.975-2.055 1.46-3.516 1.46-1.407 0-2.528-.466-3.364-1.4s-1.254-2.113-1.254-3.534c0-1.487.43-2.705 1.293-3.652.86-.948 2.023-1.422 3.484-1.422 1.407 0 2.54.467 3.395 1.402.818.907 1.226 2.078 1.226 3.513zm-2.21.07c0-.886-.19-1.645-.573-2.278-.447-.767-1.086-1.15-1.914-1.15-.857 0-1.508.384-1.955 1.15-.383.633-.572 1.404-.572 2.316 0 .885.19 1.644.572 2.276.46.766 1.105 1.148 1.936 1.148.814 0 1.453-.39 1.914-1.168.393-.645.59-1.412.59-2.296zM112.62 23.783c-.21-.04-.435-.06-.67-.06-.75 0-1.33.284-1.74.85-.354.5-.532 1.133-.532 1.896v5.034h-2.13l.02-6.574c0-1.106-.028-2.113-.08-3.02h1.856l.078 1.835h.06c.224-.63.58-1.14 1.065-1.52.475-.343.988-.514 1.54-.514.198 0 .376.015.534.04v2.033zM122.156 26.252c0 .382-.025.704-.078.967h-6.396c.025.947.334 1.672.928 2.172.54.447 1.236.67 2.092.67.947 0 1.81-.15 2.588-.453l.334 1.48c-.908.395-1.98.592-3.217.592-1.488 0-2.656-.438-3.506-1.313-.847-.876-1.272-2.05-1.272-3.525 0-1.447.395-2.652 1.186-3.613.828-1.026 1.947-1.54 3.355-1.54 1.382 0 2.43.514 3.14 1.54.563.815.846 1.823.846 3.02zm-2.033-.553c.014-.633-.125-1.18-.414-1.64-.37-.593-.937-.89-1.7-.89-.697 0-1.264.29-1.697.87-.355.46-.566 1.014-.63 1.658h4.44z" />
                        </g>
                        <g fill="#FFF">
                          <path d="M49.05 10.01c0 1.176-.353 2.062-1.058 2.657-.653.55-1.58.824-2.783.824-.597 0-1.107-.025-1.534-.077v-6.43c.557-.09 1.157-.137 1.805-.137 1.146 0 2.01.25 2.59.747.653.563.98 1.368.98 2.416zm-1.105.028c0-.763-.202-1.348-.606-1.756-.405-.407-.995-.61-1.772-.61-.33 0-.61.02-.844.067v4.888c.13.02.365.03.708.03.802 0 1.42-.224 1.857-.67s.655-1.096.655-1.95zM54.91 11.037c0 .725-.208 1.32-.622 1.785-.434.48-1.01.718-1.727.718-.69 0-1.242-.23-1.653-.69-.41-.458-.615-1.037-.615-1.735 0-.73.21-1.33.635-1.794s.994-.697 1.712-.697c.69 0 1.247.23 1.668.688.4.447.6 1.023.6 1.727zm-1.088.034c0-.434-.094-.807-.28-1.118-.22-.376-.534-.564-.94-.564-.422 0-.742.188-.962.564-.188.31-.28.69-.28 1.138 0 .435.093.808.28 1.12.227.375.543.563.95.563.4 0 .715-.19.94-.574.195-.318.292-.694.292-1.13zM62.765 8.72l-1.475 4.713h-.96l-.61-2.047c-.156-.51-.282-1.02-.38-1.523h-.02c-.09.518-.216 1.025-.378 1.523l-.65 2.047h-.97L55.935 8.72h1.077l.533 2.24c.13.53.235 1.035.32 1.513h.02c.077-.394.206-.896.388-1.503l.67-2.25h.853l.64 2.202c.156.537.282 1.054.38 1.552h.028c.07-.485.178-1.002.32-1.552l.572-2.202h1.03zM68.198 13.433H67.15v-2.7c0-.832-.316-1.248-.95-1.248-.31 0-.562.114-.757.343-.193.23-.29.5-.29.808v2.796h-1.05v-3.366c0-.414-.012-.863-.037-1.35h.92l.05.738h.03c.12-.23.303-.418.542-.57.284-.175.602-.264.95-.264.44 0 .806.142 1.097.427.362.35.543.87.543 1.562v2.823zM71.088 13.433H70.04V6.556h1.048v6.877zM77.258 11.037c0 .725-.207 1.32-.62 1.785-.435.48-1.01.718-1.728.718-.693 0-1.244-.23-1.654-.69-.41-.458-.615-1.037-.615-1.735 0-.73.212-1.33.636-1.794s.994-.697 1.71-.697c.694 0 1.25.23 1.67.688.4.447.602 1.023.602 1.727zm-1.088.034c0-.434-.094-.807-.28-1.118-.22-.376-.534-.564-.94-.564-.422 0-.742.188-.96.564-.19.31-.282.69-.282 1.138 0 .435.094.808.28 1.12.228.375.544.563.952.563.4 0 .713-.19.94-.574.194-.318.29-.694.29-1.13zM82.33 13.433h-.94l-.08-.543h-.028c-.322.433-.78.65-1.377.65-.445 0-.805-.143-1.076-.427-.247-.258-.37-.58-.37-.96 0-.576.24-1.015.723-1.32.482-.303 1.16-.452 2.033-.445V10.3c0-.62-.326-.93-.98-.93-.464 0-.874.116-1.228.348l-.213-.688c.438-.27.98-.407 1.617-.407 1.232 0 1.85.65 1.85 1.95v1.736c0 .47.023.845.068 1.123zm-1.088-1.62v-.727c-1.156-.02-1.734.297-1.734.95 0 .246.066.43.2.553.136.122.308.183.513.183.23 0 .446-.073.642-.218.197-.146.318-.33.363-.558.01-.05.017-.113.017-.184zM88.285 13.433h-.93l-.05-.757h-.028c-.297.576-.803.864-1.514.864-.568 0-1.04-.223-1.416-.67s-.562-1.024-.562-1.735c0-.763.203-1.38.61-1.853.396-.44.88-.66 1.456-.66.634 0 1.077.213 1.33.64h.02V6.556h1.048v5.607c0 .46.012.882.037 1.27zM87.2 11.445v-.786c0-.137-.01-.247-.03-.33-.06-.253-.186-.465-.38-.636-.194-.17-.43-.257-.7-.257-.39 0-.697.155-.922.466-.223.31-.336.708-.336 1.193 0 .466.107.844.322 1.135.227.31.533.466.916.466.344 0 .62-.13.828-.388.202-.24.3-.527.3-.863zM97.248 11.037c0 .725-.207 1.32-.62 1.785-.435.48-1.01.718-1.728.718-.69 0-1.242-.23-1.654-.69-.41-.458-.615-1.037-.615-1.735 0-.73.212-1.33.636-1.794s.994-.697 1.713-.697c.69 0 1.247.23 1.667.688.4.447.6 1.023.6 1.727zm-1.086.034c0-.434-.094-.807-.28-1.118-.222-.376-.534-.564-.942-.564-.42 0-.74.188-.96.564-.19.31-.282.69-.282 1.138 0 .435.094.808.28 1.12.228.375.544.563.952.563.4 0 .715-.19.94-.574.194-.318.292-.694.292-1.13zM102.883 13.433h-1.047v-2.7c0-.832-.316-1.248-.95-1.248-.312 0-.563.114-.757.343s-.292.5-.292.808v2.796h-1.05v-3.366c0-.414-.01-.863-.036-1.35h.92l.05.738h.028c.123-.23.305-.418.543-.57.285-.175.602-.264.95-.264.44 0 .806.142 1.097.427.363.35.543.87.543 1.562v2.823zM109.936 9.504h-1.154v2.29c0 .582.205.873.61.873.19 0 .345-.016.468-.05l.027.796c-.207.078-.48.117-.814.117-.414 0-.736-.126-.97-.378-.233-.252-.35-.676-.35-1.27V9.503h-.688V8.72h.69v-.865l1.026-.31v1.173h1.155v.786zM115.484 13.433h-1.05v-2.68c0-.845-.315-1.268-.948-1.268-.486 0-.818.245-1 .735-.03.103-.05.23-.05.377v2.835h-1.046V6.556h1.047v2.84h.02c.33-.516.803-.774 1.416-.774.434 0 .793.142 1.078.427.356.354.534.882.534 1.58v2.803zM121.207 10.853c0 .188-.014.346-.04.475h-3.142c.014.466.164.82.455 1.067.266.22.61.33 1.03.33.464 0 .888-.074 1.27-.223l.164.728c-.447.194-.973.29-1.582.29-.73 0-1.305-.214-1.72-.644-.42-.43-.626-1.007-.626-1.73 0-.712.193-1.304.582-1.776.406-.504.955-.756 1.648-.756.678 0 1.193.252 1.54.756.282.4.42.895.42 1.483zm-1-.27c.008-.312-.06-.58-.203-.806-.182-.29-.46-.437-.834-.437-.342 0-.62.142-.834.427-.174.227-.277.498-.31.815h2.18z" />
                        </g>
                      </svg>
                    </a>
                  </div>
                  <div class="badge google-play">
                    <a href="https://play.google.com/store/apps/details?id=me.pear" target="_blank">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="135.717" height="40.02" viewBox="0 0 135.717 40.02">
                        <pattern x="-84.642" y="218.51" width="124" height="48" patternUnits="userSpaceOnUse" id="s" viewBox="0 -48 124 48" overflow="visible">
                          <path fill="none" d="M0 0h124v-48H0z" />
                          <defs>
                            <path id="a" d="M0-48h124V0H0z" />
                          </defs>
                          <clipPath id="b">
                            <use xlink:href="#a" overflow="visible" />
                          </clipPath>
                          <g clip-path="url(#b)">
                            <path d="M29.625-20.695l-11.613 6.598c-.65.37-1.23.344-1.606.008l-.058.063.058.06c.375.335.957.358 1.606-.013L29.7-20.62l-.075-.075z" />
                          </g>
                        </pattern>
                        <pattern x="-84.642" y="218.51" width="124" height="48" patternUnits="userSpaceOnUse" id="A" viewBox="0 -48 124 48" overflow="visible">
                          <path fill="none" d="M0 0h124v-48H0z" />
                          <defs>
                            <path id="c" d="M0-48h124V0H0z" />
                          </defs>
                          <clipPath id="d">
                            <use xlink:href="#c" overflow="visible" />
                          </clipPath>
                          <g clip-path="url(#d)">
                            <path d="M16.348-14.145c-.235-.246-.37-.63-.37-1.125v.117c0 .496.135.88.37 1.125l.058-.062-.058-.055z" />
                          </g>
                        </pattern>
                        <pattern x="-84.642" y="218.51" width="124" height="48" patternUnits="userSpaceOnUse" id="I" viewBox="0 -48 124 48" overflow="visible">
                          <path fill="none" d="M0 0h124v-48H0z" />
                          <defs>
                            <path id="e" d="M0-48h124V0H0z" />
                          </defs>
                          <clipPath id="f">
                            <use xlink:href="#e" overflow="visible" />
                          </clipPath>
                          <g clip-path="url(#f)">
                            <path d="M33.613-22.96l-3.988 2.265.074.074 3.913-2.224c.56-.316.836-.734.836-1.156-.048.38-.333.75-.837 1.04z" />
                          </g>
                        </pattern>
                        <pattern x="-84.642" y="218.51" width="124" height="48" patternUnits="userSpaceOnUse" id="Q" viewBox="0 -48 124 48" overflow="visible">
                          <path fill="none" d="M0 0h124v-48H0z" />
                          <defs>
                            <path id="g" d="M0-48h124V0H0z" />
                          </defs>
                          <clipPath id="h">
                            <use xlink:href="#g" overflow="visible" />
                          </clipPath>
                          <g clip-path="url(#h)">
                            <path fill="#FFF" d="M18.012-33.902l15.6 8.863c.51.29.79.66.837 1.04 0-.418-.278-.836-.837-1.156l-15.6-8.864c-1.118-.633-2.036-.105-2.036 1.176v.113c0-1.278.918-1.805 2.035-1.172z" />
                          </g>
                        </pattern>
                        <path d="M130.54 39.943H5.24c-2.755 0-5.012-2.23-5.012-4.954V5.266C.228 2.544 2.485.314 5.24.314h125.3c2.755 0 5.01 2.23 5.01 4.953V34.99c0 2.723-2.255 4.953-5.01 4.953z" />
                        <path fill="#A6A6A6" d="M130.54 1.108c2.32 0 4.207 1.866 4.207 4.16v29.72c0 2.295-1.888 4.16-4.208 4.16H5.24c-2.32 0-4.207-1.865-4.207-4.16V5.27c0-2.294 1.888-4.16 4.207-4.16h125.3m0-.794H5.24C2.485.314.228 2.544.228 5.267V34.99c0 2.723 2.257 4.953 5.012 4.953h125.3c2.755 0 5.01-2.23 5.01-4.954V5.266c0-2.723-2.255-4.953-5.01-4.953z" />
                        <path fill="#FFF" stroke="#FFF" stroke-width=".16" stroke-miterlimit="10" d="M45.934 16.195c0 .668-.2 1.203-.594 1.602-.454.473-1.044.71-1.766.71-.69 0-1.28-.24-1.765-.718-.486-.485-.728-1.08-.728-1.79s.242-1.304.727-1.784c.483-.48 1.073-.723 1.764-.723.344 0 .672.07.985.203.31.132.565.312.75.535l-.42.423c-.32-.38-.757-.566-1.316-.566-.504 0-.94.177-1.312.53-.367.357-.55.818-.55 1.384s.183 1.032.55 1.387c.37.352.808.53 1.312.53.535 0 .985-.18 1.34-.534.234-.235.367-.56.402-.973h-1.742v-.577h2.324c.028.124.036.245.036.362zM49.62 14.19H47.44v1.52h1.97v.58h-1.97v1.52h2.183v.588h-2.8v-4.796h2.8v.59zM52.223 18.398h-.618V14.19h-1.34v-.588h3.298v.588h-1.34v4.208zM55.95 18.398v-4.796h.616v4.796h-.617zM59.3 18.398h-.612V14.19h-1.344v-.588h3.3v.588h-1.343v4.208zM66.887 17.78c-.473.486-1.06.728-1.758.728-.704 0-1.29-.242-1.763-.727-.472-.483-.707-1.077-.707-1.78 0-.703.235-1.297.707-1.78.473-.486 1.06-.728 1.762-.728.694 0 1.28.242 1.753.73.475.49.71 1.08.71 1.778 0 .703-.234 1.297-.706 1.78zm-3.063-.4c.356.36.79.538 1.305.538.51 0 .948-.18 1.3-.54.355-.358.535-.82.535-1.377 0-.558-.18-1.02-.535-1.378-.352-.36-.79-.54-1.3-.54-.517 0-.95.182-1.306.54-.355.36-.535.82-.535 1.38 0 .557.18 1.018.534 1.377zM68.46 18.398v-4.796h.752l2.332 3.73h.026l-.026-.923V13.6h.616v4.796h-.644l-2.442-3.914h-.027l.027.926v2.99h-.613z" transform="matrix(1.253 0 0 1.2384 -9.796 -9.594)" />
                        <path fill="#FFF" d="M68.526 21.865c-2.354 0-4.277 1.77-4.277 4.214 0 2.423 1.922 4.212 4.276 4.212 2.36 0 4.283-1.79 4.283-4.213 0-2.444-1.924-4.215-4.284-4.215zm0 6.768c-1.29 0-2.403-1.054-2.403-2.554 0-1.52 1.112-2.556 2.403-2.556s2.41 1.035 2.41 2.555c0 1.5-1.118 2.553-2.41 2.553zm-9.333-6.768c-2.36 0-4.278 1.77-4.278 4.214 0 2.423 1.918 4.212 4.278 4.212 2.357 0 4.278-1.79 4.278-4.213 0-2.444-1.92-4.215-4.277-4.215zm0 6.768c-1.293 0-2.408-1.054-2.408-2.554 0-1.52 1.115-2.556 2.408-2.556 1.292 0 2.403 1.035 2.403 2.555 0 1.5-1.11 2.553-2.403 2.553zm-11.107-5.477v1.79h4.327c-.127 1.002-.465 1.737-.984 2.25-.632.62-1.616 1.307-3.344 1.307-2.662 0-4.747-2.124-4.747-4.756s2.084-4.756 4.746-4.756c1.44 0 2.488.558 3.26 1.278l1.277-1.262c-1.082-1.02-2.52-1.804-4.537-1.804-3.65 0-6.72 2.936-6.72 6.545s3.07 6.545 6.72 6.545c1.973 0 3.455-.64 4.62-1.838 1.194-1.18 1.566-2.84 1.566-4.18 0-.416-.035-.798-.097-1.118h-6.09zm45.416 1.39c-.352-.944-1.438-2.68-3.65-2.68-2.195 0-4.02 1.705-4.02 4.213 0 2.36 1.807 4.212 4.23 4.212 1.958 0 3.088-1.18 3.553-1.868l-1.453-.957c-.485.7-1.146 1.166-2.1 1.166-.95 0-1.63-.43-2.065-1.277l5.702-2.33-.198-.48zm-5.814 1.402c-.05-1.625 1.277-2.457 2.227-2.457.744 0 1.376.368 1.586.895l-3.812 1.563zm-4.634 4.088h1.874V17.652h-1.874v12.384zm-3.07-7.233h-.064c-.42-.493-1.224-.938-2.242-.938-2.135 0-4.087 1.852-4.087 4.228 0 2.36 1.953 4.2 4.088 4.2 1.018 0 1.82-.45 2.242-.958h.064v.604c0 1.61-.87 2.476-2.276 2.476-1.145 0-1.857-.817-2.15-1.505l-1.63.674c.47 1.117 1.715 2.49 3.78 2.49 2.198 0 4.052-1.275 4.052-4.386V22.12h-1.776v.683zm-2.144 5.83c-1.292 0-2.373-1.07-2.373-2.54 0-1.485 1.08-2.57 2.373-2.57 1.273 0 2.278 1.085 2.278 2.57 0 1.47-1.005 2.54-2.278 2.54zm24.438-10.98h-4.483v12.383h1.87v-4.693h2.614c2.076 0 4.112-1.485 4.112-3.846-.002-2.36-2.043-3.845-4.114-3.845zm.05 5.968h-2.664v-4.246h2.663c1.396 0 2.193 1.146 2.193 2.123 0 .958-.797 2.124-2.193 2.124zm11.555-1.78c-1.35 0-2.755.59-3.333 1.897l1.66.687c.355-.687 1.013-.91 1.706-.91.97 0 1.955.575 1.968 1.593v.13c-.338-.193-1.062-.48-1.953-.48-1.786 0-3.606.973-3.606 2.787 0 1.66 1.463 2.728 3.108 2.728 1.258 0 1.952-.56 2.388-1.213h.064v.956h1.806v-4.75c0-2.196-1.658-3.425-3.807-3.425zm-.225 6.788c-.612 0-1.464-.3-1.464-1.05 0-.958 1.063-1.326 1.983-1.326.823 0 1.21.18 1.71.416-.148 1.147-1.147 1.96-2.23 1.96zm10.61-6.516l-2.147 5.37h-.063l-2.222-5.37h-2.016l3.337 7.504-1.905 4.173h1.954l5.143-11.678h-2.08zm-16.85 7.924h1.874V17.652h-1.874v12.384z" />
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <linearGradient id="i" gradientUnits="userSpaceOnUse" x1="154.852" y1="317.855" x2="138.069" y2="301.073" gradientTransform="matrix(1.0024 0 0 -.9907 -129.783 329.876)">
                            <stop offset="0" stop-color="#00A0FF" />
                            <stop offset=".007" stop-color="#00A1FF" />
                            <stop offset=".26" stop-color="#00BEFF" />
                            <stop offset=".512" stop-color="#00D2FF" />
                            <stop offset=".76" stop-color="#00DFFF" />
                            <stop offset="1" stop-color="#00E3FF" />
                          </linearGradient>
                          <path fill="url(#i)" d="M16.348 33.97c-.235-.247-.37-.63-.37-1.125V15.152c0-.496.135-.88.37-1.125l.058-.054 9.914 9.91v.234l-9.914 9.91-.058-.058z" />
                        </g>
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <linearGradient id="j" gradientUnits="userSpaceOnUse" x1="164.456" y1="308.737" x2="140.26" y2="308.737" gradientTransform="matrix(1.0024 0 0 -.9907 -129.783 329.876)">
                            <stop offset="0" stop-color="#FFE000" />
                            <stop offset=".409" stop-color="#FFBD00" />
                            <stop offset=".775" stop-color="#FFA500" />
                            <stop offset="1" stop-color="#FF9C00" />
                          </linearGradient>
                          <path fill="url(#j)" d="M29.62 20.578l-3.3 3.305v.234l3.305 3.305.074-.043 3.913-2.228c1.117-.632 1.117-1.672 0-2.308L29.7 20.62l-.08-.042z" />
                        </g>
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <linearGradient id="k" gradientUnits="userSpaceOnUse" x1="150.609" y1="313.622" x2="127.854" y2="290.868" gradientTransform="matrix(1.0024 0 0 -.9907 -129.783 329.876)">
                            <stop offset="0" stop-color="#FF3A44" />
                            <stop offset="1" stop-color="#C31162" />
                          </linearGradient>
                          <path fill="url(#k)" d="M29.7 20.62L26.32 24l-9.972-9.973c.37-.39.976-.437 1.664-.047L29.7 20.62" />
                        </g>
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <linearGradient id="l" gradientUnits="userSpaceOnUse" x1="136.617" y1="318.012" x2="146.781" y2="307.848" gradientTransform="matrix(1.0024 0 0 -.9907 -129.783 329.876)">
                            <stop offset="0" stop-color="#32A071" />
                            <stop offset=".069" stop-color="#2DA771" />
                            <stop offset=".476" stop-color="#15CF74" />
                            <stop offset=".801" stop-color="#06E775" />
                            <stop offset="1" stop-color="#00F076" />
                          </linearGradient>
                          <path fill="url(#l)" d="M29.7 27.38L18.01 34.02c-.688.386-1.293.34-1.664-.05L26.32 24l3.38 3.38z" />
                        </g>
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <defs>
                            <filter id="m" filterUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48">
                              <feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" />
                            </filter>
                          </defs>
                          <mask maskUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48" id="p">
                            <g filter="url(#m)">
                              <defs>
                                <path id="n" d="M-4.7-3.126h124v48h-124z" />
                              </defs>
                              <clipPath id="o">
                                <use xlink:href="#n" overflow="visible" />
                              </clipPath>
                              <path clip-path="url(#o)" fill-opacity=".2" d="M7.818 40.254h98.963V1.494H7.82v38.76z" />
                            </g>
                          </mask>
                          <g mask="url(#p)">
                            <defs>
                              <path id="q" d="M-15.685-5.722h155.37v59.444h-155.37z" />
                            </defs>
                            <clipPath id="r">
                              <use xlink:href="#q" overflow="visible" />
                            </clipPath>
                            <g clip-path="url(#r)">
                              <pattern id="t" xlink:href="#s" patternTransform="matrix(1.253 0 0 -1.2384 1931.852 -18664.836)" />
                              <path fill="url(#t)" d="M0 0h124v48H0V0z" />
                            </g>
                          </g>
                        </g>
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <defs>
                            <filter id="u" filterUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48">
                              <feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" />
                            </filter>
                          </defs>
                          <mask maskUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48" id="x">
                            <g filter="url(#u)">
                              <defs>
                                <path id="v" d="M-4.7-3.126h124v48h-124z" />
                              </defs>
                              <clipPath id="w">
                                <use xlink:href="#v" overflow="visible" />
                              </clipPath>
                              <path clip-path="url(#w)" fill-opacity=".12" d="M7.818 40.254h98.963V1.494H7.82v38.76z" />
                            </g>
                          </mask>
                          <g mask="url(#x)">
                            <defs>
                              <path id="y" d="M-15.685-5.722h155.37v59.444h-155.37z" />
                            </defs>
                            <clipPath id="z">
                              <use xlink:href="#y" overflow="visible" />
                            </clipPath>
                            <g clip-path="url(#z)">
                              <pattern id="B" xlink:href="#A" patternTransform="matrix(1.253 0 0 -1.2384 1931.852 -18664.836)" />
                              <path fill="url(#B)" d="M0 0h124v48H0V0z" />
                            </g>
                          </g>
                        </g>
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <defs>
                            <filter id="C" filterUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48">
                              <feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" />
                            </filter>
                          </defs>
                          <mask maskUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48" id="F">
                            <g filter="url(#C)">
                              <defs>
                                <path id="D" d="M-4.7-3.126h124v48h-124z" />
                              </defs>
                              <clipPath id="E">
                                <use xlink:href="#D" overflow="visible" />
                              </clipPath>
                              <path clip-path="url(#E)" fill-opacity=".12" d="M7.818 40.254h98.963V1.494H7.82v38.76z" />
                            </g>
                          </mask>
                          <g mask="url(#F)">
                            <defs>
                              <path id="G" d="M-15.685-5.722h155.37v59.444h-155.37z" />
                            </defs>
                            <clipPath id="H">
                              <use xlink:href="#G" overflow="visible" />
                            </clipPath>
                            <g clip-path="url(#H)">
                              <pattern id="J" xlink:href="#I" patternTransform="matrix(1.253 0 0 -1.2384 1931.852 -18664.836)" />
                              <path fill="url(#J)" d="M0 0h124v48H0V0z" />
                            </g>
                          </g>
                        </g>
                        <g transform="matrix(1.253 0 0 -1.2384 -9.796 49.85)">
                          <defs>
                            <filter id="K" filterUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48">
                              <feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" />
                            </filter>
                          </defs>
                          <mask maskUnits="userSpaceOnUse" x="0" y="-48" width="123.999" height="48" id="N">
                            <g filter="url(#K)">
                              <defs>
                                <path id="L" d="M-4.7-3.126h124v48h-124z" />
                              </defs>
                              <clipPath id="M">
                                <use xlink:href="#L" overflow="visible" />
                              </clipPath>
                              <path clip-path="url(#M)" fill-opacity=".25" d="M7.818 40.254h98.963V1.494H7.82v38.76z" />
                            </g>
                          </mask>
                          <g mask="url(#N)">
                            <defs>
                              <path id="O" d="M-15.685-5.722h155.37v59.444h-155.37z" />
                            </defs>
                            <clipPath id="P">
                              <use xlink:href="#O" overflow="visible" />
                            </clipPath>
                            <g clip-path="url(#P)">
                              <pattern id="R" xlink:href="#Q" patternTransform="matrix(1.253 0 0 -1.2384 1931.852 -18664.836)" />
                            </g>
                          </g>
                        </g>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>

		
		<?php
		return $html = ob_get_clean();
	}

	function pr27_export_csv() {
//		if ( ! wp_verify_nonce( $_POST['pr27_export_csv_nonce'], 'pr27_export_csv' ) ) {
//			die( 'Invalid nonce.' . var_export( $_POST, true ) );
//		}

		global $wpdb;

		$wpdb->show_errors();

		$table_name = $wpdb->prefix . 'psf';

		// Build your query
		$results = $wpdb->get_results( "SELECT `Name`, `Email`, `Age`,`Gender`,`Country`,`City`,`refferal_link`,`utm_source`,`created_date` FROM $table_name" );

		// Process report request
		if ( ! $results ) {
			$error = $wpdb->print_error();
			die( "The following error was found: $error" );
		} else {
			// Prepare our csv download

			// Set header row values
			$output_filename = 'Subscriptions.csv';
			$output_handle   = @fopen( 'php://output', 'w' );

			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: text/csv' );
			header( 'Content-Disposition: attachment; filename=' . $output_filename );
			header( 'Expires: 0' );
			header( 'Pragma: public' );

			$first = true;
			// Parse results to csv format
			foreach ( $results as $row ) {

				// Add table headers
				if ( $first ) {
					$titles = array();
					foreach ( $row as $key => $val ) {
						$titles[] = $key;
					}
					fputcsv( $output_handle, $titles );
					$first = false;
				}

				$leadArray = (array) $row; // Cast the Object to an array
				// Add row to file
				fputcsv( $output_handle, $leadArray );
			}

			// Close output file stream
			fclose( $output_handle );

			die();
		}
	}

}

add_action( 'plugins_loaded', function () {
	PearSubscriptionForm::get_instance();
} );

register_activation_hook( __FILE__, array( 'PearSubscriptionForm', 'psf_install' ) );
add_action( 'plugins_loaded', array( 'PearSubscriptionForm', 'psf_update_db_check' ) );
add_shortcode( 'PearSubForm', array( 'PearSubscriptionForm', 'form' ) );


if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Subscriptions_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Subscription', 'pear' ), //singular name of the listed records
			'plural'   => __( 'Subscriptions', 'pear' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );

	}

	/**
	 * Retrieve subscriber’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_subscriptions( $per_page = 10, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}psf";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";

		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Delete a subscriber record.
	 *
	 * @param int $id subscriber ID
	 */
	public static function delete_subscription( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}psf",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}psf";

		return $wpdb->get_var( $sql );
	}

	/** Text displayed when no subscriber data is available */
	public function no_items() {
		_e( 'No subscriptions.', 'pear' );
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		// create a nonce
		$delete_nonce = wp_create_nonce( 'psf_delete_subscriber' );

		$title = '<strong>' . $item['Name'] . '</strong>';

		$actions = [
//			'delete' => sprintf( '<a href="?page=%s&action=%s&subscriber=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'Email':
			case 'Name':
			case 'Age':
			case 'Location':
			case 'Country':
			case 'City':
			case 'Gender':
			case 'refferal_link':
			case 'utm_source':
			case 'unsubscribe':
			case 'created_date':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
//		return sprintf(
//			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
//		);
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'       => '<input type="checkbox" />',
			'name'     => __( 'Name', 'pear' ),
			'Email'    => __( 'Email', 'pear' ),
			'Age'      => __( 'Age', 'pear' ),
			'Gender' => __( 'Gender', 'pear' ),
			'Country' => __( 'Country', 'pear' ),
			'City' => __( 'City', 'pear' ),
			'refferal_link' => __( 'Refferal Link', 'pear' ),
			'utm_source' => __( 'Utm Source', 'pear' ),
			'unsubscribe' => __( 'Unsubscribe', 'pear' ),
			'created_date'=> __( 'Created Date', 'pear' )
		];

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'Name'     => array( 'Name', true ),
			'Email'    => array( 'Email', false ),
			'Age'      => array( 'Age', false ),
			'Country' => array( 'Country', false ),
			'Gender' => array( 'Gender', true ),
			'City' => array( 'City', false ),
			'refferal_link' => array( 'refferal_link', false ),
			'unsubscribe' => array( 'unsubscribe', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
//			'bulk-delete' => 'Delete'
		];

		return $actions;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

	    if(isset($_GET['num'])){
        	$num = $_GET['num'];
        }
        else{
        	$num = 10;
        }
		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'subscriptions_per_page', $num );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );


		$this->items = self::get_subscriptions( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'psf_delete_subscriber' ) ) {
				die( 'Not authorized!' );
			} else {
				self::delete_subscription( absint( $_GET['subscriber'] ) );

				wp_redirect( esc_url( add_query_arg() ) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_subscriber( $id );

			}

			wp_redirect( esc_url( add_query_arg() ) );
			exit;
		}
	}
}
