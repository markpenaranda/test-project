<?php

namespace App\Models;

use PDO;
use Carbon\Carbon;

class Notification
{

  protected $tableName = "i_notifications";

  public function __construct(PDO $db)
  {
      $this->db = $db;
  }

  /**
   *
   * Return Latest Notifications
   * @param int|string $userId, App\Helpers\Paginate::init $paginate
   * @return object array
   */
  

  public function all($userId, $paginate)
  {
    $sql = "
      SELECT * FROM $this->tableName
      WHERE user_id = '$userId'
      ORDER BY date_created DESC
      LIMIT ". $paginate['skip'] .", " . $paginate['limit'] . "
    ";

    try {
      $statement = $this->db->prepare($sql);
      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);
      $results = $statement->fetchAll();

      return $results;

    } catch (PDOException $e) {
      return $e;
    }

  }

  /**
   *
   * Individual Saving of Notifications
   * @param int|string $userId, string $message, string $link
   * @return boolean
   */
  

  public function save($userId, $message, $title, $link)
  {
    $sql = "
      INSERT INTO $this->tableName (
        `user_id`,
        `message`,
        `title`,
        `link`,
        `date_created`
      ) VALUES (
        '$userId',
        '$message',
        '$title',
        '$link',
        NOW()
      )

    ";
    // var_dump($sql);
    // die();
   try {
      $statement = $this->db->prepare($sql);
      $statement->execute();
      return true;
    } catch (PDOException $e) {
      return $e;
    }

  }

  /**
   *
   * Transactional Saving of Notification 
   * @param object array notificationArray { userid, message, link }
   * @return boolean
   * 
   */
  
  public function transactionalSave($notificationArray)
  {
    $this->db->beginTransaction();

    try {
      foreach ($notificationArray as $notification) {
        $sql = "INSERT INTO $this->tableNAme (
        `user_id`,
        `message`,
        `link`,
        `date_created`,
          ) VALUES (
            '" . $notification['userId'] . "',
            '" . $notification['message'] . "',
            '" . $notification['link'] . "',
            NOW()
          )";

        $saveStatement = $this->db->prepare($sql);     
        $saveStatement->execute();
        }
      } catch(PDOException $e) {
        $this->db->rollBack();
      }

      $this->db->commit();
    

  }

  public function countUnread($userId)
  {
    $sql = "
      SELECT COUNT(*) as total FROM $this->tableName
      WHERE `user_id` = '$userId'
      AND `read` = '0'
    ";

    try {
      $statement = $this->db->prepare($sql);
      $statement->execute();
      $count = $statement->fetch();
      return $count['total'];
    } catch (PDOException $e) {
      return $e;
    }
  }

  public function setAsRead($notificationId)
  {
    $sql = "
      UPDATE $this->tableName SET `read` = 1
      WHERE `id` = $notificationId
    ";

    try {
      $statement = $this->db->prepare($sql);
      $statement->execute();
    } catch (PDOException $e) {
      return $e;
    }
  }

  public function setAllAsReadByUserId($userId)
  {
    $sql = "
      UPDATE $this->tableName SET `read` = 1
      WHERE `user_id` = '$userId'
    ";

    try {
      $statement = $this->db->prepare($sql);
      $statement->execute();
    } catch (PDOException $e) {
      return $e;
    }
  }

}
