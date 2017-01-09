<?php
namespace App\Services;

use App\Models\OpenDay;
use App\Models\OpenDayTime;
use App\Models\OpenDayTimeBreakdown;
use App\Models\OpenDayJobs;
use App\Models\OpenDayAttendees;
use App\Models\User;

class OpenDayService
{
  protected $currentRatePerHour = "100"; // USD
  private $openday, $opendayTime;

  public function __construct(
    OpenDay $openday,
    OpenDayTime $opendayTime,
    OpendayTimeBreakdown $opendayTimeBreakdown,
    OpenDayJobs $openDayJobs,
    OpenDayAttendees $opendayAttendees,
    User $user
    )
  {
    $this->openday = $openday;
    $this->opendayTime = $opendayTime;
    $this->opendayTimeBreakdown = $opendayTimeBreakdown;
    $this->openDayJobs = $openDayJobs;
    $this->opendayAttendees = $opendayAttendees;
    $this->user = $user;
  }

  public function create($createArray, $timeRange, $jobs)
  {
      $createArray['openday_id'] = substr(uniqid(), 0, 8);
      $createArray['event_date'] = date("Y-m-d", strtotime($createArray['event_date']));
      $createArray['rate_per_hour'] = $this->currentRatePerHour;
      $createArray['amount'] = (float) $this->computeTotalHours($timeRange, $createArray['time_interval_per_candidate']) * $this->currentRatePerHour;

      $opendayId = $this->openday->create($createArray);

      $this->saveTime($timeRange, $opendayId);
      $this->saveTimeBreakdown($timeRange, $createArray['time_interval_per_candidate'], $opendayId);
      $this->saveJobs($jobs, $opendayId);

      return true;
  }

  public function update($opendayId, $updateArray, $startTime, $endTime)
  {
      $this->openday->update(['openday_id' => $opendayId], $updateArray);
      $opendayTime = $this->opendayTime->filter(['openday_id' => $opendayId])->get();

      $timeBreakDown = $this->createSplitTimeArray(
          $startTime,
          $endTime,
          $updateArray['time_interval_per_candidate']
        );

      if($opendayTime['start_time'] != $startTime || $opendayTime['end_time'] != $endTime) {
          $this->opendayTimeBreakdown->delete(['openday_id' => $opendayId], false);
          foreach ($timeBreakDown as $segment) {

            $from_time = strtotime("2008-12-13 10:21:00");
            $this->opendayTimeBreakdown->create([
              'openday_id' => $opendayId,
              'time_start' => $segment['start'],
              'time_end' => $segment['end']
             ]);
          }
      }


  }

  public function getTimeBreakDown($opendayId) 
  {
    return $this->opendayTimeBreakdown->where('openday_id', $opendayId)->get();
 
  }

  public function getJobs($opendayId)
  {
    return $this->openDayJobs->where('openday_id', $opendayId)->join('i_job_post', 'job_post_id', 'job_post_id')->get(); 

  }

  public function join($opendayId, $userId, $coverLetter, $timeBreakDownId, $timeStart, $timeEnd)
  {
     $user = $this->user->where('user_id', $userId)->get(1);

     $isScheduled = (boolean) $timeBreakDownId;
     $timeStart = date("H:i:s", strtotime($timeStart));
     $timeEnd = date("H:i:s", strtotime($timeEnd));

     $this->opendayAttendees->create([
        'openday_id'    =>    $opendayId,
        'user_id'       =>    $userId,
        'cover_letter'  =>    $coverLetter,
        'is_scheduled'  =>    $isScheduled,
        'schedule_time_start' => $timeStart,
        'schedule_time_end' => $timeEnd,
        'email' => $user['primary_email'],
        'cv' => $user['cv'],
        'phone' => $user['primary_mobile']


      ]);

     if($isScheduled) {
        $this->opendayTimeBreakdown->update(['time_breakdown_id' => $timeBreakDownId], ['scheduled_user_id' => $userId, 'is_filled' => true, 'date_filled' => date('Y-m-d H:i:s')]);
     }

     return true;
  }

  public function search($query)
  {
    return $this->openday->fullTextSearch($query, ['event_name', 'introduction']);
  }

  public function getMyOpenday($userId, $status)
  {
      return $this->opendayAttendees->filter(['user_id' => $userId, 'status' => $status])->join('i_openday', 'openday_id', 'openday_id')->get();

  }


  public function getCandidates($opendayId, $isScheduled)
  {
      return $this->opendayAttendees->filter(['openday_id' => $opendayId, 'is_scheduled' => $isScheduled])->join('i_users_object_data', 'user_id', 'user_id')->get();

  }

  public function getCurrentRatePerHour()
  {
      return $this->currentRatePerHour;
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

  /* Private Methods */

  /* Create Split Time
  * @return array;
  */
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



  private function saveTimeBreakdown(array $timeRange, $timeInterval, $opendayId)
  {
    foreach ($timeRange as $range) {

      $timeBreakDown = $this->createSplitTimeArray(
        $range['start'],
        $range['end'],
        $timeInterval
      );
      foreach ($timeBreakDown as $segment) {
        $this->opendayTimeBreakdown->create([
          'openday_id' => $opendayId,
          'time_start' => $segment['start'],
          'time_end' => $segment['end']
        ]);
      }
    }
  }

  private function saveJobs(array $jobs, $opendayId)
  {
      foreach ($jobs as $job) {
        $this->openDayJobs->create([
          'openday_id' => $opendayId,
          'job_post_id' => $job
        ]);
      }

      return true;
  }

  private function saveTime($timerange, $opendayId)
  {
    $startTime = current($timerange)['start'];
    $endTime   = end($timerange)['end'];


    $this->opendayTime->create([
      'openday_id' => $opendayId,
      'start_time' => date("H:i:s", strtotime($startTime)),
      'end_time'   => date("H:i:s", strtotime($endTime))
    ]);
  }






}
