<?php

namespace App\Controllers;

use App\Models\OpenDay;
use App\Validations\CreateOpenDayValidation;
use App\Validations\UpdateOpenDayValidation;
use App\Services\OpenDayService;
use App\Helpers\Request;

/**
 * Class OpenDayController
 * @package App\Controllers
 */

class OpenDayController extends BaseController
{
   public $openDayResouce, $openDayService, $createOpenDayValidation;

   public function __construct(
        OpenDay $openDayResource,
        OpenDayService $openDayService,
        CreateOpenDayValidation $createOpenDayValidation,
        UpdateOpenDayValidation $updateOpenDayValidation
   ){

      $this->openDayResource = $openDayResource;
      $this->openDayService  = $openDayService;
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

      $createParams = Request::only($request, [
        'event_name',
        'event_date',
        'time_interval_per_candidate',
        'introduction',
        'in_charge_user_id',
        'rate_per_hour',
        'created_by_user_id'
      ]);

      $jobs = Request::get($request, "jobs");
      $timeRange = Request::get($request, "timerange");

      $openday = $this->openDayResource->create($createParams, $timeRange, $jobs);
      return $response->withStatus(200)->withJson($openday);
   }

   public function update($request, $response, $args)
   {
     $isValid = $this->updateOpenDayValidation->assert($request->getParams());
     if(!$isValid) {
       return  $response->withStatus(400)->withJson($this->createOpenDayValidation->errors);
     }
     $updateParams = Request::only($request, [
       'event_name',
       'event_date',
       'time_interval_per_candidate',
       'introduction',
       'in_charge_user_id',
       'rate_per_hour'
     ]);
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
     $userId          = $request->getParam('user_id');
     $timeBreakdownId = $request->getParam('openday_time_breakdown_id');
     $opendayId       = $args['openday_id'];
     $timeStart       = $request->getParam('time_start');
     $timeEnd       = $request->getParam('time_end');
     $coverLetter       = $request->getParam('cover_letter');

     $result = $this->openDayService->join($opendayId, $userId, $coverLetter, $timeBreakdownId, $timeStart, $timeEnd);
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


      $items = $this->openDayService->getMyOpenday($userId, $status);

      return $response->withStatus(200)->withJson($items);

   }

   public function candidates($request, $response, $args)
   {
      $isScheduled     = $request->getParam('is_scheduled');
      $opendayId       = $args['openday_id'];

      $items = $this->openDayService->getCandidates($opendayId, $isScheduled);


      return $response->withStatus(200)->withJson($items);
   }

   public function currentRate($request, $response, $args)
   {
      $rate = $this->openDayService->getCurrentRatePerHour();

      return $response->withStatus(200)->withJson($rate);
   }

   public function computeTotalHour($request, $response)
   {
      $timeRange = Request::get($request, "timerange");
      $interval = Request::get($request, "time_interval");
      var_dump($timeRange);

      $totalHrs =  $this->openDayService->computeTotalHours($timeRange, $interval);

      return $response->withStatus(200)->withJson($totalHrs);
   }



}
