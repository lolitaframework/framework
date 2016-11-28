<?php
namespace lolita\LolitaFramework\Controls\Icons;

use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Loc;
use \lolita\LolitaFramework\Core\Data;
use \lolita\LolitaFramework\Core\View;

class Pack
{
    /**
     * Package data.
     * @var array
     */
    private $data = array();

    /**
     * File path
     * @var string
     */
    private $path = '';

    /**
     * Pack constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $parameters control parameters.
     */
    public function __construct($file_path)
    {
        $fs = Loc::wpFilesystem();
        $this->data = json_decode($fs->get_contents($file_path), true);
        $this->path = $file_path;
    }

    /**
     * Get name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string file name.
     */
    public function getName()
    {
        return basename($this->path);
    }

    /**
     * Get package title
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string package title.
     */
    public function getTitle()
    {
        return Arr::get($this->data, 'title');
    }

    /**
     * Get URL
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string CSS url.
     */
    public function getURL()
    {
        return Data::interpret(Arr::get($this->data, 'url'));
    }

    /**
     * Package icons
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array icons.
     */
    public function getIcons()
    {
        return (array) Arr::get($this->data, 'icons', array());
    }

    /**
     * Enqueue css file.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Pack instance.
     */
    public function enqueue()
    {
        wp_enqueue_style($this->getName(), $this->getURL());
        return $this;
    }

    /**
     * Render pack
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string rendered pack.
     */
    public function render()
    {
        return View::make(
            __DIR__ . DS . 'views' . DS . 'pack.php',
            array(
                'me' => $this,
            )
        );
    }
}
