<?php namespace App\Controllers;

use App\Models\Promotion;
use App\Models\User;
use Carbon\Carbon;

class PromotionController extends BaseController 
{
	public function __construct(Promotion $promotion, User $user) 
	{
		$this->promotionResource = $promotion;
		$this->userResource 	 = $user;
	}
	public function store($request, $response)
	{
		$type 			= $request->getParam('promotion_type');
		$start_date 	= ($request->getParam("schedule") == "limited") ? $request->getParam("start_date") : date("Y-m-d");
		$end_date 		= ($request->getParam("schedule") == "limited") ? $request->getParam("end_date") : date("Y-m-d", strtotime('+100 years'));
		$start_time 	= ($request->getParam("schedule") == "limited") ? $request->getParam("start_time") : date("H:i:s", strtotime("00:00:00"));
		$end_time 		= ($request->getParam("schedule") == "limited") ? $request->getParam("end_time") : date("H:i:s", strtotime("00:00:00"));
		$startTimeCarbon = Carbon::createFromFormat('H:i:s', date("H:i:s", strtotime(date("H:i:s", strtotime($start_time)))), "Asia/Dubai");
		$startTimeCarbon->tz('UTC');

		$endTimeCarbon = Carbon::createFromFormat('H:i:s', date("H:i:s", strtotime(date("H:i:s", strtotime($end_time)))), "Asia/Dubai");
		$endTimeCarbon->tz('UTC');

		$data = array(
						'currency_id' 						=> $request->getParam("currency_id"),
						'location_lat'		  				=> $request->getParam("lat"),
						'location_lng'		  				=> $request->getParam("lng"),
						'location_radius'		  			=> $request->getParam("radius"),
						'industry'		  					=> $request->getParam("industry"),
						'gender'		  					=> $request->getParam("gender"),
						'budget_per_day'		  			=> ($request->getParam("budget_per_day") != NULL) ? $request->getParam("budget_per_day"): NULL,
						'bid_per_engagement'				=> $request->getParam("bid_per_engagement"),
						'to_be_promoted_id'					=> $request->getParam("to_be_promoted_id"),
						'run_start_date'					=> date("Y-m-d", strtotime($start_date)),
						'run_end_date'						=> date("Y-m-d", strtotime($end_date)),
						'run_start_time'					=> $startTimeCarbon->format("H:i:s"),
						'run_end_time'						=> $endTimeCarbon->format("H:i:s"),
						'keyword'							=> $request->getParam('keyword'),
						'is_people_applied_include'			=> ($request->getParam('is_people_applied_included') != NULL) ? 1 : 0 ,
						'is_people_viewed_job_include'		=> ($request->getParam('is_people_viewed_job_included') != NULL) ? 1 : 0 ,
						'is_people_viewed_page_include'		=> ($request->getParam('is_people_viewed_page_included') != NULL) ? 1:  0 ,
						'created_by_user_id'			  	=> $request->getParam('created_by_user_id'),
						'page_id'			  				=> $request->getParam('page_id')

					);


		switch ($type) {
			case 'openday':
					$result = $this->promotionResource->saveOpendayPromotion($data);
				break;
			case 'user':
					$result = $this->promotionResource->saveUserPromotion($data);
				break;
			case 'job':
					$result = $this->promotionResource->saveJobPostPromotion($data);
					
				break;
			case 'page':
					$result = $this->promotionResource->savePagePromotion($data);
				break;
			default:

				break;
		}

		if($result) {
				return $response->withStatus(200)->withJson($result);
		}
		else {
				return $response->withStatus(400)->withJson($result);
		}
	}

	public function getPromotedItems($request, $response, $args)
	{
		$type = $args['type'];
		$userId = $request->getParam('user_id');
		$user = ($userId > 0 ) ? $this->userResource->getUserWithObjectById($userId) : null;

		switch ($type) {
			case 'openday':
				$output = $this->promotionResource->getPromotedOpenday($user);
				return $response->withStatus(200)->withJson($output);
				break;
			case 'job':
				$output = $this->promotionResource->getPromotedJobPost($user);
				return $response->withStatus(200)->withJson($output);
				break;
			case 'page':
				$output = $this->promotionResource->getPromotedPage($user);
				return $response->withStatus(200)->withJson($output);
				break;
			case 'user':
				$page = ($userId > 0 ) ? $this->userResource->getUserPage($userId) : null;
				$output = $this->promotionResource->getPromotedUser($page, $userId);
				return $response->withStatus(200)->withJson($output);
				break;
			default:
				# code...
				break;
		}
	}

	public function checkIfPromoted($request, $response, $args)
	{
		$type = $args['type'];
		$id = $args['id'];

		switch ($type) {
				case 'openday':
				$output = $this->promotionResource->checkIfPromotedOpenday($id);
				return $response->withStatus(200)->withJson($output);
				break;
			case 'job':
				$output = $this->promotionResource->checkIfPromotedJobPost($id);
				return $response->withStatus(200)->withJson($output);
				break;
			case 'page':
				$output = $this->promotionResource->checkIfPromotedPage($id);
				return $response->withStatus(200)->withJson($output);
				break;
			case 'user':
				
				$output = $this->promotionResource->checkIfPromotedUser($id);
				return $response->withStatus(200)->withJson($output);
				break;
			default:
				# code...
				break;
		}
	}

	public function recordEngagement($request, $response, $args)
	{
		$promotionId = $args['id'];
		$type = $args['type'];
		$userId = $request->getParam('user_id');

		$this->promotionResource->recordEngagement($promotionId, $type, $userId);

		return $response->withStatus(200)->withJson(true);


	}

	public function computeTotalConsumedAmount($request, $response, $args)
	{
		$promotionId = $args['id'];
		$type = $args['type'];


		$consumedAmount = $this->promotionResource->computeTotalConsumedAmount($promotionId, $type);

		return $response->withStatus(200)->withJson($consumedAmount);
	}

	public function getAllPromotion($request, $response, $args)
	{
		$type = $args['type'];
		switch ($type) {
				case 'openday':
				$output = $this->promotionResource->getAllOpendayPromotion();
				return $response->withStatus(200)->withJson($output);
				break;
			case 'job':
				$output = $this->promotionResource->getAllJobPromotion();
				return $response->withStatus(200)->withJson($output);
				break;
			case 'page':
				$output = $this->promotionResource->getAllPagePromotion();
				return $response->withStatus(200)->withJson($output);
				break;
			case 'user':
				
				$output = $this->promotionResource->getAllUserPromotion();
				return $response->withStatus(200)->withJson($output);
				break;
			default:
				# code...
				break;
		}
	}

}