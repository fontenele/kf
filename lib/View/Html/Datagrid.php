<?php

namespace KF\Lib\View\Html;

class Datagrid {

    /**
     * @var integer
     */
    public $rowPerPage = 15;

    /**
     * @var integer
     */
    public $active = 0;

    /**
     * @var integer
     */
    public $totalItems = 0;

    /**
     * @var integer
     */
    public $totalPages = 0;

    /**
     * @var integer
     */
    public $numItems = 5;

    /**
     * @var array
     */
    public $rows = array();

    /**
     * @var array
     */
    public $items = array();

    /**
     * @var string
     */
    public $formSelector = 'form';

    /**
     * @var array
     */
    public $criteria = array();

    /**
     * @var string
     */
    public $left = '&laquo;';

    /**
     * @var string
     */
    public $right = '&raquo;';

    public function __construct($formSelector = 'form', $criteria = array()) {
        $this->rowsPerPage = \KF\System::$config['system']['view']['datagrid']['rowsPerPage'];
        $this->formSelector = $formSelector;

        $this->criteria = $criteria;

        if (isset($criteria['p'])) {
            $this->active = $criteria['p'];
            unset($this->criteria['p']);
        }
    }

    public function getInputHidden() {
        $html = <<<HTML
                <input type="hidden" name="p" value="{$this->active}" />
HTML;
        return $html;
    }

    public function setRows($rows = array()) {
        $this->rows = $rows;
        if (count($rows) && isset($rows[array_keys($rows)[0]]) && isset($rows[array_keys($rows)[0]]['total'])) {
            $this->active = $this->active == 0 ? 1 : $this->active;
            $this->totalItems = $rows[array_keys($rows)[0]]['total'];

            $totalPages = round($this->totalItems / $this->rowPerPage);
            if ($totalPages * $this->rowPerPage < $this->totalItems) {
                $totalPages++;
            }
            $this->totalPages = $totalPages;
            if ($this->active > $this->totalPages) {
                $this->active = $this->totalPages;
            }

            $activeMiddle = $this->numItems % 2;

            $itemsPerSide = floor($this->numItems / 2);
            if ($activeMiddle && $this->active - $itemsPerSide > 0 && $this->active + $itemsPerSide <= $this->totalPages) {
                for ($i = $itemsPerSide; $i > 0; $i--) {
                    $this->items[] = $this->active - $i;
                }
                $this->items[] = $this->active;
                for ($i = 1; $i <= $itemsPerSide; $i++) {
                    $this->items[] = $this->active + $i;
                }
            } else {
                $itemsAdded = 0;
                if ($this->active - $itemsPerSide <= 0) {
                    $i = $this->active - $itemsPerSide;
                    while ($i <= $this->active) {
                        if ($i > 0 && $itemsAdded < $this->numItems && $i <= $this->totalPages) {
                            $this->items[] = $i;
                            $itemsAdded++;
                        }
                        $i++;
                    }
                    while ($itemsAdded < $this->numItems && $i <= $this->totalPages) {
                        $this->items[] = $i;
                        $itemsAdded++;
                        $i++;
                    }
                } else {
                    $i = $this->active + $itemsPerSide;

                    while ($i >= $this->active) {
                        if ($i >= $this->active && $i <= $this->totalPages && $itemsAdded < $this->numItems) {
                            $this->items[] = $i;
                            $itemsAdded++;
                        }
                        $i--;
                    }
                    while ($itemsAdded < $this->numItems && $i <= $this->totalPages) {
                        $this->items[] = $i;
                        $itemsAdded++;
                        $i--;
                    }

                    $this->items = \array_reverse($this->items);
                }
            }
        }
    }

    public function __toString() {
        $html = <<<HTML
                <ul class="pagination">
                    %s
                </ul>
HTML;
        $items = '';

        $leftDisabled = $this->active <= 1 ? "class='disabled'" : '';
        $leftPage = $this->active - 1 > 0 ? $this->active - 1 : 1;
        $items.= "<li {$leftDisabled}><a data-page='{$leftPage}' data-form='{$this->formSelector}'>{$this->left}</a></li>";

        foreach ($this->items as $page) {
            $active = $page == $this->active ? "class='active'" : '';
            $items.= "<li {$active}><a data-page='{$page}' data-form='{$this->formSelector}'>{$page}</a></li>";
        }

        $rightDisabled = $this->active == $this->totalPages ? "class='disabled'" : '';
        $rightPage = $this->active + 1 < $this->totalPages ? $this->active + 1 : $this->totalPages;
        $items.= "<li {$rightDisabled}><a data-page='{$rightPage}' data-form='{$this->formSelector}'>{$this->right}</a></li>";

        return sprintf($html, $items);
    }

}
