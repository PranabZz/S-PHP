<?php

namespace Sphp\Core;
use Sphp\Core\Database;


/* TODO */

class Models
{
    protected $table;

    protected $env;

    protected $db;

    protected $fillables = [];

    protected $hidden_fields;

    public function __construct()
    {
        $this->env = require('../app/config/config.php');
        $this->db = new Database($this->env);
    }

    public function create($request)
    {
        try {
            $data = array_intersect_key($request, array_flip($this->fillables));
    
            if (empty($data)) {
                throw new \Exception("No valid fields provided for insertion.");
            }
    
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
    
            $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
    
            $result = $this->db->query($query, array_values($data));
    
            return $result;
        } catch (\Exception $e) {
           dd($e->getMessage());
        }
    }

    public function update($request, $id)
    {
        $data = array_intersect_key($request, array_flip($this->fillables));
        if (empty($data)) {
            throw new \Exception("No valid fields provided for updating.");
        }

        $updateFields = implode(", ", array_map(function ($field) {
            return "$field = ?";
        }, array_keys($data)));

        $query = "UPDATE {$this->table} SET $updateFields WHERE id = ?";

        $params = array_values($data);
        $params[] = $id;

        $this->db->query($query, $params);


    }

    public function delete($id)
    {
        if (empty($id)) {
            throw new \Exception("No valid fields provided for updating.");
        }

        $query = "DELETE FROM {$this->table} WHERE id = ?";

        $params[] = $id;

        $this->db->query($query, $params);

    }
    public function select(array $columns, array $where = [], string $orderBy = '', int $limit = 0)
    {
        if (empty($columns)) {
            throw new \Exception("No valid fields provided for selection.");
        }

        $columns_in_string = implode(', ', $columns);

        $where_string = '';
        $params = [];
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $column => $value) {
                $conditions[] = "$column = ?";
                $params[] = $value;
            }
            $where_string = 'WHERE ' . implode(' AND ', $conditions);
        }

        $order_by_string = $orderBy ? "ORDER BY $orderBy" : '';
        $limit_string = $limit > 0 ? "LIMIT $limit" : '';

        $query = "SELECT {$columns_in_string} FROM {$this->table} {$where_string} {$order_by_string} {$limit_string}";

        // Optional: Clean up whitespace
        $query = preg_replace('/\s+/', ' ', trim($query));

        return $this->db->query($query, $params);
    }



    public function findByID($id)
    {

        if (empty($id)) {
            throw new \Exception("No valid fields provided for selection.");
        }

        $query = "SELECT * FROM {$this->table} WHERE id = $id";
        $result = $this->db->query($query);

        return $result[0];

    }
}