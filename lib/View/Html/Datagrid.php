<?php

namespace KF\Lib\View\Html;

class Datagrid {

    /**
     * @var integer
     */
    public $rowPerPage = 10;

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
            //$leftPage = $this->active - 1 > 0 ? $this->active - 1 : 1;
            $leftPage = 1;
            $items.= "<li {$leftDisabled}><a data-page='{$leftPage}' data-form='{$this->formSelector}'>{$this->paginatorLeft}</a></li>";

            foreach ($this->items as $page) {
                $active = $page == $this->active ? "class='active'" : '';
                $items.= "<li {$active}><a data-page='{$page}' data-form='{$this->formSelector}'>{$page}</a></li>";
            }

            $rightDisabled = $this->active == $this->totalPages ? "class='disabled'" : '';
            //$rightPage = $this->active + 1 < $this->totalPages ? $this->active + 1 : $this->totalPages;
            $rightPage = $this->totalPages;
            $items.= "<li {$rightDisabled}><a data-page='{$rightPage}' data-form='{$this->formSelector}'>{$this->paginatorRight}</a></li>";

            $html.= sprintf($htmlPaginator, $items);
            $html.= '<br class="clearfix" />';
            $html.= "<div class='bg-info img-rounded col-xs-2 col-xs-offset-5'>Total: {$this->totalItems} - Página: {$this->active}/{$this->totalPages}</div>";
            $html.= '</td></tr></tfoot>';

            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function setRows($rows = array()) {
        try {
            $this->rows = $rows;
            if (count($this->rows) && isset($this->rows[array_keys($this->rows)[0]]) && isset($this->rows[array_keys($this->rows)[0]]['total'])) {
                $this->active = $this->active == 0 ? 1 : $this->active;
                $this->totalItems = $this->rows[array_keys($rows)[0]]['total'];

                $maxItens = $this->numItems;
                $this->totalPages = ceil($this->totalItems / $this->rowPerPage);

                $itensPerSide = intval($maxItens / 2);

                for ($i = $this->active - $itensPerSide; $i < $this->active; $i++) {
                    if ($i > 0 && $i <= $this->totalPages) {
                        $this->items[$i] = $i;
                    }
                }

                if ($this->active <= $this->totalPages) {
                    $this->items[$this->active] = $this->active;
                }

                for ($i = $this->active + 1; $i <= $this->active + $itensPerSide; $i++) {
                    if ($i <= $this->totalPages) {
                        $this->items[$i] = $i;
                    }
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function __toString() {
        try {
            return $this->datagrid();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
