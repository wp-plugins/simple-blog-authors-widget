<?php
/*
Plugin Name: Simple Blog Authors Widget
Plugin URI: http://metodiew.com/projects/simple-blog-authors-widget/
Description: Provides a widget to list blog authors, including gravatars, post counts, and bios
Version: 1.0.3
Author: Stanko Metodiev
Contributors: metodiew
Author URI: http://metodiew.com
*/

/**
 * Simple Blog Authors Widget Class
 */
class Metodiew_Simple_Authors_Widget extends WP_Widget {

    /** 
     * constructor 
    */
    function metodiew_simple_authors_widget() {
        parent::WP_Widget( false, $name = 'Simple Authors Widget' );	
    }

    /** 
     * @see WP_Widget::widget 
    */
    function widget( $args, $instance ) {	
        extract( $args );
		global $wpdb;
		
      	$title = apply_filters( 'widget_title', $instance['title'] );
		$gravatar = $instance['gravatar'];
		$count = $instance['count'];

        echo $before_widget;
        
        if ( $title ) {
			echo $before_title . $title . $after_title;
        }
			
		echo '<ul class="sbaw_authors">';

			$authors = get_users( array( 
				'who' => 'authors', 
				'number' => 99999
			));

			foreach ( $authors as $author ) {
					
				$post_count = count_user_posts( $author->ID );
					
				if( $post_count >= 1 ) {

					$author_info = get_userdata( $author->ID );
						
					echo '<li class="sbaw_author">';

						if ( $gravatar ) {
							echo '<div>';
								echo get_avatar( $author->ID, 40 );
							echo '</div>';
						}
						
						echo '<a href="' . get_author_posts_url( $author->ID ) .'" title="View ' . $author_info->display_name . ' archive">';
							echo $author_info->display_name;
							
							if( $count ) {
								echo '(' . $post_count . ')';
							}
						echo '</a>';

					echo '</li>';
				}
			}
			
		echo '</ul>';

		echo $after_widget;
    }

    /** 
     * @see WP_Widget::update 
    */
    function update( $new_instance, $old_instance ) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['gravatar'] = strip_tags( $new_instance['gravatar'] );
		$instance['count'] = strip_tags( $new_instance['count'] );
        
		return $instance;
    }

    /** 
     * @see WP_Widget::form 
    */
    function form( $instance ) {	

    	$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$gravatar = isset( $instance['gravatar'] ) ? esc_attr( $instance['gravatar'] ) : '';
		$count = isset( $instance['count'] ) ? esc_attr( $instance['count']) : '';
        ?>
        
		<p>
        	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
          	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

		<p>
         	<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="checkbox" value="1" <?php checked( '1', $count ); ?>/>
          	<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Display Post Count?' ); ?></label> 
        </p>

		<p>
          	<input id="<?php echo $this->get_field_id( 'gravatar' ); ?>" name="<?php echo $this->get_field_name( 'gravatar' ); ?>" type="checkbox" value="1" <?php checked( '1', $gravatar ); ?>/>
          	<label for="<?php echo $this->get_field_id( 'gravatar' ); ?>"><?php _e( 'Display Author Gravatar?' ); ?></label> 
        </p>
        <?php 
    }

} // END of Class Metodiew_Simple_Authors_Widget

// register Recent Posts widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "metodiew_simple_authors_widget" );' ) );