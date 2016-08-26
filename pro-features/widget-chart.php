<?php
	defined('ABSPATH') or die('Jog on!');


class ws_ls_widget_chart extends WP_Widget {

    private $field_values = array();

	function __construct() {
		parent::__construct(
			'ws_ls_widget_chart',
			__('Weight Loss Tracker - Chart', WE_LS_SLUG),
			array( 'description' => __('Display a chart to see your current progress.', WE_LS_SLUG) ) // Args
		);

        $this->field_values = array(
            'title' => __('Weight Loss Tracker', WE_LS_SLUG),
            'max-points' => 5,
            'user-id' => '',
            'type' => 'line',
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
        if(is_user_logged_in())
        {
            $chart_arguments =  array('user-id' => get_current_user_id(),
                                    'max-data-points' => WE_LS_CHART_MAX_POINTS);

            if(is_numeric($instance['user-id']) && $instance['user-id'] != 0) {
                $chart_arguments['user-id'] = $instance['user-id'];
            }
            if(is_numeric($instance['max-points'])) {
                $chart_arguments['max-data-points'] = $instance['max-points'];
            }
             if(in_array($instance['type'], array('bar', 'line'))) {
                $chart_arguments['type'] = $instance['type'];
            }

            $weight_data = ws_ls_get_weights($chart_arguments['user-id'], $chart_arguments['max-data-points'], -1, 'desc');

            // Reverse array so in cron order
            $weight_data = array_reverse($weight_data);
            
			$chart_arguments['height'] = false;

            if ($weight_data) {
                echo $args['before_widget'];
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
                if (count($weight_data) > 1) {
                   echo ws_ls_display_chart($weight_data, $chart_arguments);
                } else {
                    echo '<p>' . __('A pretty graph shall appear once you have recorded several weights.', WE_LS_SLUG) . '</p>';
                }
                echo $args['after_widget'];
            } else {
                echo '<!-- WLT Chart: No user data found for given ID -->';
            }
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
        <p>Display a chart and / or form for the current user. The widget will be hidden if the user is <strong>not logged in</strong>.</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', WE_LS_SLUG); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $field_values['title'] ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e('Type', WE_LS_SLUG); ?></label>
            <select class="widefat" name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>">
                <option value="line" <?php selected( $field_values['type'], 'line'); ?>>Line Chart</option>
                <option value="bar" <?php selected( $field_values['type'], 'bar'); ?>>Bar Chart</option>
            </select>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'max-points' ); ?>"><?php _e('Maximum number of plot points on chart', WE_LS_SLUG); ?></label>
            <select class="widefat" name="<?php echo $this->get_field_name( 'max-points' ); ?>" id="<?php echo $this->get_field_id( 'max-points' ); ?>">
                <option value="5" <?php selected( $field_values['max-points'], '5'); ?>>5</option>
                <option value="10" <?php selected( $field_values['max-points'], '10'); ?>>10</option>
                <option value="15" <?php selected( $field_values['max-points'], '15'); ?>>15</option>
                <option value="25" <?php selected( $field_values['max-points'], '25'); ?>>25</option>
            </select>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'not-logged-in-message' ); ?>"><?php _e('Message to display if not logged in', WE_LS_SLUG); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'not-logged-in-message' ); ?>" name="<?php echo $this->get_field_name( 'not-logged-in-message' ); ?>" type="text" value="<?php echo esc_attr( $field_values['not-logged-in-message'] ); ?>">
		   <p><small><?php _e('By default the widget is hidden if the user is not logged in. If you wish, you can display a message to the visitor instead.', WE_LS_SLUG); ?></small></p>

        </p>


	   <p>
			<label for="<?php echo $this->get_field_id( 'user-id' ); ?>"><?php _e('ID of user (leave blank to show chart for current user)', WE_LS_SLUG); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'user-id' ); ?>" name="<?php echo $this->get_field_name( 'user-id' ); ?>" type="text" value="<?php echo esc_attr( $field_values['user-id'] ); ?>">
		</p>

    <p><small><?php _e('Note: By default, the chart will be displayed in the weight unit chosen by the logged in user. If not specfied the plugin default will be used.', WE_LS_SLUG); ?></small></p>

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
