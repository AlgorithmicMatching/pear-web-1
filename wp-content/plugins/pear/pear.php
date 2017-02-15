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
			<h1>Where science and math meet to find your perfect match</h1>
			<p class="mt0 mb32 join-com">Join our community to be among our beta testers or know when Pear will be available in
				your city</p>
			<div class="row join-form-container">
				<div class="col-xs-12 col-sm-3 col-sm-offset-2">
					<input id="name" class="input-boxed" type="text" placeholder="Name" required/>
				</div>
				<div class="col-xs-12 col-sm-3">
					<input id="email" class="input-boxed" type="text" placeholder="Email" required/>
				</div>
				<div class="col-xs-12 col-sm-1 select-container" style="z-index:10">
					<select id="age" class="select-age-home">
						<option></option>
					</select>
				</div>
				<input type="hidden" name="utm_source" id="source" value="<?php echo empty($_GET['utm_source']) ? "direct" : $_GET['utm_source']; ?>">

				<input type="hidden" name="refferal" id="refferal" value="<?php echo empty($_SERVER['HTTP_REFERER']) ? $_GET['utm_source'] : $_SERVER['HTTP_REFERER']; ?>">
				<input type="text" name="surname" style="display:none;">
				<?php echo wp_nonce_field( 'psf_form_submit', 'psf_nonce', true, false ); ?>
                <div id="psf-submit" class="button green shadow text-uppercase button-join col-sm-2 col-xs-12">
				<span class="join-text">Join now</span>
				<img class="loader inactive" src="<?php echo get_template_directory_uri(); ?>/dist/images/loader.svg"/>
			</div>
			</div>
			
			<div class="comng-soon">Coming soon to</div>
            <p class="coming-soon">
                 <img src="<?php echo get_template_directory_uri(); ?>/dist/images/soon-app-store.png"/>
                 <span class="seprator">&nbsp;</span>
                 <img src="<?php echo get_template_directory_uri(); ?>/dist/images/soon-google-play.svg"/ class="no-border">
            </p>
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
		$results = $wpdb->get_results( "SELECT `Name`, `Email`, `Age`, 'Gender', `Country`, 'City', 'refferal_link','utm_source','created_date' FROM $table_name" );

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
