<?php

/*
  Plugin Name: Simple Polling
  Plugin URI: https://www.fiverr.com/aliali44
  Description: Simple polling widget for your WordPress website
  Version: 1.0
  Author: Zeshan Abdullah
  Author URI: https://www.fiverr.com/aliali44
 */

// Exit if accessed directly 
  if (!defined('ABSPATH'))
    exit;

include_once 'admin/admin.php';
/**
* creating a table for saving button update details
* on activating the plugin
*/


function simple_polling_createing_default_table()
{      
 
  global $wpdb; 
  $db_table_name = $wpdb->prefix . 'simple_polling';  // table name
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $db_table_name (
    id int(11) NOT NULL auto_increment,
    img_id varchar(3),
      person_names varchar(80),
    polling_title varchar(200),
    polling_count int DEFAULT 0,
    UNIQUE KEY id (id)
    ) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
add_option( 'test_db_version', $test_db_version );
} 

register_activation_hook( __FILE__, 'simple_polling_createing_default_table' );


//enqueues css and js files
add_action('wp_enqueue_scripts', 'simple_polling_enquee_style_and_script');
function simple_polling_enquee_style_and_script() {
    wp_enqueue_style('like', plugins_url('/assets/css/style.css', __FILE__));
    wp_enqueue_script('like', plugins_url('/assets/js/logic.js', __FILE__), array('jquery'), '1.0', true);


    wp_localize_script('like', 'simple_polling_aj_var', array(
        'simple_polling_ajax_url' => admin_url('admin-ajax.php')
        ));
}
//Ajax call receive
add_action('wp_ajax_nopriv_simple_polling_ajax_call_add_vote', 'simple_polling_ajax_call_add_vote');
add_action('wp_ajax_simple_polling_ajax_call_add_vote', 'simple_polling_ajax_call_add_vote');

//ajax call for updating the person count and showing the result
function simple_polling_ajax_call_add_vote() {
  if (isset($_POST['updatePersonVal'])) {
    //senitizing and getting value
    $update_person = sanitize_text_field($_POST['updatePersonVal']);

    //getting last id
    global $wpdb;

    // table name
    $db_table_name = $wpdb->prefix . 'simple_polling'; 
    $results = $wpdb->get_results( "SELECT * FROM $db_table_name");  
    if(!empty($results)){

    //polling counter
    $counter_cols = 0;
    //getting the value of the first column for updating value
    foreach($results as $row){
        if ($counter_cols == 0) {
            $col_id = $row->id;
            $counter_cols++;

            //get first person current value
            $first_current_val = $row->polling_count;
            if ($update_person == "person-1-check") {
              $first_current_val = $first_current_val + 1;
            }
            
          

        }
        else{
          //get second person current value
            $second_current_val = $row->polling_count;
            if ($update_person == "person-2-check") { 
            $second_current_val = $second_current_val +1;
          }
        }
    }
    }

    //sending received details to the database
    if ($update_person =="person-1-check") {
       $wpdb->update( 
    $db_table_name, 
    array( 
        'polling_count' => $first_current_val,  // string
    ), 
    array( 'id' => $col_id )
    );
    }
    elseif ($update_person =="person-2-check") {
       $wpdb->update( 
    $db_table_name, 
    array( 
        'polling_count' => $second_current_val,  // string

    ), 
    array( 'id' => $col_id+1 )
    );
    }

   
  echo $first_current_val.",".$second_current_val;
  }
  exit();
  }


// ===================== Simple Polling ==========================//
//[foobar]
function simple_polling_front_show( $atts ){
  ?>
  <div class="container-simple-polling">
    <div class="inner-polling-simple">
      <?php
      global $wpdb;

      // table name
      $db_table_name = $wpdb->prefix . 'simple_polling'; 
      $results = $wpdb->get_results( "SELECT * FROM $db_table_name");  
      if(!empty($results)){

      //polling counter
      $counter_polling = 0;
      $counter_person = 0;
      //getting result 
      foreach($results as $row){

      //showing title
      if($counter_polling == 0){
      ?>        
      <h3 class="polling-title">
        <?php echo esc_html($row->polling_title); 
        $counter_polling++;
      }
      ?></h3> <!--end of the title -->

      <div class="poll-left">
              <div style="height:140px; width:140px; margin-top:20px; background-size: cover;background-position: center;background-image: url(<?php echo wp_get_attachment_url($row->img_id); ?>);" >
              </div>
              
              <!--Polling counter check -->
              <?php 

              if ($counter_person == 0) {
                ?>
                        <!--First person name -->
                <h3 class="person-name p-name-1"><?php echo esc_html($row->person_names) ?></h3>
                <input type="radio" name="polling" class="radio-select" value="person-1-check">
                <?php
                $counter_person++;
              }
              else{
                ?>
                        <!--Second person name -->
                <h3 class="person-name p-name-2"><?php echo esc_html($row->person_names) ?></h3>
                <input type="radio" name="polling" class="radio-select" value="person-2-check">
                <?php
              }
              ?>
                </div>
      <?php
    }//end of result show
  } //end of empty result
  ?>
  <div class="clear-fix"></div>

  <button class="submit-vote">Vote</button>
    </div>
  </div>

  <?php
}
add_shortcode( 'show_simple_polling', 'simple_polling_front_show' );

