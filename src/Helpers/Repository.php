<?php

namespace ModPath\Helpers;

use ModPath\Helpers\Database;

class Repository
{
    public string $table;
    public array $fields;

    public function __construct(
        public Database $db = new Database()
    ) {}

    public function getAll(array $fields = [], array $filters = [], int $page = 1, int $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $filters['LIMIT'] = [$offset, $perPage];
        unset($filters["page"], $filters["perPage"]);
        $data = $this->db->connect()->select(
            $this->table,
            !empty($fields) ? $fields : $this->fields,
            $filters
        );
        unset($filters['LIMIT']);
        $total = $this->db->connect()->count($this->table, '*', $filters);
        $lastPage = (int) ceil($total / $perPage);
        $start = max(1, $page - 5);
        $end = min($lastPage, $page + 5);
        $pages = [];
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = [
                'number' => $i,
                'is_current' => $i === $page
            ];
        }
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage,
            'pages' => $pages
        ];
    }

    public function getById(int $id, array $fields = [])
    {
        return $this->db->connect()->select($this->table, !empty($fields) ? $fields : $this->fields, [
            "id" => $id
        ]);
    }

    public function find(array | string $fields = '', array $filter = [])
    {
        if ($fields === '*') {
            return $this->db->connect()->get($this->table, $fields, $filter);
        }
        return $this->db->connect()->get($this->table, !empty($fields) ? $fields : $this->fields, $filter);
    }

    public function create(array $fields)
    {
        return $this->db->connect()->insert($this->table, $fields);
    }

    public function update(int $id, array $fields)
    {
        return $this->db->connect()->update($this->table, $fields, [
            "id" => $id
        ]);
    }

    public function delete(int $id)
    {
        return $this->db->connect()->delete($this->table, [
            "id" => $id
        ]);
    }
}
