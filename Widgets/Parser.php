<?php
namespace lolita\LolitaFramework\Widgets;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Loc;
use \WP_Widget;

class Parser
{
    /**
     * Parsed data
     * @var boolean
     */
    private $data = false;

    /**
     * JSON path
     * @var null
     */
    private $path = null;

    /**
     * Parser class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->contents()->name()->idBase()->view()->form();
    }

    /**
     * Parse name
     *
     * @return Parser instance.
     */
    private function name()
    {
        if (!array_key_exists('name', $this->data)) {
            $this->data = false;
            throw new \Exception(__('Parameter "name" is required!', 'lolita'));
        }
        return $this;
    }

    /**
     * Parse id_base
     *
     * @return Parser instance.
     */
    private function idBase()
    {
        if (!array_key_exists('id_base', $this->data) || '' === $this->data['id_base']) {
            $this->data['id_base'] = Str::slug($this->data['name']);
        }
        return $this;
    }

    /**
     * Parse view
     * @return Parser instance.
     */
    private function view()
    {
        if (!array_key_exists('view', $this->data)) {
            $json_name    = basename($this->path);
            $view_name    = str_replace('.json', '.php', $json_name);
            $views_folder = dirname($this->path) . DS . 'views' . DS;
            $view_path    = $views_folder . $view_name;
            if (is_file($view_path)) {
                $this->data['view'] = $view_path;
            }
        }
        return $this;
    }

    /**
     * Parse form
     *
     * @return Parser instance.
     */
    private function form()
    {
        if (array_key_exists('controls', $this->data)) {
            $this->data['form'] = array($this, 'renderForm');
        }
        if (!array_key_exists('form', $this->data)) {
            $this->data['form'] = null;
        }
        return $this;
    }

    /**
     * JSON from path
     *
     * @return Parser instance.
     */
    private function contents()
    {
        $fs = Loc::wpFilesystem();
        $content = $fs->get_contents($this->path);
        if (false !== $content) {
            $decoded = json_decode($content, true);
            if ($decoded) {
                $this->data = $decoded;
            }
        }
        return $this;
    }

    /**
     * Parsed data
     *
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }
}
