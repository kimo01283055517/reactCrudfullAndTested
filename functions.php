<?php 
global $wpdb;
function enqueue_my_react_app() {
    
    
    
    wp_enqueue_script(
        'my-react-app',
        get_theme_file_uri('/build/index.js') , // Adjust path if necessary
        ['wp-element'], // Load WordPress's React and ReactDOM
        0,
        true
    );
    wp_enqueue_style( 'mystyle',get_theme_file_uri('/build/index.css') );

    wp_localize_script('my-react-app', 'my', [
        'root'  => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest') // إنشاء Nonce

    ]);

 
}
add_action('wp_enqueue_scripts', 'enqueue_my_react_app');


 register_activation_hook(__FILE__, 'ctp_create_custom_table');
register_deactivation_hook(__FILE__, 'ctp_delete_custom_table');
   
   

   // Create a custom table on plugin activation
   function ctp_create_custom_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_table';

    // SQL to create the custom table
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        age INT(3) NOT NULL,
        hobby VARCHAR(255) NOT NULL,
        city VARCHAR(255) NOT NULL,
        phone_number VARCHAR(15) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Delete the custom table on plugin deactivation
function ctp_delete_custom_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_table';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

add_filter( 'template_include','redirest_page_as_i_need' );

function redirest_page_as_i_need($template){
    if(is_page('play')){
        echo 'this test';
        return __FILE__.'template/play.php';
    }
    var_dump($template);
    return $template;
}

//--------------------to select data to plugin ------------------------------

add_action('rest_api_init', function () {
    register_rest_route('test/v1', '/data', array(
        'methods' => 'GET',
        'callback' => 'custom_get_data',
       
    ));
});

function custom_get_data($request) {
    // Prepare data to return
    global $wpdb;
    $res = $wpdb->get_results('SELECT * FROM wp_my_table');
    

    return rest_ensure_response($res);
}

//--------------------to insert data to plugin ------------------------------
add_action('rest_api_init', function () {
    register_rest_route('test/v1', '/insert', [
        'methods'  => 'POST', // HTTP method
        'callback' => 'handle_insert_data', // Callback function to handle the request
        
    ]);
});

function handle_insert_data($request) {
    // Get data from the request
    // ملاحظة الريكوست لوحدة لايخرج بيانات  echo $request
    //  لازم يكون $request['age']
    // here send request data with two param name and age 
    // and sanitize both to send at last to database
    
    //Validate and sanitize the input\
    //if( is_user_logged_in(  )){echo 'this use can edit boys';}else
    //{die( 'this can not edit the boys');}
    //die used to stop the code from continue
    $name = sanitize_text_field($request['name'] ?? '');
     $age = sanitize_textarea_field($request['age'] ?? '');
     $soso = sanitize_textarea_field($request['soso'] ?? '');
     
     
     if (empty($name) || empty($age)|| empty($soso)) {
        return new WP_Error('missing_data', 'Title or age is missing.', ['status' => 400]);
     }

     
global  $wpdb ;
    $res = $wpdb->insert('wp_my_table',array('name'=>$name , 'age'=>$age , 'soso'=>$soso));
//     // Insert the$request as a post (example)
//     $post_id = wp_insert_post([
//     'post_title'    => $name,
//     'post_content'  => $age,
//     'post_status'   => 'publish',  // Post status ('publish', 'draft', etc.)
//     'post_type'     => 'boy',     // Post type ('post', 'page', or custom post type)
//     'meta_input'    => ['parent' => 19,
        
//     ],
// ]);
$resdata = $wpdb->get_results('SELECT * from wp_my_table ORDER BY id DESC LIMIT 1');
//     'post_type'=>'boy',
//     'p'=>$post_id
// ));
// $res =[];
  

//     if (is_wp_error($post_id)) {
//         return new WP_Error('insert_failed', 'Failed to insert the$request.', ['status' => 500]);
//     }

    return  $resdata;
}
//-------------------------------------------------------
  
?>