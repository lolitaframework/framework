<?php
namespace lolita\LolitaFramework\Widgets;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Loc;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Controls\Controls;
use \WP_Widget;

class Widget extends WP_Widget
{

    /**
     * Widget view
     * @var null
     */
    private $view_file = null;

    /**
     * Callback form or view string
     * @var null
     */
    private $form = null;

    /**
     * Controls
     * @var null
     */
    public $controls = null;

    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct($name, $view, $id_base, $form, $controls = null, $widget_options = array(), $control_options = array())
    {
        $this->view_file = $view;
        $this->form      = $form;
        $this->controls  = $controls;
        $this->prepareControls();

        parent::__construct(
            $id_base,
            $name,
            $widget_options,
            $control_options
        );
        add_shortcode($this->id_base.'_sc', array(&$this, 'widgetShortcode'));
    }

    /**
     * Prepare controls
     *
     * @return Widget instance
     */
    private function prepareControls()
    {
        $defaults['default'] = '';
        if (is_array($this->controls)) {
            foreach ($this->controls as &$control) {
                $control = array_merge($defaults, $control);
            }
        }
        return $this;
    }

    /**
     * Render widget in short code
     *
     * @param  array $atts attributes.
     * @param  string $content content
     * @return string rendered shortcode.
     */
    public function widgetShortcode($atts, $content = '')
    {
        $new_atts = json_decode($content, true);
        $new_atts = array_merge(
            array(
                'args'     => array(),
                'instance' => array(),
            ),
            (array) $new_atts
        );

        ob_start();
        $this->widget($new_atts['args'], $new_atts['instance']);
        return ob_get_clean();
    }

    /**
     * Get view
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $file view file.
     * @param  array $arguments arguments.
     * @return string view HTML code.
     */
    public function getView($file, $arguments = array())
    {
        $arguments = apply_filters($this->id_base.'_arguments', $arguments);
        $file      = apply_filters($this->id_base.'_view', $file, $arguments);
        return View::make(
            $file,
            $arguments
        );
    }

    /**
     * Render view
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $file view file.
     * @param  array $arguments arguments.
     * @return void.
     */
    public function view($file, $arguments)
    {
        echo $this->getView($file, $arguments);
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        if (is_callable($this->view_file)) {
            call_user_func(
                $this->view_file,
                $args,
                $instance
            );
        } else {
            $this->view(
                $this->view_file,
                array(
                    'args'     => $args,
                    'instance' => array_merge(Arr::pluck($this->controls, 'default', 'name'), $instance),
                )
            );
        }
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        if ($this->controls) {
            echo $this->renderControls($instance);
        } else {
            if ($this->form) {
                echo View::make($this->form, $instance);
            } else {
                echo View::make(__DIR__ . DS . 'views' . DS . 'empty_form.php');
            }
        }
    }



    /**
     * Render controls if we have it
     *
     * @param  array $instance
     * @return string HTML
     */
    private function renderControls($instance)
    {
        $controls_data = $this->controls;
        $controls = new Controls;
        foreach ($controls_data as &$control) {
            $control['old_name'] = $control['name'];
            $control['name']     = $this->get_field_name($control['name']);
        }

        $controls->generateControls($controls_data);

        foreach ($controls->collection as $control) {
            // ==============================================================
            // Set new value
            // ==============================================================
            $control->setValue(
                Arr::get($instance, $control->old_name, '')
            );
            // ==============================================================
            // Fill new attributes
            // ==============================================================
            $attributes = $control->getAttributes();
            $control->setAttributes(
                array_merge(
                    $attributes,
                    array(
                        'class' => $control->old_name . '-class widefat ' . Arr::get($attributes, 'class', ''),
                        'id'    => $this->get_field_id($control->getID()),
                    )
                )
            );
        }
        return $controls->render(
            dirname(__FILE__) . DS . 'views' . DS . 'collection.php',
            dirname(__FILE__) . DS . 'views' . DS . 'row.php'
        );
    }
}
