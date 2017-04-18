<?php

namespace App\Controllers;

use App\Models\OpenDay;
use App\Models\User;
use App\Validations\CreateOpenDayValidation;
use App\Validations\UpdateOpenDayValidation;
use App\Helpers\Request;
use App\Helpers\Paginate;
use App\Helpers\EmailHelper;


/**
 * Class OpenDayController
 * @package App\Controllers
 */

class OpenDayController extends BaseController
{
   public $openDayResouce, $createOpenDayValidation;

   public function __construct(
        OpenDay $openDayResource,
        User $userResource,
        Paginate $paginate,
        EmailHelper $emailHelper
   ){

      $this->openDayResource = $openDayResource;
      $this->userResource = $userResource;
      $this->paginate = $paginate;
      $this->emailHelper = $emailHelper;
   }

   public function index($request, $response)
   {

      $paginate = $this->paginate->init($request->getParam('page'));
      $items = $this->openDayResource->getLatestEvents($paginate);


      return $response->withStatus(200)->withJson($items);
   }

   public function show($request, $response, $args)
   {

      $item = $this->openDayResource->getById($args['openday_id']);
      if($item) {
        $item['time_breakdown'] = $this->openDayResource->getTimeBreakDownByOpendayId($item['openday_id']);
        $item['jobs'] = $this->openDayResource->getJobsByOpendayId($item['openday_id']);

        $userId = $request->getParam('user_id');
        if($userId) {

          $item['applied'] = $this->openDayResource->checkIfAlreadySubmittedApplication($args['openday_id'], $userId);
          $item['schedule'] = [];
          if($item['applied']) {
            $item['schedule'] =  $this->openDayResource->getSchedule($args['openday_id'], $userId);
          }

        }

        return $response->withStatus(200)->withJson($item);
      }
      return $response->withStatus(404)->withJson(['message' => 'Not Found']);
   }

   public function store($request, $response)
   {



      $createParams = array(
        'event_name' => $request->getParam('event_name'),
        'event_date' => $request->getParam('event_date'),
        'time_interval_per_candidate' => $request->getParam('time_interval_per_candidate'),
        'introduction' => $request->getParam('introduction'),
        'in_charge_user_id' => $request->getParam('in_charge_user_id'),
        'rate_per_hour' => $request->getParam('rate_per_hour'),
        'created_by_user_id' => $request->getParam('created_by_user_id'),
        'employment_type_id' => $request->getParam('employment_type_id')
      );

      $jobs      = $request->getParam('jobs');
      $timeRange = $request->getParam("timerange");

      $openday = $this->openDayResource->create($createParams, $timeRange, $jobs);
      return $response->withStatus(200)->withJson($openday);
   }

   public function update($request, $response, $args)
   {



     $updateParams = array(
        'event_name' => $request->getParam('event_name'),
        'event_date' => $request->getParam('event_date'),
        'time_interval_per_candidate' => $request->getParam('time_interval_per_candidate'),
        'introduction' => $request->getParam('introduction'),
        'in_charge_user_id' => $request->getParam('in_charge_user_id'),
        'rate_per_hour' => $request->getParam('rate_per_hour')
      );

      $jobs      = $request->getParam('jobs');
      $timeRange = $request->getParam("timerange");

      $openday = $this->openDayResource->update($createParams, $timeRange, $jobs);

     return $response->withStatus(200)->withJson($openday);
   }

   public function delete($request, $response)
   {

   }

   public function join($request, $response, $args)
   {
     $opendayId       = $args['openday_id'];
     $userId          = $request->getParam('user_id');
     $timeBreakdownId = $request->getParam('openday_time_breakdown_id');
     $timeStart       = $request->getParam('time_start');
     $timeEnd         = $request->getParam('time_end');
     $coverLetter     = $request->getParam('cover_letter');
     $coverLetterTitle     = $request->getParam('cover_letter_title');

     $openday = $this->openDayResource->getById($opendayId);
     if($openday['stopped_adding_queue']) {
       return $response->withStatus(400);
     }

     $timeBreakdown = $this->openDayResource->getTimeBreakdown($timeBreakdownId);
     if($timeBreakdown['is_filled'] == 1) {
       return $response->withStatus(400)->withJson(array('message' => 'This schedules is already taken.'));
     }
     $result = $this->openDayResource->join($opendayId, $userId, $coverLetter, $coverLetterTitle, $timeBreakdownId, $timeStart, $timeEnd);

     if($result) {
       $user = $this->userResource->getUserById($userId);
       $scheduleDetails = $this->openDayResource->getSchedule($opendayId, $userId);


       $timeZone = $this->userResource->getTimeZoneByUserId($userId);
       $this->emailHelper->sendOpendayDetailsMail($openday, $scheduleDetails, $user, $timeZone);
       return $response->withStatus(200)->withJson($result);
     }

     return $response->withStatus(400);
   }

   public function search($request, $response, $args)
   {
     $search = $request->getParam("q");
     $paginate = $this->paginate->init($request->getParam('page'));

     $result = $this->openDayResource->search($search, $paginate);

     return $response->withStatus(200)->withJson($result);

   }

   public function myOpenDay($request, $response, $args)
   {
      $status = $request->getParam("status");
      $userId = $request->getParam("user_id");

      if($status == 'active') {
        $items = $this->openDayResource->getActiveOpendayByAttendeeUSerId($userId);
      }
      else {
        $items = $this->openDayResource->getEndOpendayByAttendeeUSerId($userId, $status);

      }

      return $response->withStatus(200)
                      ->withJson(array(
                        'success' => true,
                        'data'    => $items
                      ));

   }

   public function suggestedUsers($request, $response, $args)
   {
     $opendayId       = $args['openday_id'];

     $items = $this->openDayResource->getSuggestedUsersByOpendayId($opendayId);

     return $response->withStatus(200)->withJson($items);
   }

   public function candidates($request, $response, $args)
   {
      $isScheduled     = $request->getParam('is_scheduled');
      $opendayId       = $args['openday_id'];
      $withEnded       = $request->getParam('with_ended');

      $items = $this->openDayResource->getCandidates($opendayId, $isScheduled, $withEnded);


      return $response->withStatus(200)->withJson($items);
   }

   public function createdOpenday($request, $response, $args)
   {
     $userId = $request->getParam('user_id');
     $items = $this->openDayResource->getOpendayCreatedByUserPage($userId);
     return $response->withStatus(200)->withJson($items);
   }

   public function currentRate($request, $response, $args)
   {
      $rate = $this->openDayResource->getCurrentRatePerHour();

      return $response->withStatus(200)->withJson($rate);
   }

   public function computeTotalHour($request, $response)
   {
      $timeRange = $request->getParam('timerange');
      $interval = $request->getParam('time_interval');
      var_dump($timeRange);

      $totalHrs =  $this->openDayResource->computeTotalHours($timeRange, $interval);

      return $response->withStatus(200)->withJson($totalHrs);
   }

   public function schedule($request, $response, $args)
   {
      $userId     = $request->getParam('user_id');
      $opendayId  = $args['openday_id'];

      $schedule = $this->openDayResource->getSchedule($opendayId, $userId);

      return $response->withStatus(200)->withJson($schedule);

   }

   public function rejectCandidate($request, $response, $args)
   {
      $opendayId = $args['openday_id'];
      $userId    = $request->getParam('user_id');

       $saved = $this->openDayResource->rejectCandidate($opendayId, $userId);

       if($saved) {
         $user = $this->userResource->getUserById($userId);
         $scheduleDetails = $this->openDayResource->getSchedule($opendayId, $userId);
         $this->emailHelper->sendRejectedCandidateMail($scheduleDetails, $user);

        return $response->withStatus(200);
       }

       return $response->withStatus(400);
   }

   public function stopQueue($request, $response, $args)
   {
      $opendayId = $args['openday_id'];

      $this->openDayResource->stopQueue($opendayId);

      return $response->withStatus(200);

   }

   public function endInterview($request, $response, $args)
   {
      $opendayId = $args['openday_id'];
      $userId    = $request->getParam('user_id');

      $schedule = $this->openDayResource->endInterview($opendayId, $userId);
      return $response->withStatus(200)->withJson($schedule);

   }

   public function getListOfCandidateId($request, $response, $args)
   {
     $opendayId = $args['openday_id'];
     $status    = $request->getParam('status');


     $scheduled = $this->openDayResource->getListOfCandidateId($opendayId, $status, 1);

    $scheduled = array_column($scheduled, 'user_id');
     $notScheduled = $this->openDayResource->getListOfCandidateId($opendayId, $status, 0);
      $notScheduled = array_column($notScheduled, 'user_id');
     $items = array(
        'scheduled' => $scheduled,
        'notScheduled' => $notScheduled
      );

     return $response->withStatus(200)->withJson($items);

   }

   public function setWaiting($request, $response, $args)
   {
     $opendayId = $args['openday_id'];
     $userId    = $request->getParam('user_id');

     $this->openDayResource->setWaiting($opendayId, $userId);
   }

   public function setInterviewing($request, $response, $args)
   {
     $opendayId = $args['openday_id'];
     $userId    = $request->getParam('user_id');

     $this->openDayResource->setInterviewing($opendayId, $userId);



   }

   public function getLiveOpenday($request, $response, $args)
   {
      $userId    = $request->getParam('user_id');

      $items = $this->openDayResource->liveOpenday($userId);

      return $response->withStatus(200)->withJson($items);

   }

   public function getCurrentlyInterviewed($request, $response, $args)
   {
      $opendayId = $args['openday_id'];
      $candidate = $this->openDayResource->getCurrentlyInterviewedCandidate($opendayId);

      return $response->withStatus(200)->withJson($candidate);

   }

   public function waitingModeInfo($request, $response, $args)
   {
      $opendayId = $args['openday_id'];
      $item = [
          'currently_interviewed' => $this->openDayResource->getCurrentlyInterviewedCandidate($opendayId),
          'total_waiting_list' => $this->openDayResource->countWaitingList($opendayId)
      ];

      return $response->withStatus(200)->withJson($item);


   }

   public function checkIfJoined($request, $response, $args)
   {
      $userId = $request->getParam('user_id');
      $opendayId = $args['openday_id'];

      $checkIfAttended = $this->openDayResource->checkIfAlreadySubmittedApplication($opendayId, $userId);

      return $response->withStatus(200)->withJson($checkIfAttended);

   }

   public function getUserCoverLetter($request, $response, $args)
   {
      $userId = $request->getParam('user_id');

      $items = $this->openDayResource->getCoverLetterByUserId($userId);

       return $response->withStatus(200)->withJson($items);
   }

   public function extendHours($request, $response, $args) {

      $numberOfHours = $request->getParam('numberOfHours');
      $opendayId = $args['openday_id'];

      $openday = $this->openDayResource->extendOpendayTime($numberOfHours, $opendayId);
      return $response->withStatus(200)->withJson($openday);
   }

}
