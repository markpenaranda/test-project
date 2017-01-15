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

      $sqlInsert = "
          INSERT INTO i_openday (
            `openday_id`,
            `event_name`,
            `event_date`,
            `time_interval_per_candidate`,
            `introduction`,
            `in_charge_user_id`,
            `rate_per_hour`,
            `created_by_user_id`,
            `amount`,
            `date_created`,
            `date_updated`,
            `page_id`
          )
          VALUES (
             '". $inputArray['openday_id'] ."',
            '". $inputArray['event_name'] ."',
            '". $inputArray['event_date'] ."',
            '". $inputArray['time_interval_per_candidate'] ."',
            '". $inputArray['introduction'] ."',
            '". $inputArray['in_charge_user_id'] ."',
            '". $inputArray['rate_per_hour'] ."',
            '". $inputArray['created_by_user_id'] ."',
            '". $inputArray['amount'] ."',
            '". $inputArray['date_created'] ."',
            '". $inputArray['date_updated'] ."',
            '". $inputArray['page_id'] ."'
           
          )
        ";

     $opendayInsertStatement  = $this->db->prepare($sqlInsert);

     // Time Breakdown
     $timeBreakDownData = $this->buildSaveTimeBreakDownData($timeRange, $timeInterval, $inputArray['openday_id']);
   
    $timeBreakDownRowsSQL = array();
    $timeBreakDownToBind = array();
    $timeBreakDownColumnNames = array_keys($timeBreakDownData[0]);
    foreach($timeBreakDownData as $arrayIndex => $row){
        $params = array();
        foreach($row as $columnName => $columnValue){
            $param = ":" . $columnName . $arrayIndex;
            $params[] = $param;
            $timeBreakDownToBind[$param] = $columnValue; 
        }
        $timeBreakDownRowsSQL[] = "(" . implode(", ", $params) . ")";
    }
    $timeBreakDownSql = "INSERT INTO i_openday_time_breakdown (" . implode(", ", $timeBreakDownColumnNames) . ") VALUES " . implode(", ", $timeBreakDownRowsSQL);
  
    $opendayTimeBreakDownStatement = $this->db->prepare($timeBreakDownSql);
    foreach($timeBreakDownToBind as $param => $val){
        $opendayTimeBreakDownStatement->bindValue($param, $val);
    }
     // Openday Link Jobs
     $jobsData = $this->buildJobsData($jobs, $inputArray['openday_id']);

     $jobsRowsSQL = array();
    $jobsToBind = array();
    $jobsColumnNames = array_keys($jobsData[0]);
    foreach($jobsData as $arrayIndex => $row){
        $params = array();
        foreach($row as $columnName => $columnValue){
            $param = ":" . $columnName . $arrayIndex;
            $params[] = $param;
            $jobsToBind[$param] = $columnValue; 
        }
        $jobsRowsSQL[] = "(" . implode(", ", $params) . ")";
    }
    $jobsSql = "INSERT INTO i_openday_link_job (" . implode(", ", $jobsColumnNames) . ") VALUES " . implode(", ", $jobsRowsSQL);
    $opendayJobsStatement = $this->db->prepare($jobsSql);
    foreach($jobsToBind as $param => $val){
        $opendayJobsStatement->bindValue($param, $val);
    }

     
     // Openday Time
     $timeData = $this->buildTimeData($timeRange, $inputArray['openday_id']);
     $opendayTimeInsertSql = "
          INSERT INTO i_openday_time (
            `openday_id`,
            `start_time`,
            `end_time`
          )
          VALUES (
            '". $timeData['openday_id']  ."',
            '". $timeData['start_time'] ."',
            '". $timeData['end_time'] ."'
          )
        ";

    $opendayTimeStatement = $this->db->prepare($opendayTimeInsertSql);

    


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
        SELECT openday.*, in_charge.name as in_charge_name, page.page_name as page_name, page.page_id, openday_time.start_time, openday_time.end_time FROM i_openday as openday 
        JOIN i_page as page on page.page_id = openday.page_id 
        JOIN i_openday_time as openday_time on openday_time.openday_id = openday.openday_id 
        JOIN i_users as in_charge on in_charge.user_id = openday.in_charge_user_id
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
      
        $sql = "
          INSERT INTO i_openday_attendees (
            `openday_id`,
            `user_id`,
            `cover_letter`,
            `is_scheduled`,
            `schedule_time_start`,
            `schedule_time_end`,
            `email`,
            `cv`,
            `phone`,
            `date_joined`
          )
          VALUES (
            '$opendayId',
            '$userId',
            '$coverLetter',
            '$isScheduled',
            '$timeStart',
            '$timeEnd',
            '". $user['primary_email'] ."',
            '". $user['cv'] ."',
            '". $user['primary_mobile'] ."',
            NOW()
          )
        ";
        $joinStatement = $this->db->prepare($sql);

        $joinStatement->execute();

        if($isScheduled) {
          
       
          $updateTimeBreakDownSql = "
            UPDATE i_openday_time_breakdown
            SET scheduled_user_id = '$userId',
            is_filled = 1,
            date_filled = NOW(),
            date_updated = NOW()
            WHERE time_breakdown_id = '$timeBreakdownId'
          ";          

          $updateTimeBreakDownStatement = $this->db->prepare($updateTimeBreakDownSql);

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
      JOIN i_page as page on page.page_id = openday.page_id
      JOIN i_openday_time as time on openday.openday_id = time.openday_id
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
        AGAINST ("' . $q . '" IN BOOLEAN MODE);
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
      JOIN i_users_object_data as user on attendees.user_id = user.user_id
      WHERE attendees.openday_id = '$opendayId'
      AND attendees.is_scheduled = '$isScheduled '
      AND attendees.status < 2
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


  public function getSchedule($opendayId, $userId)
  {
    $sql = "
      SELECT * FROM i_openday_attendees as attendees
      JOIN i_openday as openday on openday.openday_id = attendees.openday_id
      JOIN i_page as page on page.page_id = openday.page_id
      JOIN i_openday_time as time on openday.openday_id = time.openday_id
      WHERE attendees.user_id = '$userId'
      AND attendees.openday_id = '$opendayId'
      LIMIT 1
    ";

    try {
      $statement = $this->db->prepare($sql);
      $statement->execute();
      $schedule = $statement->fetch();

      return $schedule;

    }
    catch(PDOException $e) {
      return $e;
    }
  }

  public function rejectCandidate($opendayId, $userId)
  {
    $updateAttendeeSql = "
      UPDATE i_openday_attendees
      SET status = '3'
      WHERE openday_id = '$opendayId'
      AND user_id = '$userId'
    ";

    $updateTimeBreakDownSql = "
      UPDATE i_openday_time_breakdown
      SET scheduled_user_id = NULL,
      is_filled = 0
      WHERE openday_id = '$opendayId'
      AND scheduled_user_id = '$userId'
    ";

    $this->db->beginTransaction();
    try {
        $updateAttendee = $this->db->prepare($updateAttendeeSql);

        $updateTimeBreakDown = $this->db->prepare($updateTimeBreakDownSql);

        $updateAttendee->execute();
        $updateTimeBreakDown->execute();

        $this->db->commit();

        return true;

    }
    catch(PDOException $e) {
      $this->db->rollback();

      return false;
    }


  }

  public function stopQueue($opendayId)
  {
    $sql = "
      UPDATE i_openday
      SET stopped_adding_queue = 1
      WHERE openday_id = '$opendayId'
    ";

    $this->db->beginTransaction();

    try {
      $updateStatement = $this->db->prepare($sql);

      $updateStatement->execute();


      $this->db->commit();
    }
    catch(PDOException $e) {
      $this->db->rollBack();
    }



  }

   public function endInterview($opendayId, $userId)
  {
    $sql = "
      UPDATE i_openday_attendees
      SET status = 2
      WHERE openday_id = '$opendayId'
      AND user_id = '$userId'
    ";

    $this->db->beginTransaction();

    try {
      $updateStatement = $this->db->prepare($sql);

      $updateStatement->execute();


      $this->db->commit();
    }
    catch(PDOException $e) {
      $this->db->rollBack();
    }



  }

  public function getListofCandidateId($opendayId, $status, $scheduled) 
  {
    $sql = "
      SELECT user_id 
      FROM  i_openday_attendees
      WHERE openday_id = '$opendayId'
      AND is_scheduled = '$scheduled'
      AND status  < 2
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

  public function setInterviewing($opendayId, $userId)
  {

    $sql = "
      UPDATE i_openday_attendees
      SET status = 0
      WHERE openday_id = '$opendayId'
      AND user_id = '$userId'
    ";

    $this->db->beginTransaction();

    try {
      $updateStatement = $this->db->prepare($sql);

      $updateStatement->execute();


      $this->db->commit();
    }
    catch(PDOException $e) {
      $this->db->rollBack();
    }

  }

  public function liveOpenday() 
  {
    $sql = "
      SELECT * FROM i_openday as openday 
      JOIN i_openday_time as o_time on o_time.openday_id = openday.openday_id
      WHERE o_time.start_time <= NOW() and openday.event_date = CURDATE()
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
  // Private Functions

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
