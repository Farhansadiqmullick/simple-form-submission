<?php
class SFS_Widget extends WP_Widget
{
    // Constructor
    public function __construct()
    {
        parent::__construct(
            'sfs_widget', // Widget ID
            'SFS Widget Frontend Form', // Widget Name
            array('description' => __('A custom widget that renders the SFS form', 'sfs')),
        );
    }

    // Widget front-end
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        ob_start();
        include(SFS_PATH . 'template/frontend-form.php');
        $content = ob_get_clean();
        echo $content;
        echo $args['after_widget'];
        return $content;
    }


    // Widget back-end
    public function form($instance)
    {
        $title = !empty($instance['title']) ? esc_attr($instance['title']) : __('SFS frontend form', 'sfs');
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'sfs'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
<?php
    }

    // Widget update
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}
