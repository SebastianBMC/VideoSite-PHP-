<?php 
class Entity
{
    private $connection, $sqlData;

    public function __construct($connection, $input)
    {
        $this->connection = $connection;


        if(is_array($input))
        {
            $this->sqlData = $input;
        }
        else
        {
            $query = $this->connection->prepare("SELECT * FROM entities WHERE id=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
        
    }

    public function getId()
    {
        return $this->sqlData["id"]; 
    }
    public function getName()
    {
        return $this->sqlData["name"]; 
    }
    public function getThumbnail()
    {
        return $this->sqlData["thumbnail"]; 
    }
    public function getPreview()
    {
        return $this->sqlData["preview"]; 
    }



}


?>