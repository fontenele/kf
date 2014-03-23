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
     * Max pages in paginator
     * @var integer
     */
    public $numItems = 3; // ver esse 5 ae

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
    public $paginatorLeft = '&laquo;';

    /**
     * @var string
     */
    public $paginatorRight = '&raquo;';

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var array
     */
    public $headersRenderers = [];

    public function __construct($formSelector = 'form', $criteria = array()) {
        try {

            $this->rowsPerPage = \KF\Kernel::$config['system']['view']['datagrid']['rowsPerPage'];
            $this->formSelector = $formSelector;

            $this->criteria = $criteria;

            if (isset($criteria['kf-dg-p'])) {
                $this->active = $criteria['kf-dg-p'];
                unset($this->criteria['kf-dg-p']);
            }
            foreach ($this->criteria as $field => $value) {
                if (!$value) {
                    unset($this->criteria[$field]);
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getInputHidden() {
        try {

            $html = <<<HTML
                <input type="hidden" name="kf-dg-p" value="{$this->active}" />
HTML;
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $html;
    }

    public function addHeader($fieldName, $label, $width = null, $class = null, $renderer = null) {
        try {
            $this->headers[$fieldName] = ['label' => $label];
            if ($width) {
                $this->headers[$fieldName]['width'] = $width;
            }
            if ($class) {
                $this->headers[$fieldName]['class'] = $class;
            }
            if ($renderer) {
                $this->headersRenderers[$fieldName] = $renderer;
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function datagrid() {
        try {
            $html = '';
            if (count($this->headers)) {
                $html.= '<table class="table table-bordered table-condensed table-hover table-responsive table-striped">';
                $html.= $this->headers();
                $html.= $this->body();
                $html.= $this->footer();
                $html.= '</table>';
            }
            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function headers() {
        try {
            $html = '<thead><tr>';
            foreach ($this->headers as $header => $data) {
                $width = isset($data['width']) ? $data['width'] : null;
                $class = isset($data['class']) ? $data['class'] : null;
                $html.= "<th class='{$class}' width='{$width}'>{$data['label']}</th>";
            }
            $html.= '</tr></thead>';
            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function body() {
        try {
            $html = '<tbody>';
            foreach ($this->rows as $row) {
                $html.= '<tr>';
                foreach ($this->headers as $header => $data) {
                    $class = isset($data['class']) ? $data['class'] : null;
                    $value = isset($row[$header]) ? $row[$header] : null;
                    if (isset($this->headersRenderers[$header])) {
                        $value = call_user_func_array($this->headersRenderers[$header], [$value, $row]);
                    }
                    $html.= "<td class='{$class}'>{$value}</td>";
                }
                $html.= '</tr>';
            }
            if (!count($this->rows)) {
                $colspan = count($this->headers);
                $html.= "<tr class='warning'><td colspan='{$colspan}'>Nenhum resultado encontrado.</td></tr>";
            }
            $html.= '</tbody>';
            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function footer() {
        try {
            $html = '<tfoot><tr class="text-center">';
            $colspan = count($this->headers);
            $html.= "<td colspan='{$colspan}'>";

            $htmlPaginator = <<<HTML
                <ul class="pagination">
                    %s
                </ul>
HTML;
            $items = '';

            $leftDisabled = $this->active <= 1 ? "class='disabled'" : '';
            $leftPage = $this->active - 1 > 0 ? $this->active - 1 : 1;
            $items.= "<li {$leftDisabled}><a data-page='{$leftPage}' data-form='{$this->formSelector}'>{$this->paginatorLeft}</a></li>";

            foreach ($this->items as $page) {
                $active = $page == $this->active ? "class='active'" : '';
                $items.= "<li {$active}><a data-page='{$page}' data-form='{$this->formSelector}'>{$page}</a></li>";
            }

            $rightDisabled = $this->active == $this->totalPages ? "class='disabled'" : '';
            $rightPage = $this->active + 1 < $this->totalPages ? $this->active + 1 : $this->totalPages;
            $items.= "<li {$rightDisabled}><a data-page='{$rightPage}' data-form='{$this->formSelector}'>{$this->paginatorRight}</a></li>";

            $html.= sprintf($htmlPaginator, $items);
            $html.= '<br class="clearfix" />';
            $html.= "<div class='bg-info img-rounded col-xs-2 col-xs-offset-5'>Total: {$this->totalItems}</div>";
            $html.= '</td></tr></tfoot>';

            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
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
        return $this->datagrid();
    }

}
