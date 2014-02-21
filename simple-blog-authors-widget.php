<?php
/*
Plugin Name: Simple Blog Authors Widget
Plugin URI: http://pippinsplugins.com/simple-blog-authors-widget
Description: Provides a widget to list blog authors, including gravatars, post counts, and bios
Version: 1.0.2
Author: Pippin Williamson
Contributors: mordauk
Author URI: http://pippinsplugins.com
*/

/**
 * Authors Widget Class
 */
class pippin_simple_authors_widget extends WP_Widget {


    /** constructor */
    function pippin_simple_authors_widget() {
        parent::WP_Widget(false, $name = 'Simple Authors Widget');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
        extract( $args );
		global $wpdb;
		
      $title = apply_filters('widget_title', $instance['title']);
		$gravatar = $instance['gravatar'];
		$count = $instance['count'];
		
		if(!$size)
			$size = 40;

        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul>
							<?php

								$authors = get_users( array( 'who' => 'authors', 'number' => 99999) );

								foreach($authors as $author) {
									
									$post_count = count_user_posts($author->ID);
																	
									if( $post_count >= 1 ) {									
																		
									$author_info = get_userdata($author->ID);
									
										echo '<li class="sbaw_author">';
										
											if( $gravatar ) {											
												echo '<div style="float: left; margin-left: 5px;">';
													echo get_avatar($author->ID, 40);
												echo '</div>';
											}
											echo '<a href="' . get_author_posts_url($author->ID) .'" title="View author archive">';
												echo $author_info->display_name;
												if($count) {
													echo '(' . $post_count . ')';
												}
											echo '</a>';
	
										echo '</li>';
										
									}
								}							
							?>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['gravatar'] = strip_tags($new_instance['gravatar']);
		$instance['count'] = strip_tags($new_instance['count']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	

      $title = esc_attr($instance['title']);
		$gravatar = esc_attr($instance['gravatar']);
		$count = esc_attr($instance['count']);
		
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

		<p>
          <input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="checkbox" value="1" <?php checked( '1', $count ); ?>/>
          <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Display Post Count?'); ?></label> 
        </p>

		<p>
          <input id="<?php echo $this->get_field_id('gravatar'); ?>" name="<?php echo $this->get_field_name('gravatar'); ?>" type="checkbox" value="1" <?php checked( '1', $gravatar ); ?>/>
          <label for="<?php echo $this->get_field_id('gravatar'); ?>"><?php _e('Display Author Gravatar?'); ?></label> 
        </p>

        <?php 
    }


} // class utopian_recent_posts
// register Recent Posts widget
add_action('widgets_init', create_function('', 'return register_widget("pippin_simple_authors_widget");'));
