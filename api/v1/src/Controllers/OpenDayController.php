<?php

namespace App\Controllers;

use App\Models\OpenDay;
use App\Validations\CreateOpenDayValidation;
use App\Validations\UpdateOpenDayValidation;
use App\Helpers\Request;

/**
 * Class OpenDayController
 * @package App\Controllers
 */

class OpenDayController extends BaseController
{
   public $openDayResouce, $createOpenDayValidation;

   public function __construct(
        OpenDay $openDayResource,
        CreateOpenDayValidation $createOpenDayValidation,
        UpdateOpenDayValidation $updateOpenDayValidation
   ){

      $this->openDayResource = $openDayResource;
      $this->createOpenDayValidation  = $createOpenDayValidation;
      $this->updateOpenDayValidation  = $updateOpenDayValidation;
   }

   public function index($request, $response)
   {
      $items = $this->openDayResource
                    ->filter($request->getQueryParams())
                    ->paginate($request->getQueryParam('page'));

      return $response->withStatus(200)->withJson($items);
   }

   public function show($request, $response, $args)
   {
      $item = $this->openDayResource->getById($args['openday_id']);
      if($item) {
        $item['time_breakdown'] = $this->openDayResource->getTimeBreakDownByOpendayId($item['openday_id']);
        $item['jobs'] = $this->openDayResource->getJobsByOpendayId($item['openday_id']);

        return $response->withStatus(200)->withJson($item);
      }
      return $response->withStatus(404)->withJson(['message' => 'Not Found']);
   }

   public function store($request, $response)
   {
     $isValid = $this->createOpenDayValidation->assert($request->getParams());

     if(!$isValid) {
       return  $response->withStatus(400)->withJson($this->createOpenDayValidation->errors);
     }

    

      $createParams = array(
        'event_name' => $request->getParam('event_name'),
        'event_date' => $request->getParam('event_date'),
        'time_interval_per_candidate' => $request->getParam('time_interval_per_candidate'),
        'introduction' => $request->getParam('introduction'),
        'in_charge_user_id' => $request->getParam('in_charge_user_id'),
        'rate_per_hour' => $request->getParam('rate_per_hour'),
        'created_by_user_id' => $request->getParam('created_by_user_id')
      );

      $jobs      = $request->getParam('jobs');
      $timeRange = $request->getParam("timerange");

      $openday = $this->openDayResource->create($createParams, $timeRange, $jobs);
      return $response->withStatus(200)->withJson($openday);
   }

   public function update($request, $response, $args)
   {
     $isValid = $this->updateOpenDayValidation->assert($request->getParams());
     if(!$isValid) {
       return  $response->withStatus(400)->withJson($this->createOpenDayValidation->errors);
     }
    

     $updateParams = array(
        'event_name' => $request->getParam('event_name'),
        'event_date' => $request->getParam('event_date'),
        'time_interval_per_candidate' => $request->getParam('time_interval_per_candidate'),
        'introduction' => $request->getParam('introduction'),
        'in_charge_user_id' => $request->getParam('in_charge_user_id'),
        'rate_per_hour' => $request->getParam('rate_per_hour')
      );

     $startTime = date("H:i", strtotime($request->getParam('start_time')));
     $endTime = date("H:i", strtotime($request->getParam('end_time')));

     $openday = $this->openDayService->update($args['openday_id'], $updateParams, $startTime, $endTime);

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

     $openday = $this->openDayResource->getById($opendayId);
     if($openday['stopped_adding_queue']) {
       return $response->withStatus(400);
     }

     $timeBreakdown = $this->openDayResource->getTimeBreakdown($timeBreakdownId);
     if($timeBreakdown['is_filled'] == 1) {
       return $response->withStatus(400);
     }
     $result = $this->openDayResource->join($opendayId, $userId, $coverLetter, $timeBreakdownId, $timeStart, $timeEnd);
     if($result) {
       return $response->withStatus(200)->withJson($result);
     }
     return $response->withStatus(400);
   }

   public function search($request, $response, $args)
   {
     $search = $request->getParam("q");

     $result = $this->openDayResource->search($search);

     return $response->withStatus(200)->withJson($result);

   }

   public function myOpenDay($request, $response, $args)
   {
      $status = $request->getParam("status");
      $userId = $request->getParam("user_id");


      $items = $this->openDayResource->getOpendayByAttendeeUserId($userId, $status);

      return $response->withStatus(200)->withJson($items);

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

      $items = $this->openDayResource->getCandidates($opendayId, $isScheduled);


      return $response->withStatus(200)->withJson($items);
   }

   public function createdOpenday($request, $response, $args) 
   {
     $userId = $request->getParam('user_id');
     $items = $this->openDayResource->getOpendayByCreatedByUserId($userId);
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

      $this->openDayResource->endInterview($opendayId, $userId);
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

   public function setInterviewing($request, $response, $args)
   {
     $opendayId = $args['openday_id'];
     $userId    = $request->getParam('user_id'); 

     $this->openDayResource->setInterviewing($opendayId, $userId);

   }

   public function getLiveOpenday($request, $response, $args) 
   {
      $items = $this->openDayResource->liveOpenday();

      return $response->withStatus(200)->withJson($items);

   }

}
