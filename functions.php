<?php 

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

?>