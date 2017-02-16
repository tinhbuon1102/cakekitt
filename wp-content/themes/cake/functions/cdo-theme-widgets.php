<?php 
/*-----------------------------------------------------------------------------------
  Register Widgets
-----------------------------------------------------------------------------------*/
function cake_init_sidebars() {    

	register_sidebar( array(
	'name'				=> esc_html__( 'Blog', 'cake'),
	'id'				=> 'cake-post-sidebar',
	'description'		=> esc_html__( 'Located at the side of blog, archives, single and search.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));

	register_sidebar( array(
	'name'				=> esc_html__( 'Page', 'cake'),
	'id'				=> 'cake-page-sidebar',
	'description'		=> esc_html__( 'Located at the side of page templates.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));
	
	register_sidebar( array(
	'name'				=> esc_html__( 'Shop', 'cake'),
	'id'				=> 'cake-shop-sidebar',
	'description'		=> esc_html__( 'Located at the side of shop page.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));
	
	register_sidebar( array(
	'name'				=> esc_html__( 'After Content', 'cake'),
	'id'				=> 'cake-after-content-sidebar',
	'description'		=> esc_html__( 'Located at the bottom after content section.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));
	
	register_sidebar( array(
	'name'				=> esc_html__( 'Footer1', 'cake'),
	'id'				=> 'cake-footer1',
	'description'		=> esc_html__( 'Located at footer, show on first column.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));

	register_sidebar( array(
	'name'				=> esc_html__( 'Footer2', 'cake'),
	'id'				=> 'cake-footer2',
	'description'		=> esc_html__( 'Located at footer, show on second column.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));

	register_sidebar( array(
	'name'				=> esc_html__( 'Footer3', 'cake'),
	'id'				=> 'cake-footer3',
	'description'		=> esc_html__( 'Located at footer, show on third column.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));

	register_sidebar( array(
	'name'				=> esc_html__( 'Footer4', 'cake'),
	'id'				=> 'cake-footer4',
	'description'		=> esc_html__( 'Located at footer, show on fourth column.', 'cake'),
	'before_widget'		=> '<aside class="widgets %2$s" id="%1$s">',
	'after_widget'		=> '</aside>',
	'before_title'		=> '<h3 class="widget-title">',
	'after_title'		=> '</h3>',
	));

}
add_action( 'widgets_init', 'cake_init_sidebars');


/*-----------------------------------------------------------------------------------
  Tags Cloud Widget
-----------------------------------------------------------------------------------*/
class cake_Custom_Tags_Widget extends WP_Widget {
	function __construct() {
        $widgets_opt = array( 'description' => esc_html__('Tags cloud widget.', 'cake') );
		parent::__construct(false,$name= esc_html__("Cake - Tag Cloud",'cake'),$widgets_opt);
    }

    function widget($args, $instance) {
        
        $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__('Tag Cloud','cake') : esc_attr($instance['title']
		), $instance, $this->id_base );

        echo $args['before_widget'];

        if ( !empty($instance['title']) )
            echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$tags = array();
		$posts = get_posts('numberposts=-1');
		foreach ($posts as $p) {
			foreach (wp_get_post_tags($p->ID) as $tag) {
				if (array_key_exists($tag->name, $tags))
					$tags[$tag->name]['count']++;
				else {
					$tags[$tag->name]['count'] = 1;
					$tags[$tag->name]['link'] = get_tag_link($tag->term_id);
				}
			}
		}
		
		// Show tag cloud
		echo '<div class="cake-tag-cloud">';
			foreach ($tags as $tag_name => $tag) {
				echo '<span class="tag"><a href="' . esc_url($tag['link']) . '">' . $tag_name . '</a></span>';
			}
		echo '</div>';  

        echo $args['after_widget'];

    }

    function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
        return $instance;
    }

    function form( $instance ) {
        $title = isset( $instance['title'] ) ? esc_attr($instance['title']) : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'cake') ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("cake_Custom_Tags_Widget");'));


/*-----------------------------------------------------------------------------------
  Post Widget
-----------------------------------------------------------------------------------*/
class cake_Popular_Post_Widget extends WP_Widget {
  
  function __construct() {
        $widgets_opt = array( 'description' => esc_html__('Display popular post base on comments count.', 'cake') );
		parent::__construct(false,$name= esc_html__("Cake - Post",'cake'),$widgets_opt);
    }

    function widget($args, $instance) {
        
        $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__('Popular Post','cake') : esc_attr($instance['title']
		), $instance, $this->id_base );
		$numnews = isset( $instance['numnews'] ) ? esc_attr($instance['numnews']) : 3;
		$type = isset( $instance['type'] ) ? esc_attr($instance['type']) : 'recent';

        echo $args['before_widget'];

        if ( !empty($instance['title']) )
            echo $args['before_title'] . $instance['title'] . $args['after_title'];

        cake_popular_post($numnews, $type);

        echo $args['after_widget'];

    }

    function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
        $instance['numnews'] = $new_instance['numnews'];
		$instance['type'] = $new_instance['type'];
        return $instance;
    }

    function form( $instance ) {
        $title = isset( $instance['title'] ) ? esc_attr($instance['title']) : '';
        $numnews = isset( $instance['numnews'] ) ? esc_attr($instance['numnews']) : 3;
		$type = isset( $instance['type'] ) ? esc_attr($instance['type']) : 'recent';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'cake') ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" />
        </p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('numnews')); ?>"><?php esc_html_e('Number to display:','cake');?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('numnews')); ?>" name="<?php echo esc_attr($this->get_field_name('numnews')); ?>"  value="<?php echo esc_attr($numnews);?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('type')); ?>"><?php esc_html_e('Type','cake');?></label>
			<?php $types = array ('recent', 'popular');?>
			<select name="<?php echo esc_attr($this->get_field_name('type')); ?>" id="<?php echo esc_attr($this->get_field_id('type')); ?>" class="widefat">
			
			<?php
			foreach ($types as $option) {
			echo '<option value="' . $option . '" id="' . $option . '"', $type == $option ? ' selected="selected"' : '', '>', $option, '</option>';
			}
			?>
			</select>
		</p>
        <?php
    }
  
}
add_action('widgets_init', create_function('', 'return register_widget("cake_Popular_Post_Widget");'));
?>