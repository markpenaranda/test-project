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
                JOIN i_city ON i_users.city_id = i_city.city_id
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

    public function getUserPage ($userId) 
    {
       try {
        $sql = "
            SELECT *  FROM i_page_connection as con
            JOIN i_page as page on page.page_id = con.page_id
             JOIN i_city ON page.city_id = i_city.city_id
            where user_id = '$userId' limit 1
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetch();

        } 
        catch(PDOException $e) {
        return $e;
        } 
    }

    public function getUserPageId($userId)
    {
        try {
        $sql = "
            SELECT page_id  FROM i_page_connection where user_id = '$userId' limit 1
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetch();

        } 
        catch(PDOException $e) {
        return $e;
        }
    }

    public function getTimeZoneByUserId($userId)
    {
        $sql = "SELECT * FROM i_users
            JOIN i_city ON i_users.city_id = i_city.city_id
            WHERE i_users.user_id='$userId'
            ";

        try {


        $statement = $this->db->prepare($sql);
        $statement->execute();
        $city = $statement->fetch();

        $timezone = $this->getTimeZoneByCoordinates(
                              $city['longitude'],
                              $city['latitude'],
                              "AIzaSyBxkwfU2Xdm9pT6J1xGVmBOca9J04TeirE"
                        );

        return $timezone;

        }
        catch(PDOException $e) {
             return $e;
        }

    }

    private function getTimeZoneByCoordinates($long, $lat, $googleMapApiKey)
    {
        $url = "https://maps.googleapis.com/maps/api/timezone/json?location=". $lat .",". $long ."&timestamp=" . time() ."&key=" . $googleMapApiKey;

        $curl = curl_init();

        $action_url = $url;



        curl_setopt_array($curl, array(
              CURLOPT_RETURNTRANSFER => 1,
              CURLOPT_URL => $url
          ));
        $resp = curl_exec($curl);


           $result = json_decode($resp, true);

        return $result;

    }
}
