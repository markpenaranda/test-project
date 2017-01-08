<?php
namespace App\Models;

use PDO;
class OpenDayTimeBreakdown
{
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }
  protected $tableName = "i_openday_time_breakdown";

  public function create ($inputArray) {
    try {

      $columns = array_keys($inputArray);
      $tableColumn = implode(",", $columns);
      $prepCol = $columns;
      array_walk($prepCol, function(&$item) { $item = ':'.$item; });
      $tablePrepCol = implode(",", $prepCol);

      $values = array_values($inputArray);

      $insertSqlCommand = "
        INSERT INTO bla$this->tableName ($tableColumn) VALUES ($tablePrepCol);
        ";

      $statement = $this->db->prepare($insertSqlCommand);
      foreach ($inputArray as $key => $value) {
        $statement->bindValue(':' . $key, $value);
      }

      $statement->execute();

      return true;
    }
    catch(PDOException $e) {
      $this->db->rollBack();
      throw $e;

    }
  }

}
