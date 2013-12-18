<?php
global $wpsxp_token;
class sexypolling_widget extends WP_Widget {

  // Constructor //
  function sexypolling_widget() {
    $widget_ops = array(
      'classname' => 'sexypolling_widget',
      'description' => 'Add Sexy Polling widget.'
    ); // Widget Settings
    $control_ops = array('id_base' => 'sexypolling_widget'); // Widget Control Settings
    $this->WP_Widget('sexypolling_widget', 'Sexy Polling', $widget_ops, $control_ops); // Create the widget
  }

  // Extract Args
  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    // Before widget
    echo $before_widget;
    // Title of widget
    if ($title) {
      echo $before_title . $title . $after_title;
    }
    // the widget content
    global $wpsxp_token;
	
    wpsxp_enqueue_front_scripts($instance['poll_id']);
    echo $sexy_rendered_content = wpsxp_render_poll($instance['poll_id']);
    
    
    // After widget
    echo $after_widget;
  }

  // Update Settings //
  function update($new_instance, $old_instance) {
    $instance['title'] = $new_instance['title'];
    $instance['poll_id'] = $new_instance['poll_id'];
    return $instance;
  }

  // Widget Control Panel //
  function form($instance) {
    $defaults = array(
      'title' => '',
      'poll_id' => 0
    );
    $instance = wp_parse_args((array)$instance, $defaults);
    global $wpdb; ?>
  <p>
    <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
           name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>"/>
    <label for="<?php echo $this->get_field_id('poll_id'); ?>">Select a poll:</label>
    <select name="<?php echo $this->get_field_name('poll_id'); ?>'" id="<?php echo $this->get_field_id('poll_id'); ?>"
            style="width:225px;text-align:center;">
      <option style="text-align:center" value="0">- Select a Poll -</option>
      <?php
      $ids_sexypolling = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wpsxp_sexy_polls order by `id` DESC", 0);
      foreach ($ids_sexypolling as $arr_sexypolling) {
        ?>
        <option value="<?php echo $arr_sexypolling->id; ?>" <?php if ($arr_sexypolling->id == $instance['poll_id']) {
          echo "SELECTED";
        } ?>><?php echo $arr_sexypolling->name; ?></option>
        <?php }?>
    </select>
  <?php
  }
}

add_action('widgets_init', create_function('', 'return register_widget("sexypolling_widget");'));
?>