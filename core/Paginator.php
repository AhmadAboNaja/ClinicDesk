<?php

class Paginator
{
    private int $totalItems;
    private int $perPage;
    private int $currentPage;

    public function __construct(int $totalItems, int $perPage, int $currentPage)
    {
        $this->totalItems = max(0, $totalItems);
        $this->perPage = max(1, $perPage);
        $this->currentPage = max(1, $currentPage);
    }
    public function render(): string
    {
        $totalPages = $this->totalPages();

        if ($totalPages <= 1) {
            return '';
        }

        $query = $_GET;
        unset($query['page_num']);

        $html = '<ul class="pagination pagination-sm m-0 float-right">';

        if ($this->hasPrev()) {

            $query['page_num'] = $this->currentPage - 1;

            $html .= '<li class="page-item">
                    <a class="page-link" href="?' .
                http_build_query($query) .
                '">&laquo;</a>
                  </li>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {

            $query['page_num'] = $i;

            $active = ($i === $this->currentPage)
                ? ' active'
                : '';

            $html .= '<li class="page-item' . $active . '">
                    <a class="page-link"
                       href="?' . http_build_query($query) . '">' .
                $i .
                '</a>
                  </li>';
        }

        if ($this->hasNext()) {

            $query['page_num'] = $this->currentPage + 1;

            $html .= '<li class="page-item">
                    <a class="page-link"
                       href="?' . http_build_query($query) .
                '">&raquo;</a>
                  </li>';
        }

        $html .= '</ul>';

        return $html;
    }
    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function totalPages(): int
    {
        return (int) ceil($this->totalItems / $this->perPage);
    }

    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages();
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }
}
