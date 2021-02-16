<?php
/**
Theme specific styles and scripts
	wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
	wp_enqueue_style( $handle, $src, $deps, $ver );
*/ 
wp_enqueue_script('cbv-google.js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBaRQsAJCZyyD6MbCg0jB_0sdLGEOxt97Y', array(), '1.0.0', true);

?>