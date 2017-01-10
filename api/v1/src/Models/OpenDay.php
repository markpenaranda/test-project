<?php

namespace App\Models;

use PDO;
use App\Models\OpenDayTimeBreakdown;

class OpenDay
{

  protected $currentRatePerHour = "100";
  protected $tableName = "i_openday";

  public function __construct(PDO $db)
  {
      $this->db = $db;
  }

 
  public function getCurrentRatePerHour() {
    return $this->currentRatePerHour;
  }

  public function create($inputArray, $timeRange, $jobs)
  {
    $page = $this->getUserPageId($inputArray['created_by_user_id']);
    $this->db->beginTransaction();
    try {

      $inputArray['openday_id'] = substr(uniqid(), 0, 8);
      $inputArray['event_date'] = date("Y-m-d", strtotime($inputArray['event_date']));
      $inputArray['rate_per_hour'] = $this->currentRatePerHour;
      $inputArray['amount'] = (float) $this->computeTotalHours($timeRange, $inputArray['time_interval_per_candidate']) * $this->currentRatePerHour;
      $inputArray['date_created'] = date('Y-m-d H:i:s');
      $inputArray['date_updated'] = date('Y-m-d H:i:s');
      $inputArray['page_id'] = $page['page_id'];
      $timeInterval = $inputArray['time_interval_per_candidate'];

     $opendayInsertStatement = $this->prepareInsertStatement("i_openday",$inputArray);

     $timeBreakDownData = $this->buildSaveTimeBreakDownData($timeRange, $timeInterval, $inputArray['openday_id']);
     $opendayTimeBreakDownStatement = $this->prepareMultiInsertStatement("i_openday_time_breakdown", $timeBreakDownData);

     $jobsData = $this->buildJobsData($jobs, $inputArray['openday_id']);
     $opendayJobsStatement = $this->prepareMultiInsertStatement("i_openday_link_job", $jobsData);

     $timeData = $this->buildTimeData($timeRange, $inputArray['openday_id']);
     $opendayTimeStatement = $this->prepareInsertStatement("i_openday_time", $timeData);

    
     $opendayInsertStatement->execute();
     $opendayTimeBreakDownStatement->execute();
     $opendayJobsStatement->execute();
     $opendayTimeStatement->execute();

      $this->db->commit();

      return $inputArray['openday_id'];
    }
    catch(PDOException $e) {
      $this->db->rollBack();
      return false;
    }
  }

  public function getById($opendayId)
  {
    try {

      $sql = "
        SELECT openday.*, page.page_name as page_name, page.page_id, openday_time.start_time, openday_time.end_time FROM i_openday as openday 
        JOIN i_page as page on page.page_id = openday.page_id 
        JOIN i_openday_time as openday_time on openday_time.openday_id = openday.openday_id 
        WHERE openday.openday_id = '$opendayId' limit 1;
      ";
      $statement = $this->db->prepare($sql);
      $statement->execute();
      $openday = $statement->fetch();

      return $openday;

    }
    catch(PDOException $e) {
      return $e;
    }
  }


  public function getTimeBreakDownByOpendayId($opendayId)
  {
    try{
      $sql = "
        SELECT * FROM i_openday_time_breakdown where openday_id = '$opendayId';
      ";
      $statement = $this->db->prepare($sql);
      $statement->execute();

      return $statement->fetchAll();
    }
    catch(PDOException $e) {
      return $e;
    }
  }

  public function getJobsByOpendayId($opendayId)
  {
     try{
      $sql = "
        SELECT * FROM `i_openday_link_job` as link_job 
        JOIN i_job_post as job on link_job.job_post_id = job.job_post_id
        WHERE link_job.openday_id = '$opendayId'
      ";
      $statement = $this->db->prepare($sql);
      $statement->execute();

      return $statement->fetchAll();
    }
    catch(PDOException $e) {
      return $e;
    }   
  }

  public function search($query)
  {
    try {
      $sql = "
        SELECT * FROM i_openday WHERE is_deleted='0' AND MATCH (event_name, introduction)
             AGAINST ('{$query}' IN BOOLEAN MODE)
      ";

      $statement = $this->db->prepare($sql);

      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);

      $results = $statement->fetchAll();

      return $results;
    }
    catch(PDOException $e){
      return $e;
    }
  }

  public function join(
    $opendayId, 
    $userId, 
    $coverLetter, 
    $timeBreakdownId, 
    $timeStart, 
    $timeEnd)
  {

     $user  = $this->getUserById($userId);
     $isScheduled = (boolean) $timeBreakdownId;
     $timeStart = date("H:i:s", strtotime($timeStart));
     $timeEnd = date("H:i:s", strtotime($timeEnd));
  
     $this->db->beginTransaction();
     try {
       $joinStatement = $this->prepareInsertStatement("i_openday_attendees", [
        'openday_id'    =>    $opendayId,
        'user_id'       =>    $userId,
        'cover_letter'  =>    $coverLetter,
        'is_scheduled'  =>    $isScheduled,
        'schedule_time_start' => $timeStart,
        'schedule_time_end' => $timeEnd,
        'email' => $user['primary_email'],
        'cv' => $user['cv'],
        'phone' => $user['primary_mobile'],
        'date_joined' => date("Y-m-d H:i:s")
        ]);

        $joinStatement->execute();

        if($isScheduled) {
          
          $updateTimeBreakDownStatement = $this->prepareUpdateStatement("i_openday_time_breakdown", [ "time_breakdown_id" => $timeBreakdownId], [
            'scheduled_user_id' => $userId,
            'is_filled'         => 1,
            'date_filled'       => date('Y-m-d H:i:s'),
            'date_updated'      => date('Y-m-d H:i:s')
          ]);

          $updateTimeBreakDownStatement->execute();
        }
       
        $this->db->commit();
        return true;
     }
     catch(PDOEXception $e) {
       $this->rollBack();
       return $e;
     }    
  }

  public function getOpendayByAttendeeUserId($userId, $status) 
  {
    $sql = "
      SELECT * FROM i_openday_attendees as attendees
      JOIN i_openday as openday on openday.openday_id = attendees.openday_id
      WHERE attendees.user_id = '$userId'
      AND attendees.status = '$status'
    ";
    try {

      $statement = $this->db->prepare($sql);

      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);

      $results = $statement->fetchAll();

      return $results;
    }
    catch(PDOException $e){
      return $e;
    }

  }

  public function getSuggestedUsersByOpendayId($opendayId)
  {
    $jobs = $this->getJobsByOpendayId($opendayId);
    $q = "";
    foreach($jobs as $job) {
      $q .= " " . $job['job_title'];
    }
    try {
      $sql = '
        SELECT * FROM 
        i_users_object_data WHERE 
        MATCH ( personal_info ) 
        AGAINST ("{$q}" IN BOOLEAN MODE);
      ';
      $statement = $this->db->prepare($sql);

      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);

      $results = $statement->fetchAll();

      return $results;
    }
    catch(PDOException $e){
      return $e;
    }
  }

  public function getCandidates($opendayId, $isScheduled) {
    $sql = "
      SELECT * FROM i_openday_attendees as attendees
      JOIN i_users as user on attendees.user_id = users.user_id
      WHERE attendees.openday_id = '$opendayId'
      AND attendees.is_scheduled = '$isScheduled '
    ";
    try {

      $statement = $this->db->prepare($sql);

      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);

      $results = $statement->fetchAll();

      return $results;
    }
    catch(PDOException $e){
      return $e;
    }
  }

  public function getOpendayByCreatedByUserId ($userId)
  {
    $sql = "
      SELECT * FROM i_openday WHERE created_by_user_id = '$userId'
    ";
    try {

      $statement = $this->db->prepare($sql);

      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);

      $results = $statement->fetchAll();

      return $results;
    }
    catch(PDOException $e){
      return $e;
    }
  }

  public function getTimeBreakdown($timeBreakdownId)
  {
    $sql = "
      SELECT * FROM i_openday_time_breakdown 
      WHERE time_breakdown_id = '$timeBreakdownId'
      LIMIT 1
    ";
    try {
      $statement = $this->db->prepare($sql);
      $statement->execute();
      $timeBreakdown = $statement->fetch();

      return $timeBreakdown;

    }
    catch(PDOException $e) {
      return $e;
    }

  }
  // Private Functions

  private function prepareInsertStatement($tableName, $createArray)
  {
      $columns = array_keys($createArray);
      $tableColumn = implode(",", $columns);
      $prepCol = $columns;
      array_walk($prepCol, function(&$item) { $item = ':'.$item; });
      $tablePrepCol = implode(",", $prepCol);

      $values = array_values($createArray);

      $insertSqlCommand = "INSERT INTO $tableName ($tableColumn) VALUES ($tablePrepCol);";
      $statement = $this->db->prepare($insertSqlCommand);
      foreach ($createArray as $key => $value) {
        $statement->bindValue(':' . $key, $value);
      }

      return $statement;
  }

  function prepareMultiInsertStatement($tableName, $data){
    
    //Will contain SQL snippets.
    $rowsSQL = array();
 
    //Will contain the values that we need to bind.
    $toBind = array();
    
    //Get a list of column names to use in the SQL statement.
    $columnNames = array_keys($data[0]);
 
    //Loop through our $data array.
    foreach($data as $arrayIndex => $row){
        $params = array();
        foreach($row as $columnName => $columnValue){
            $param = ":" . $columnName . $arrayIndex;
            $params[] = $param;
            $toBind[$param] = $columnValue; 
        }
        $rowsSQL[] = "(" . implode(", ", $params) . ")";
    }
    
    //Construct our SQL statement
    $sql = "INSERT INTO $tableName (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
 
    //Prepare our PDO statement.
    $pdoStatement = $this->db->prepare($sql);
 
    //Bind our values.
    foreach($toBind as $param => $val){
        $pdoStatement->bindValue($param, $val);
    }
    
    //Execute our statement (i.e. insert the data).
    return $pdoStatement;
}

private function prepareUpdateStatement ($tableName, $whereArray, $updateArray) 
{
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

      $updateSqlCommand = "UPDATE $tableName SET $setSQL where $whereSQL;";

      $statement = $this->db->prepare($updateSqlCommand);
      foreach ($whereArray as $key => $value) {
        $statement->bindValue(':' . $key . "", $value);
      }
      foreach ($updateArray as $key => $value) {
        $statement->bindValue(':' . $key, $value);
      }
      return $statement; 
}
  private function createSplitTimeArray($startTime, $endTime, $split)
  {
    $startTime = date("H:i", strtotime($startTime));
    $endTime = date("H:i", strtotime($endTime));

    $timeArray = [];
    do {
      $segmentStartTime = date("H:i", strtotime($startTime));
      $segmentEndTime = date("H:i", strtotime('+'. $split . ' minutes', strtotime($segmentStartTime)));
      $timeSegment = ['start' => $segmentStartTime, 'end' => $segmentEndTime];
      if($endTime >= $segmentEndTime) {
        array_push($timeArray, $timeSegment);
      }

      $startTime = $segmentEndTime;
    } while ($startTime < $endTime);

    return $timeArray;
  }

  public function computeTotalHours(array $timeRange, $timeInterval)
  {
     $breakdownCount = 0;
     foreach ($timeRange as $range) {
        $timeBreakDown = $this->createSplitTimeArray(
          $range['start'],
          $range['end'],
          $timeInterval
        );

        $breakdownCount += count($timeBreakDown);

     }

     $totalMinutes = $breakdownCount * $timeInterval;
     $totalHours = $totalMinutes/60;

     return $totalHours;

  }




  private function buildSaveTimeBreakDownData(array $timeRange, $timeInterval, $opendayId)
  {
     $data = [];
     foreach ($timeRange as $range) {

      $timeBreakDown = $this->createSplitTimeArray(
        $range['start'],
        $range['end'],
        $timeInterval
      );
      foreach ($timeBreakDown as $segment) {
        array_push($data, [
          'openday_id' => $opendayId,
          'time_start' => $segment['start'],
          'time_end' => $segment['end']
        ]);
        }
      }
      return $data;   
  }

  private function buildJobsData(array $jobs, $opendayId)
  {
    $data = [];
    foreach ($jobs as $job) {
        array_push($data,[
          'openday_id' => $opendayId,
          'job_post_id' => $job
        ]);
      }
    return $data;
  }

  private function buildTimeData($timerange, $opendayId)
  {
    $startTime = current($timerange)['start'];
    $endTime   = end($timerange)['end'];


    return [
      'openday_id' => $opendayId,
      'start_time' => date("H:i:s", strtotime($startTime)),
      'end_time'   => date("H:i:s", strtotime($endTime))
    ];
  }

  private function getUserPageId($userId)
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

  private function getUserById($userId)
  {
    try {
      $sql = "
        SELECT * FROM i_users where user_id = '$userId' limit 1
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
