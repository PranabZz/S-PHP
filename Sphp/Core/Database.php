<?php

/* 
  Database class which connects our project to the database 
*/

namespace Sphp\Core;

class Database
{

  public $connection;

  // Everytime we make a database object the contructor will make a database connection to the host and database wiht the username and password provided

  public function __construct($config)
  {
    $dsn = 'mysql:host=' . $config['host'] . ';port='. $config['port'] .';dbname=' . $config['database'];

    $this->connection = new \PDO($dsn, $config['username'], $config['password']);
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
