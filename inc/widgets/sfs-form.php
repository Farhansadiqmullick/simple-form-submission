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
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('SFS frontend form', 'sfs');
        }
    }

    // Widget update
    public function update($new_instance, $old_instance)
    {
    }
}

