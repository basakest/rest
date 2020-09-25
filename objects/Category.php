<?php
class Category{
    
    private $conn;
    private $table_name = "categories";
 
    public $id;
    public $name;
    public $description;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }

    public function read()
    {
        $query = "select id, name, description from {$this->table_name} order by name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "insert into {$this->table_name} (name, description, created) values (:name, :description, :created)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created", $this->created);
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function delete()
    {
        if (!$this->findOne()) {
            return 'no record is found';
        }
        $query = "delete from {$this->table_name} where id = :id limit 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function update()
    {
        $query = "update {$this->table_name} set name = :name, description = :description where id = :id limit 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function findOne()
    {
        $query = "select * from {$this->table_name} where id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProducts()
    {
        if (!$this->findOne()) {
            return "false";
        }
        $query = "select {$this->table_name}.id as category_id2,
                         products.category_id, 
                         products.name, 
                         products.description, 
                         products.price 
                  from {$this->table_name} 
                  left join products 
                  on categories.id = category_id 
                  where categories.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        //return $stmt;
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
