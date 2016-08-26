<?php
	defined('ABSPATH') or die('Jog on!');


class ws_ls_widget_form extends WP_Widget {

    private $field_values = array();

	function __construct() {
		parent::__construct(
			'ws_ls_widget_form',
			__('Weight Loss Tracker - Form', WE_LS_SLUG),
			array( 'description' => __('Display a quick entry form for logged in users to record their weight for today.', WE_LS_SLUG) ) // Args
		);

        $this->field_values = array(
            'title' => __('Your weight today', WE_LS_SLUG),
						'force_todays_date' => 'yes',
                        'not-logged-in-message' => ''
        );

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

        // User logged in?
        if(is_user_logged_in()) {

						echo $args['before_widget'];
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];

						$force_to_todays_date = ('yes' == $instance['force_todays_date']) ? true : false;

            echo ws_ls_display_weight_form(false, false, false, true, ws_ls_remove_non_numeric($args['widget_id']) + 10000, $force_to_todays_date);
            echo $args['after_widget'];
        } elseif (isset($instance['not-logged-in-message']) && !empty($instance['not-logged-in-message'])) { 
            echo $args['before_widget'];
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
            echo '<p>' . $instance['not-logged-in-message'] . '</p>';
            echo $args['after_widget'];
        }

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

        // Loop through expected fields and process
        foreach($this->field_values as $key => $default) {
            $field_values[$key] = !empty($instance[$key]) ? $instance[$key] : $default;
        }

		?>
        <p>Display a quick entry form for logged in users to record their weight for today. The widget will be hidden if the user is <strong>not logged in</strong>.</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', WE_LS_SLUG); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $field_values['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'force_todays_date' ); ?>"><?php _e('Allow user to specify a date?', WE_LS_SLUG); ?></label>
            <select class="widefat" name="<?php echo $this->get_field_name( 'force_todays_date' ); ?>" id="<?php echo $this->get_field_id( 'force_todays_date' ); ?>">
                    <option value="yes" <?php selected( $field_values['force_todays_date'], 'yes'); ?>><?php _e('No. Automatically set to today\'s date', WE_LS_SLUG); ?></option>
                    <option value="no" <?php selected( $field_values['force_todays_date'], 'no'); ?>><?php _e('Allow user to specify a date', WE_LS_SLUG); ?></option>
            </select>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'not-logged-in-message' ); ?>"><?php _e('Message to display if not logged in', WE_LS_SLUG); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'not-logged-in-message' ); ?>" name="<?php echo $this->get_field_name( 'not-logged-in-message' ); ?>" type="text" value="<?php echo esc_attr( $field_values['not-logged-in-message'] ); ?>">
		   <p><small><?php _e('By default the widget is hidden if the user is not logged in. If you wish, you can display a message to the visitor instead.', WE_LS_SLUG); ?></small></p>

        </p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

    foreach($this->field_values as $key => $value) {
        $instance[$key] = !empty($new_instance[$key]) ? strip_tags($new_instance[$key]) : '';
    }

		return $instance;
	}

}
