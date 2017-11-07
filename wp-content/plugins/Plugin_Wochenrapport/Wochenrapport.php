<?php
/*
Plugin Name: Wochenrapport
Plugin URI: http://example.com
Description: Wochenrapport für Raess Frauchiger
Version: 1.0
Author: Joel Gratwohl

*/
    //
    // the plugin code will go here..
    // 
    // 
    
 
    // This function will run when the plugin is activated by the user.
    register_activation_hook( __FILE__, 'wochenrapport_create_db' );
    
    //Test2
    function wochenrapport_create_db() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_clients = $wpdb->prefix . 'clients';

	$sql_1 = "CREATE TABLE $table_clients 
                (clientID int(10) NOT NULL AUTO_INCREMENT,
                firmname varchar(255) NOT NULL,
                firstname varchar(255) NOT NULL,
                name varchar(255) NOT NULL,
                adress varchar(255) NOT NULL,
                plz int(4) NOT NULL,
                city varchar(255) NOT NULL,
                phone varchar(255) NOT NULL,
                PRIMARY KEY (clientID))
                $charset_collate;";
        
        $table_hours = $wpdb->prefix . 'hours';

	$sql_2 = "CREATE TABLE $table_hours
                (hoursID int(10) NOT NULL AUTO_INCREMENT,
                `date` date NOT NULL,
                KW int(2) NOT NULL,
                amountHours double NOT NULL,
                workElements varchar(255) NOT NULL,
                usedMaterial varchar(255) NOT NULL,
                orderDone tinyint(1) NOT NULL,
                usersuserID int(10) NOT NULL,
                clientsclientID int(10) NOT NULL,
                PRIMARY KEY (hoursID))
                $charset_collate;";
        
        $table_benutzer = $wpdb->prefix . 'benutzer';

	$sql_3 = "CREATE TABLE $table_benutzer 
                (userID int(10) NOT NULL AUTO_INCREMENT,
                email varchar(255) NOT NULL,
                password varchar(255) NOT NULL,
                admin tinyint(1) NOT NULL,
                firstName varchar(255) NOT NULL,
                name varchar(255) NOT NULL,
                address varchar(255) NOT NULL,
                plz int(4) NOT NULL,
                city varchar(255) NOT NULL,
                phone varchar(255) NOT NULL,
                PRIMARY KEY (userID))
                $charset_collate;";
        
        $sql_4 = "ALTER TABLE $table_hours
                ADD INDEX FKhours858665 (usersuserID),
                ADD CONSTRAINT FKhours858665 FOREIGN KEY (usersuserID)
                REFERENCES users (userID)
                $charset_collate;";
        
        $sql_5 = "ALTER TABLE $table_hours 
                ADD INDEX FKhours919319 (clientsclientID),
                ADD CONSTRAINT FKhours919319
                FOREIGN KEY (clientsclientID)
                REFERENCES clients (clientID);
                $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql_1);
        dbDelta( $sql_2);
        dbDelta( $sql_3);
        dbDelta( $sql_4);
        dbDelta( $sql_5);
}
	
	function html_form_code() {
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Your Name (required) <br />';
    echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Email (required) <br />';
    echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Subject (required) <br />';
    echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Message (required) <br />';
    echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Send"/></p>';
    echo '</form>';
}

	
function deliver_data_to_db(){
    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {
    $name    = sanitize_text_field( $_POST["cf-name"] );
    $email   = sanitize_email( $_POST["cf-email"] );
    $subject = sanitize_text_field( $_POST["cf-subject"] );
    $message = esc_textarea( $_POST["cf-message"] );
    
    global $wpdb;
    
    $wpdb->insert($wpdb->prefix . 'wochenrapport', array(
                'name'        => $name,
                'email'       => $email,
                'comment'     => $message
            )
        );
}
}

// D'FUNKTION "deliver_mail()" NED BEACHTE ..... 

/*function deliver_mail() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = sanitize_text_field( $_POST["cf-subject"] );
        $message = esc_textarea( $_POST["cf-message"] );

        // get the blog administrator's email address
        $to = 'joel.gratwohl@gmail.com';

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}
*/
	function cf_shortcode() {
    ob_start();
    deliver_data_to_db();
    html_form_code();

    return ob_get_clean();
}

	add_shortcode( 'sitepoint_contact_form', 'cf_shortcode' );
	
	
?>

