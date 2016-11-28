<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Core\Data;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Str;

class Customize implements IModule
{
    const WITHOUT_PANEL   = '__WITHOUT_PANEL__';
    const SECTIONS        = '__SECTIONS__';
    const SETTING         = '__SETTINGS__';
    const CONTROLS        = '__CONTROLS__';
    const CUSTOM_CLASS    = '__CLASS__';

    /**
     * $wp_customize object
     *
     * @var null
     */
    private $customize = null;

    /**
     * Save the data list
     *
     * @var array
     */
    private $data = array();

    /**
     * Customs class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $data config file data.
     * @return void
     */
    public function __construct(array $data = null)
    {
        // Save data
        $this->data = $data;

        add_action('customize_register', array( $this, 'register' ));
    }

    /**
     * Front End Customizer.
     * Load setting tree.
     *
     * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register($wp_customize)
    {
        $this->customize = $wp_customize;
        $this->addPanels($this->data);
    }

    /**
     * Add panels to customizer
     *
     * @param array $panels panels list.
     */
    private function addPanels(array $panels)
    {
        foreach ($panels as $panel) {
            if (is_array($panel)) {
                if (array_key_exists(self::SECTIONS, $panel)) {
                    $panel = $this->preparePanel($panel);
                    $this->customize->add_panel($panel['id'], $panel);
                    $this->addSections($panel['id'], $panel[self::SECTIONS]);
                } else {
                    $this->addSections('', array($panel));
                }
            } else {
                $this->customize->remove_section($panel);
            }
        }
    }

    /**
     * Prepare panel
     *
     * @param  array $data
     * @return array
     */
    private function preparePanel(array $data)
    {
        if (!array_key_exists('title', $data)) {
            throw new \Exception("`title` is required field! Please fill this field.");
        }

        $data = array_merge(
            array(
                'id'          => Str::slug($data['title'], '_'),
                'description' => '',
                'priority'    => 10,
            ),
            $data
        );

        return $data;
    }



    /**
     * Add sections to customizer
     *
     * @param string $panel_id
     * @param array $sections
     */
    private function addSections($panel_id, array $sections)
    {
        foreach ($sections as $section) {
            $section = $this->prepareSection($panel_id, $section);
            $this->customize->add_section($section['id'], $section);
            if (array_key_exists(self::CONTROLS, $section)) {
                $this->addControls($panel_id, $section['id'], $section[self::CONTROLS]);
            }
        }
    }

    /**
     * Prepare section data
     *
     * @param  string $panel_id
     * @param  array  $data
     * @return prepared data
     */
    private function prepareSection($panel_id, array $data)
    {
        if (!array_key_exists('title', $data)) {
            throw new \Exception("`title` is required field! Please fill this field.");
        }

        $data = array_merge(
            array(
                'id'          => Str::slug($panel_id.' '.$data['title'], '_'),
                'description' => '',
                'panel'       => $panel_id,
                'priority'    => 10,
            ),
            $data
        );

        return $data;
    }

    private function addControls($panel_id, $section_id, array $controls)
    {
        foreach ($controls as $control) {
            $control = $this->prepareControl($panel_id, $section_id, $control);
            $args = array_merge(
                array(
                    'default'           => '',
                    'type'              => 'theme_mod',
                    'capability'        => 'manage_options',
                    'sanitize_callback' => '',
                ),
                Arr::get($control, self::SETTING, array())
            );
            $args['default'] = Data::interpret($args['default']);
            $this->customize->add_setting($control['id'], $args);
            if (array_key_exists(self::CUSTOM_CLASS, $control)) {
                $custom_class = $control[self::CUSTOM_CLASS];
                $this->customize->add_control(
                    new $custom_class(
                        $this->customize,
                        $control['id'],
                        $control
                    )
                );
            } else {
                $this->customize->add_control($control['id'], $control);
            }
        }
    }

    /**
     * Prepare control data
     *
     * @param  string $panel_id
     * @param  string $section_id
     * @param  array  $data
     * @return prepared data
     */
    private function prepareControl($panel_id, $section_id, array $data)
    {
        if (!array_key_exists('label', $data)) {
            throw new \Exception("`label` is required field! Please fill this field.");
        }

        $data = array_merge(
            array(
                'id'          => Str::slug($section_id.' '.$data['label'], '_'),
                'description' => '',
                'section'     => $section_id,
                'settings'    => Str::slug($section_id.' '.$data['label'], '_'),
            ),
            $data
        );

        if (array_key_exists('choices', $data)) {
            $data['choices'] = Data::interpret($data['choices']);
        }

        return $data;
    }

    /**
     * Module priority
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return 99;
    }
}
