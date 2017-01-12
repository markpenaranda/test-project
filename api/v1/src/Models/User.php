<?php

namespace App\Models;

use PDO;

class User 
{
    protected $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getUsersByPageId($pageId) 
    {
        try {
            $sql = "
                SELECT * from i_page_connection as c
                JOIN i_users as u on u.user_id = c.user_id
                WHERE c.page_id = '$pageId'
            ";
            $statement = $this->db->prepare($sql);

            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);

            return $statement->fetchAll();

        } catch(PDOException $e) {
            return $e;
        }
    }

    public function getUserById($userId)
    {
        try {
            $sql = "
                SELECT * from i_users 
                JOIN i_country on i_users.nationality = i_country.country_id
                WHERE i_users.user_id = '$userId'
            ";
            $statement = $this->db->prepare($sql);

            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);

            return $statement->fetch();

        } catch(PDOException $e) {
            return $e;
        }
    }

    public function getUserPageId($userId)
    {
        try {
        $sql = "
            SELECT page_id FROM i_page_connection where user_id = '$userId' limit 1
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetch();

        } 
        catch(PDOException $e) {
        return $e;
        }
    }
}
