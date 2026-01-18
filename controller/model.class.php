<?php

class Model
{
    // Refer to database connection
    private $db;

    // Instantiate object with database connection
    public function __construct($db_conn)
    {
        $this->db = $db_conn;
    }
    public function select_all($tablename)
    {
        try {
            // Define query to insert values into the users table
            $sql = "SELECT * FROM " . $tablename . "";

            // Prepare the statement
            $query = $this->db->prepare($sql);

            // Bind parameters

            // Execute the query
            $query->execute();

            // Return row as an array indexed by both column name
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
            return $rows;
        } catch (PDOException $e) {
            array_push($errors, $e->getMessage());
        }
    }


    public function insert_data($table, $data)
    {
        if (!empty($data) && is_array($data)) {
            $columns = '';
            $values  = '';
            $i = 0;


            $columnString = implode(',', array_keys($data));
            $valueString = ":" . implode(',:', array_keys($data));
            $sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
            $query = $this->db->prepare($sql);
            foreach ($data as $key => $val) {
                $query->bindValue(':' . $key, $val);
            }
            $insert = $query->execute();
            return $insert ? $this->db->lastInsertId() : false;
        } else {
            return false;
        }
    }


    public function getRows($table, $conditions = array())
    {
        $sql = 'SELECT ';
        $sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
        $sql .= ' FROM ' . $table;
    
        // Handle joins
        if (array_key_exists("join", $conditions)) {
            $sql .= ' INNER JOIN ' . $conditions['join'];
        }
        if (array_key_exists("leftjoin", $conditions)) {
            $sql .= ' LEFT JOIN ' . $conditions['leftjoin'];
        }
        if (array_key_exists("joinx", $conditions)) {
            foreach ($conditions['joinx'] as $key => $value) {
                $sql .= ' INNER JOIN ' . $key . $value;
            }
        }
        if (array_key_exists("joinl", $conditions)) {
            foreach ($conditions['joinl'] as $key => $value) {
                $sql .= ' LEFT JOIN ' . $key . $value;
            }
        }
    
        // Handle WHERE conditions
        $whereClauses = [];
    
        if (array_key_exists("where", $conditions)) {
            foreach ($conditions['where'] as $key => $value) {
                if (is_array($value)) {
                    // Handle IN clause
                    $placeholders = implode(',', array_map(fn($v) => $this->db->quote($v), $value));
                    $whereClauses[] = "$key IN ($placeholders)";
                } else {
                    $whereClauses[] = "$key = " . $this->db->quote($value);
                }
            }
        }
    
        // Support for raw WHERE conditions (e.g., subqueries)
        if (array_key_exists("where_raw", $conditions)) {
            $whereClauses[] = $conditions['where_raw'];
        }
    
        if (!empty($whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }
    
        // Additional conditional operators
        if (array_key_exists("where_not", $conditions)) {
            foreach ($conditions['where_not'] as $key => $value) {
                $sql .= " AND $key != " . $this->db->quote($value);
            }
        }
        if (array_key_exists("where_greater_equals", $conditions)) {
            foreach ($conditions['where_greater_equals'] as $key => $value) {
                $sql .= " AND $key >= " . $this->db->quote($value);
            }
        }
        if (array_key_exists("where_lesser_equals", $conditions)) {
            foreach ($conditions['where_lesser_equals'] as $key => $value) {
                $sql .= " AND $key <= " . $this->db->quote($value);
            }
        }
        if (array_key_exists("where_lesser", $conditions)) {
            foreach ($conditions['where_lesser'] as $key => $value) {
                $sql .= " AND $key < " . $this->db->quote($value);
            }
        }
        if (array_key_exists("where_greater", $conditions)) {
            foreach ($conditions['where_greater'] as $key => $value) {
                $sql .= " AND $key > " . $this->db->quote($value);
            }
        }
    
        // Handle GROUP BY, ORDER BY, LIMIT
        if (array_key_exists("group_by", $conditions)) {
            $sql .= ' GROUP BY ' . $conditions['group_by'];
        }
        if (array_key_exists("order_by", $conditions)) {
            $sql .= ' ORDER BY ' . $conditions['order_by'];
        }
        if (array_key_exists("limit", $conditions)) {
            if (array_key_exists("start", $conditions)) {
                $sql .= ' LIMIT ' . $conditions['start'] . ', ' . $conditions['limit'];
            } else {
                $sql .= ' LIMIT ' . $conditions['limit'];
            }
        }
    
        // Prepare and execute query
        $query = $this->db->prepare($sql);
        $query->execute();
    
        // Handle return type
        if (array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all') {
            switch ($conditions['return_type']) {
                case 'count':
                    return $query->rowCount();
                case 'single':
                    return $query->fetch(PDO::FETCH_ASSOC);
                default:
                    return false;
            }
        } else {
            return $query->rowCount() > 0 ? $query->fetchAll(PDO::FETCH_ASSOC) : false;
        }
    }
    
    

    public function countRows($table, $conditions = array())
    {
        $sql = 'SELECT COUNT(*) as total_row';
        $sql .= ' FROM ' . $table;
        if (array_key_exists("where", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }

        $query = $this->db->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $total_row = $row['total_row'];
        return $total_row;
    }

    public function upDate($table, $data, $conditions)
    {
        if (!empty($data) && is_array($data)) {
            $colvalSet = '';
            $whereSql = '';
            $i = 0;

            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colvalSet .= $pre . $key . "='" . $val . "'";
                $i++;
            }
            if (!empty($conditions) && is_array($conditions)) {
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach ($conditions as $key => $value) {
                    $pre = ($i > 0) ? ' AND ' : '';
                    $whereSql .= $pre . $key . " = '" . $value . "'";
                    $i++;
                }
            }
            $sql = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
            $query = $this->db->prepare($sql);
            $update = $query->execute();
            return $update ? $query->rowCount() : false;
        } else {
            return false;
        }
    }

    /* 
     * Delete data from the database 
     * @param string name of the table 
     * @param array where condition on deleting data 
     */
    public function delete($table, $conditions)
    {
        $whereSql = '';
        if (!empty($conditions) && is_array($conditions)) {
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach ($conditions as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $whereSql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }
        $sql = "DELETE FROM " . $table . $whereSql;
        $delete = $this->db->exec($sql);
        return $delete ? $delete : false;
    }
    // Log Out User
    public function log_out_user()
    {
        session_unset();
        session_destroy();
    }

    public function sumQuery($table, $column, $conditions = [])
    {
        $sql = "SELECT SUM($column) as total_sum FROM $table";

        if (array_key_exists("where", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " = :" . $key;
                $i++;
            }
        }

        $query = $this->db->prepare($sql);

        // Bind parameters
        if (array_key_exists("where", $conditions)) {
            foreach ($conditions['where'] as $key => $value) {
                $query->bindValue(':' . $key, $value);
            }
        }

        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total_sum'] ?? 0;
    }

    

}
