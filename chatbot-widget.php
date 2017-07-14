<?php
/**
 * Plugin Name: Chatbot Widget
 * Plugin URI: http://bot.daio.com/widget
 * Description: A Chatbot widget 
 * Version: 1.0.0
 * Author: Benoit Bottemanne
 * Author URI: http://daio.com
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'chatbot_load_widgets' );

/**
 * Register our widget.
 * 'Chatbot_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function chatbot_load_widgets() {
	register_widget( 'Chatbot_Widget' );
}

/**
 * Chatbot Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Chatbot_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Chatbot_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'chatbot', 'description' => __('A Chatbot widget designed to talk about various subjects.', 'chatbot') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 225, 'id_base' => 'chatbot-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'chatbot-widget', __('Chatbot Widget', 'chatbot'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		//$name = $instance['name'];
		$sex = $instance['sex'];
		$enable_voice = isset( $instance['enable_voice'] ) ? $instance['enable_voice'] : false;

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		
		$arg_line = ($enable_voice == true) ? 'v=1' : 'v=0';		
		$arg_line .= ($sex == 'female') ? '&s=f' : '&s=m';
		
		echo '<script type="text/javascript">';
		echo 'aiwidget_arg="' . $arg_line . '";';
		echo '</script>';
		echo '<script type="text/javascript" src="http://ai.daio.com/aiwidget.js"></script>';
								
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['name'] = strip_tags( $new_instance['name'] );

		/* No need to strip tags for sex and enable_voice. */
		$instance['sex'] = $new_instance['sex'];
		$instance['enable_voice'] = $new_instance['enable_voice'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Chatbot', 'chatbot'), 'sex' => 'male', 'enable_voice' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>		

		<!-- Sex: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'sex' ); ?>"><?php _e('Voice:', 'chatbot'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'sex' ); ?>" name="<?php echo $this->get_field_name( 'sex' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'male' == $instance['sex'] ) echo 'selected="selected"'; ?>>male</option>
				<option <?php if ( 'female' == $instance['sex'] ) echo 'selected="selected"'; ?>>female</option>
			</select>
		</p>

		<!-- Enable Voice ? Checkbox -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['enable_voice'], true ); ?> id="<?php echo $this->get_field_id( 'enable_voice' ); ?>" name="<?php echo $this->get_field_name( 'enable_voice' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'enable_voice' ); ?>"><?php _e('Enable Voice ?', 'chatbot'); ?></label>
		</p>

	<?php
	}
}

?>