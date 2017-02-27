<?php
namespace lolita\LolitaFramework\Tables;

use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Str;
use \Exception;

class Table
{
    /**
     * Table name
     * @var string
     */
    public $name = '';

    /**
     * Table slug
     * @var string
     */
    public $slug = '';

    /**
     * <tfoot></tfoot>
     * @var null
     */
    public $tfoot = null;

    /**
     * <thead></thead>
     * @var null
     */
    public $thead = null;

    /**
     * <tbody>
     * @var null
     */
    public $tbody = null;

    /**
     * Show chart
     * @var boolean
     */
    public $show_chart = true;

    /**
     * Class constructor
     *
     * @param string $name
     * @param array  $rows
     */
    public function __construct($name, array $rows, $show_chart = true)
    {
        $this->setName($name);
        $this->setRows($rows);
        $this->show_chart = $show_chart;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        if ('' === trim($name)) {
            throw new Exception('Invalid name:' . $name . '!');
        }

        $this->name = $name;
        $this->slug = Str::slug($name);

        return $this;
    }

    /**
     * Set rows
     * @param array $rows
     */
    public function setRows(array $rows)
    {
        if (3 > $rows) {
            throw new Exception('Not enough lines in Array!');
        }
        $this->thead = array_shift($rows);
        $this->tfoot = array_pop($rows);
        $this->tbody = $rows[0];
    }

    /**
     * Render table
     *
     * @return void
     */
    public function render()
    {
        return View::make(
            __DIR__ . DS . 'views' . DS . 'table.php',
            array(
                'classes' => 'wp-list-table widefat fixed striped ' . $this->slug,
                'table'   => $this,
            )
        );
    }
}
