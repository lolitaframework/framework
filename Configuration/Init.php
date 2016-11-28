<?php
namespace lolita\LolitaFramework\Configuration;

abstract class Init
{
    /**
     * Save the data list
     *
     * @var array
     */
    protected $data = array();

    /**
     * Initialize action
     *
     * @var string
     */
    protected $init_action = 'init';

    /**
     * Initialize function
     *
     * @var string
     */
    protected $init_function = 'install';

    /**
     * Init data exception string
     *
     * @var string
     */
    protected $init_exp_string = 'JSON can be converted to Array';

    /**
     * Initialize new LF_Init class
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @throws Exception JSON can be converted to Array.
     * @return void
     */
    protected function init()
    {
        if (null !== $this->data) {
            add_action($this->init_action, array( $this, $this->init_function ));
        } else {
            throw new \Exception(__($this->init_exp_string, 'lolita'));
        }
    }
}
