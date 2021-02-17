<?php 

function deploy_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar Widget', 'ash' ),
		'id'            => 'post-widget',
		'description'   => __( 'Add widgets here to appear in your blog page.', 'ash' ),
		'before_widget' => '<aside><div class="sidebar-widget">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'deploy_widgets_init' );
