<?php
/*
** adding necessarey files
*/

function simple_polling_admin_style_script_added() {

    wp_enqueue_style('wooLiveSaleAdminFilesMainStyle', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_script('wooLiveSaleAdminFilesCutomLogic', plugins_url('/js/logic.js',__FILE__ ));
}
add_action('admin_enqueue_scripts', 'simple_polling_admin_style_script_added');


/*Theme customize */
add_action( 'admin_menu', 'simple_polling_admin_main_page_display' );

/**
 * Adds a new settings page under Setting menu
*/

function simple_polling_admin_main_page_display() {
    //only editor and administrator can add a polling
    if( current_user_can('editor') || current_user_can('administrator') ) {
    add_options_page( __( 'Simple Polling' ), __( 'Simple Polling' ), 'manage_options', 'spSimplePolling', 'simple_polling_main_page_display_home' );
}
}

/**
* Tabs Method 
*/
function simple_polling_show_tabs_list( $current = 'first' ) {
    $tabs = array(
        'first'   => __( 'Add election', 'plugin-textdomain' ), 
       
        );
    $html = '<h2 class="wooLiveSalenav-tabnav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . esc_html($class) . '" href="?page=spSimplePolling&tab=' . esc_html($tab) . '">' . esc_html($name) . '</a>';
    }
    $html .= '</h2>';
    echo $html ;
}

function simple_polling_main_page_display_home(){
    ?>
    <div class="cont-p-dashboard">
        <div class="post_like_dislike_header wrap">Dashboard<span>Contact me for further customization, starting from $5. 
            <a href="https://www.fiverr.com/aliali44">Contact</a>
        </span>
    </div>
    <?php

    // ================== Tabs ========================//
     $tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'first';
     simple_polling_show_tabs_list( $tab );


   // =========================== Tab 1 ========================//
    if ( $tab == 'first' ) {
        ?>
        <div class="woo-live-saleTabs woo-live-sale-firstTab">
        	<!--First tab -->
        	<div class="setting-left-sp">
        		<div class="list-sp list-sp-left">
        			
        			<?php

        			//upload polling images
        			simple_polling_media_selector_settings_page_callback(); 
        			
        			?>
        			<!-- uploading polling names -->
        			<br><br>
        			<label>Enter first person name</label>
        			<input type="text" name="" class="names-list first-name">

        			<label>Enter second person name</label>
        			<input type="text" name="" class="names-list last-name">

        			<label>Enter polling title</label><br>
        			<i>*You can leave empty if you do not want to enter title</i>
        			<input type="text" name="" class="names-list title-name">
        			<button class="button-primary update-names-and-titles">Submit Names and Title</button>

        		</div>
        		<div class="list-sp list-polling-right">
                    <div class="cont-polling">
                    <h1 class="polling-preview-1">Polling Preview</h1>
                    <?php
                    // Getting image id from the database
                    global $wpdb;

                    // table name
                    $db_table_name = $wpdb->prefix . 'simple_polling'; 
                    $results = $wpdb->get_results( "SELECT * FROM $db_table_name");  
                    if(!empty($results)){

                        //polling counter
                        $counter_polling = 0;
                        //getting result 
                        foreach($results as $row){

                    //showing title
                    if($counter_polling == 0){
                    ?>
        			

                    <h3 class="polling-title"><?php echo esc_html($row->polling_title); }?></h3>


        			<div class="poll-left">
        			<div style="height:180px; width:180px; background-size: cover;background-position: center;background-image: url(<?php echo wp_get_attachment_url($row->img_id); ?>);" >
        			</div>
        			
        			<!--Polling counter check -->
        			<?php 
        			if ($counter_polling == 0) {
        				?>
                        <!--First person name -->
        				<h3 class="person-name p-name-1"><?php echo esc_html($row->person_names) ?></h3>
        				<input type="radio" name="polling" class="radio-select">
        				<?php
                        $counter_polling++;
        			}
        			else{
        				?>
                        <!--Second person name -->
        				<h3 class="person-name p-name-2"><?php echo esc_html($row->person_names) ?></h3>
        				<input type="radio" name="polling" class="radio-select">
        				<?php
        			}
        			?>
        			</div>

        			<?php
        				}
					}                       	
					?>

        		</div>	
               
        		<button class="button-primary delete-current-polling">Delete Current Polling</button>
        		<div class="limitations">
        			<b>Limitations:</b><br>
        			<i>You only can create one election polling at a time.
        		</div>
                <div class="short-code-div">Short Code: <b>[show_simple_polling]</b></div>
        		</div>
        		
        	</div>
        	<div class="display-right-sp">
        		
        	</div>
        </div>

        <?php
    }
    // =========================== Tab 2 ========================//
     elseif($tab == 'second' ){
        ?>
        <div class="woo-live-saleTabs woo-live-sale-secondTab">
        </div>
        <?php

     }
     // =========================== Tab 3 ========================//
     else{
        ?>
        <div class="woo-live-saleTabs woo-live-sale-thirdTab">
        </div>

        <?php
        
     }
}

function simple_polling_media_selector_settings_page_callback() {
	// Save attachment ID
	$i = 0;
	if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
		update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) );

	//first image save increment
	$i++;
	endif;
	wp_enqueue_media();
	?><form method='post'>
		<div class='image-preview-wrapper'>
			<h3>Image preview</h3>
			<img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>' height='100'>
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php 
		//button text for the first image
		if($i == 0)
			{ 
				?>
				Upload first image
				<?php
			}

		//second image button text 
		if($i == 1){ 
			?>
			Upload second image
			<?php
		}


		?>" />
		<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
		<input type="submit" name="submit_image_selector" value="Save image" class="button-primary sumbit-names-titles">
	</form><?php
	
	//saving first image
	if ($i==1) {

	// image id
	$imgId = get_option( 'media_selector_attachment_id' ); 
	
	global $wpdb;

	// table name
	$db_table_name = $wpdb->prefix . 'simple_polling';  

	//inserting image id
	$wpdb->insert( $db_table_name, array( 'img_id' => $imgId ) ); 

	//deleting current option 
	delete_option( 'media_selector_attachment_id' ); 

	}

}
add_action( 'admin_footer', 'simple_polling_media_selector_print_scripts' );
function simple_polling_media_selector_print_scripts() {
	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
	?>
	<script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
			jQuery('#upload_image_button').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#image_attachment_id' ).val( attachment.id );
					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});
					// Finally, open the modal
					file_frame.open();
			});
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});
	</script><?php
}



//Ajax call for updating person names and titles 

add_action('wp_ajax_update_person_names', 'simple_polling_ajax_call_update_names');

function simple_polling_ajax_call_update_names(){

	global $wpdb;

	// table name
	$db_table_name = $wpdb->prefix . 'simple_polling'; 

    //Received veriables
    if (isset($_POST['firstPersonName'])) {
        $first_person_name   = sanitize_text_field($_POST['firstPersonName']);
        $second_person_name  = sanitize_text_field($_POST['secondPersonName']);
        $polling_title       = sanitize_text_field($_POST['pollingTitle']);
    

    //getting table column id

    $results = $wpdb->get_results( "SELECT * FROM $db_table_name");  
    if(!empty($results)){

    //polling counter
    $counter_cols = 0;
    //getting the value of the first column for updating value
    foreach($results as $row){
        if ($counter_cols == 0) {
            $col_id = $row->id;
            $counter_cols++;
        }
    }
    }
    
    
    //adding first name and title
    $wpdb->update( 
    $db_table_name, 
    array( 
        'person_names' => $first_person_name,  // string
        'polling_title' => $polling_title   // integer (number) 
    ), 
    array( 'id' => $col_id )
    );

    //adding second name and title
    $wpdb->update( 
    $db_table_name, 
    array( 
        'person_names' => $second_person_name,  // string
        'polling_title' => $polling_title   // integer (number) 
    ), 
    array( 'id' => $col_id+1 )
    );
   

}//end of checking is set function

exit();
}


//Ajax call for deleting the polling 

add_action('wp_ajax_delete_polling', 'simple_polling_delete_polling');

function simple_polling_delete_polling(){
    //delete access for only editor and adminstrator
    if( current_user_can('editor') || current_user_can('administrator') ) {
	global $wpdb;
	// table name
	$db_table_name = $wpdb->prefix . 'simple_polling';
	$wpdb->query( "DELETE  FROM {$db_table_name}" );
}
	exit();
	}