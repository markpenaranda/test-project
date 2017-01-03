<?php

namespace App\Models;

use PDO;

abstract class BaseModel
{
  public $db, $sqlStatement, $sqlCountStatement, $softDelete, $joinActivated;

  protected $tableName, $filterable, $visible, $created_at, $updated_at;




  public function __construct(PDO $db)
  {
      $this->db = $db;
  }

  public function setVisible($columns)
  {
      $this->visible = (count($columns) > 0) ? $columns :$this->visible;
  }
  /*
  Compose an sql query based on $filter array
  @param filterArray = ['dbColumn' => 'value']
  */
  public function filter($filterArray, $withDeleted = false)
  {
    if(!$withDeleted && $this->softDelete) {
      $this->sqlStatement = "SELECT * FROM $this->tableName WHERE is_deleted='0'";
    }
    else {
      $this->sqlStatement = "SELECT * FROM $this->tableName";
    }

    $index= 0;

    foreach ($filterArray as $column => $value) {
        if(in_array($column, $this->filterable)) {
          if($index == 0) {
            if(!$withDeleted && $this->softDelete) {
              $this->sqlStatement .= " AND ";
            }
            else {
              $this->sqlStatement .= " WHERE ";
            }
          }

          if($index > 0 && $index < count($filterArray)) { $this->sqlStatement .= " AND "; }
          $this->sqlStatement .= "$column = '$value'";
          $index++;
        }
    }

    return $this;
  }

  public function where($column, $value)
  {

    $this->filter([$column => $value]);
    return $this;
  }

  public function search($searchArray)
  {
    var_dump($searchArray);
    die();
  }

  // Fetch All Record without Pagination
  public function all()
  {
    try {
        if($this->visible) {
          $visibleStatement = implode(",", $this->visible);
          $this->sqlStatement = str_replace("*", $visibleStatement, $this->sqlStatement);
        }
        $statement = $this->db->prepare($this->sqlStatement);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $results = $statement->fetchAll();

        return $results;

    } catch(PDOException $e) {
        return $e;
    }
  }

  public function get($limit = 0)
  {
    try {
        $results = [];

        if($this->joinActivated) {
           $this->sqlStatement = str_replace("*", "l.*, r.*", $this->sqlStatement);
        }
        else if($this->visible && $this->joinActivated == false) {
          $visibleStatement = implode(",", $this->visible);
          $this->sqlStatement = str_replace("*", $visibleStatement, $this->sqlStatement);
        }

        if($limit) {
        $this->sqlStatement .= " LIMIT 0," . $limit;
          
        }

        $statement = $this->db->prepare($this->sqlStatement);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $results = $statement->fetchAll();
        if($limit == 1) {
          return $results[0];
        }

        return $results;
        // return null;

    } catch(PDOException $e) {
        return $e;
    }
  }

  // Fetch All Record with Pagination
  public function paginate($currentPage = 1, $itemPerPage = 15)
  {
    try {
        $limit = ($itemPerPage != "NULL") ? $itemPerPage : 15;
        $currentPage = ($currentPage != "NULL" && $currentPage != NULL) ? $currentPage : 1;
        $offset = ($currentPage - 1) * $limit;
        // Count Total
        $count = $this->count();

        // Prep
        $this->sqlStatement  = $this->sqlStatement .= " LIMIT $limit OFFSET $offset";
        if($this->visible) {
          $visibleStatement = implode(",", $this->visible);
          $this->sqlStatement = str_replace("*", $visibleStatement, $this->sqlStatement);
        }
        $statement = $this->db->prepare($this->sqlStatement);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $results = $statement->fetchAll();
        $hasNextPage = ((count($results) * $currentPage) < $count ) ? true : false;

        return [
                'data'  => $results,
                'size'  => count($results),
                'total' => $count,
                'currentPage' => (int) $currentPage,
                'hasNextPage' => $hasNextPage
              ];

    } catch(PDOException $e) {
        return $e;
    }
  }

  public function count()
  {
    $countSqlStatement = str_replace("SELECT * FROM", "SELECT COUNT(*) as count FROM", $this->sqlStatement);
    $statement = $this->db->prepare($countSqlStatement);
    $statement->execute(['count']);
    $count = $statement->fetchColumn();
    return (int) $count;
  }

  public function create($createArray)
  {
    try {
      if($this->created_at) {
        $createArray[$this->created_at] = date('Y-m-d H:i:s');
      }
      if($this->updated_at) {
        $createArray[$this->updated_at] = date('Y-m-d H:i:s');
      }

      $columns = array_keys($createArray);
      $tableColumn = implode(",", $columns);
      $prepCol = $columns;
      array_walk($prepCol, function(&$item) { $item = ':'.$item; });
      $tablePrepCol = implode(",", $prepCol);

      $values = array_values($createArray);

      $insertSqlCommand = "INSERT INTO $this->tableName ($tableColumn) VALUES ($tablePrepCol);";
      $statement = $this->db->prepare($insertSqlCommand);
      foreach ($createArray as $key => $value) {
        $statement->bindValue(':' . $key, $value);
      }
      $statement->execute();
      return $createArray['openday_id'];
    }

    catch(PDOException $e) {
        return $e;
    }
  }

  public function update($whereArray, $updateArray)
  {
    try {

      if($this->updated_at) {
        $updateArray[$this->updated_at] = date('Y-m-d H:i:s');
      }

      $setSQL = [];
      foreach ($updateArray as $key => $value) {
        array_push($setSQL, "$key = :$key");
      }
      $setSQL = implode(",", $setSQL);

      $whereSQL = [];
      foreach ($whereArray as $key => $value) {
        array_push($whereSQL, "$key = :$key");
      }
      $whereSQL = implode(",", $whereSQL);

      $updateSqlCommand = "UPDATE $this->tableName SET $setSQL where $whereSQL;";

      $statement = $this->db->prepare($updateSqlCommand);
      foreach ($whereArray as $key => $value) {
        $statement->bindValue(':' . $key . "", $value);
      }
      foreach ($updateArray as $key => $value) {
        $statement->bindValue(':' . $key, $value);
      }

      return $statement->execute();

    }

    catch(PDOException $e) {
        return $e;
    }
  }

  public function delete($whereArray, $isTrashed = true)
  {
    if($this->softDelete && $isTrashed){
      $this->update($whereArray, [$this->softDelete => 1]);
    }
    else {
      $whereSQL = [];
      foreach ($whereArray as $key => $value) {
        array_push($whereSQL, "$key = :$key");
      }
      $whereSQL = implode(",", $whereSQL);

      $deleteSQLCommand = "DELETE FROM $this->tableName WHERE $whereSQL;";

      $statement = $this->db->prepare($deleteSQLCommand);
      foreach ($whereArray as $key => $value) {
        $statement->bindValue(':' . $key . "", $value);
      }

      return $statement->execute();
    }
  }

  public function fullTextSearch($query, $columns)
  {
      $columnMatch = implode(",", $columns);
      $this->sqlStatement =  "SELECT * FROM {$this->tableName} WHERE is_deleted='0' AND MATCH ({$columnMatch})
             AGAINST ('{$query}' IN BOOLEAN MODE)";



      $statement = $this->db->prepare($this->sqlStatement);

      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);

      $results = $statement->fetchAll();

      return $results;

  }

  public function join ($table, $leftColumn, $rightColumn) 
  {
    $this->joinActivated = true;
    $this->sqlStatement = str_replace($this->tableName, $this->tableName . " as l join " . $table . " as r on l." . $leftColumn . " = r." . $rightColumn, $this->sqlStatement);

    return $this;
  }



  


}
