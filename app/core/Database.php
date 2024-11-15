<?php

/* 
  Database class which connects our project to the database 
*/

namespace App\Core;

class Database
{

  public $connection;

  // Everytime we make a database object the contructor will make a database connection to the host and database wiht the username and password provided

  public function __construct($config)
  {
    $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['database'];

    $this->connection = new \PDO($dsn, 'root', 'root');
  }

  /* 
    Query function takes two parameter 

      $parameter['query'] => query such as 'SELECT * FROM `users`'
      $paramerter['parms'] => any paramerter or variables that are comming from the user end
  */

  public function query($query, $params = array())
  {
    $statement = $this->connection->prepare($query);

    $statement->execute($params);

    return $statement->fetchAll(\PDO::FETCH_ASSOC);
  }
}
