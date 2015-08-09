<?php
/*
Plugin Name: Simple Blog Authors Widget
Plugin URI: http://metodiew.com/projects/simple-blog-authors-widget/
Description: Provides a widget to list blog authors, including gravatars, post counts, and bios
Version: 1.5.0
Author: Stanko Metodiev
Contributors: metodiew
Author URI: http://metodiew.com
*/

if ( ! defined( 'SBAW_VERSION' ) ) {
	define( 'SBAW_VERSION', '1.5.0' );
}

if ( ! defined( 'SBAW_TEXT_DOMAIN' ) ) {
	define( 'SBAW_TEXT_DOMAIN', 'sbaw' );
}

/**
 * Simple Blog Authors Widget Class
 */
class Metodiew_Simple_Authors_Widget extends WP_Widget {

    /** 
     * constructor 
    */
    function __construct() {
		parent::__construct(
			'metodiew_simple_authors_widget',
			__( 'Simple Authors Widget', SBAW_TEXT_DOMAIN ),
			array( 'description' => __( 'Simple Authors Widget', SBAW_TEXT_DOMAIN ), )
		);
		
		// Register SBAW Widget
		add_action( 'widgets_init', array( $this, 'register_metodiew_simple_authors_widget' ) );
		
		// Enqueue Styles and Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'sbaw_enqueue_scripts' ) );
    }
	
	// register Foo_Widget widget
	function register_metodiew_simple_authors_widget() {
	    register_widget( 'Metodiew_Simple_Authors_Widget' );
	}
	
	function sbaw_enqueue_scripts() {
		wp_enqueue_script( 'sbaw-main', plugins_url( '/js/main.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_localize_script( 'sbaw-main', 'sbawAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );  
	}

    /** 
     * @see WP_Widget::widget 
    */
    public function widget( $args, $instance ) {
        extract( $args );
		global $wpdb;
		
      	$title = apply_filters( 'widget_title', $instance['title'] );
		$gravatar = ! empty ( $instance['gravatar'] ) ? $instance['gravatar'] : '';
		$count = ! empty( $instance['count'] ) ? $instance['count'] : '';
		$dropdown = ! empty ( $instance['dropdown'] ) ? $instance['dropdown'] : ''; 
		
        echo $before_widget;
        
        if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
        }
		
		$authors = get_users( array( 
			'who' => 'authors', 
			'number' => 99999
		) );
		
		if ( ! empty( $dropdown ) && $dropdown == 1 ) {
			echo '<select id="sbaw-select">';
			printf( __( '<option value="0">Select Author</option>', SBAW_TEXT_DOMAIN ) );
			
			foreach ( $authors as $author ) {
				$posts_count = count_user_posts( $author->ID );
				
				if ( ! empty( $posts_count ) && $posts_count > 0 ) {
					$author_info = get_userdata( $author->ID );
					
					echo '<option value="' .get_author_posts_url( $author->ID ) . '">' . $author_info->display_name; 
					if( $count ) {
						echo ' (' . $posts_count . ')';
					}
					echo '</option>';
				}
			}
			echo '</select>';
			
			
		} else {

			echo '<ul class="sbaw_authors">';
				foreach ( $authors as $author ) {
					$posts_count = count_user_posts( $author->ID );
						
					if ( ! empty( $posts_count ) && $posts_count >= 1 ) {
						$author_info = get_userdata( $author->ID );
						echo '<li class="sbaw_author">';
							if ( ! empty( $gravatar ) && $gravatar == 1 ) {
								echo '<div>';
									echo get_avatar( $author->ID, 40 );
								echo '</div>';
							}
							
							echo '<a href="' . get_author_posts_url( $author->ID ) .'" title="View ' . $author_info->display_name . ' archive">';
								echo $author_info->display_name;
								
								if( $count ) {
									echo ' (' . $posts_count . ')';
								}
							echo '</a>';
						echo '</li>';
					}
				}
			
			echo '</ul>';
		}

		echo $after_widget;
    }
	
    /** 
     * @see WP_Widget::form 
    */
    public function form( $instance ) {

    	$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$gravatar = isset( $instance['gravatar'] ) ? esc_attr( $instance['gravatar'] ) : '';
		$count = isset( $instance['count'] ) ? esc_attr( $instance['count']) : '';
		$dropdown = isset( $instance['dropdown'] ) ? esc_attr( $instance['dropdown'] ) : '';
        ?>
		<p>
        	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', SBAW_TEXT_DOMAIN ); ?></label> 
          	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

		<p>
         	<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="checkbox" value="1" <?php checked( '1', $count ); ?>/>
          	<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Display Post Count?', SBAW_TEXT_DOMAIN ); ?></label> 
        </p>

		<p>
          	<input id="<?php echo $this->get_field_id( 'gravatar' ); ?>" name="<?php echo $this->get_field_name( 'gravatar' ); ?>" type="checkbox" value="1" <?php checked( '1', $gravatar ); ?>/>
          	<label for="<?php echo $this->get_field_id( 'gravatar' ); ?>"><?php _e( 'Display Author Gravatar?', SBAW_TEXT_DOMAIN ); ?></label> 
        </p>
        <p>
        	<input id="<?php echo $this->get_field_id( 'dropdown' ); ?>" name="<?php echo $this->get_field_name( 'dropdown' ); ?>" type="checkbox" value="1" <?php checked( '1', $dropdown ); ?>/>
        	<label for="<?php echo $this->get_field_id( 'dropdown' ); ?>"><?php _e( 'Display authors list in a dropdown?', SBAW_TEXT_DOMAIN ); ?></label>
        </p>
        <?php 
    }

	/** 
     * @see WP_Widget::update 
    */
    public function update( $new_instance, $old_instance ) {
    	
		$instance = $old_instance;
		$instance['title'] = isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['gravatar'] = isset( $new_instance['gravatar'] ) ? strip_tags( $new_instance['gravatar'] ) : '';
		$instance['count'] = isset( $new_instance['count'] ) ? strip_tags( $new_instance['count'] ) : '';
		$instance['dropdown'] = isset( $new_instance['dropdown'] ) ? strip_tags( $new_instance['dropdown'] ) : '';
		
		return $instance;
    }

} // END of Class Metodiew_Simple_Authors_Widget

$metodiew_simple_authors_widget = new Metodiew_Simple_Authors_Widget();