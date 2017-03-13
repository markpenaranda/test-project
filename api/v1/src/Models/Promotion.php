<?php

namespace App\Models;

use PDO;
use Carbon\Carbon;

class Promotion
{


	public function __construct(PDO $db)
  	{
      $this->db = $db;
	}

	public function getAllOpendayPromotion()
	{
		$sql = "
			SELECT * FROM i_promote_openday as promote
			JOIN i_openday as openday on openday.openday_id = promote.openday_id
			JOIN i_openday_time as openday_time on openday.openday_id = openday_time.openday_id
			JOIN i_page as page on page.page_id = openday.page_id
			WHERE promote.is_active = 1
			AND promote.is_deleted = 0

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

	public function getAllJobPromotion()
	{
		$sql = "
			SELECT * FROM i_promote_job_post as promote
			JOIN i_job_post as job_post on job_post.job_post_id = promote.job_post_id
			JOIN i_page as page on page.page_id = promote.page_id
			WHERE promote.is_active = 1
			AND promote.is_deleted = 0
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

	public function getAllPagePromotion()
	{
		$sql = "
			SELECT * FROM i_promote_page as promote
			JOIN i_page as page on page.page_id = promote.page_id
			WHERE promote.is_active = 1
			AND promote.is_deleted = 0
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

	public function getAllUserPromotion()
	{
		$sql = "
			SELECT * FROM i_promote_user_profile as promote
			JOIN i_users_object_data as user on user.user_id = promote.user_id
			WHERE promote.is_active = 1
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
			AND promote.is_deleted = 0
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


	public function saveOpendayPromotion($data)
	{

		$promotion_id = $this->getUniqueId();
		$checkIfHaveActivePromotion = $this->checkIfPromotedOpenday($data['to_be_promoted_id']);

		if($checkIfHaveActivePromotion) {
			return false;
		}
		$sqlInsert = "
			INSERT INTO i_promote_openday (
				`promote_id`,
				`page_id`,
				`openday_id`,
				`coordinates`,
				`industry`,
				`gender`,
				`budget_per_day`,
				`bid_per_engagement`,
				`currency_id`,
				`run_start_date`,
				`run_end_date`,
				`run_start_time`,
				`run_end_time`,
				`keyword`,
				`is_people_applied_include`,
				`is_people_viewed_job_include`,
				`is_people_viewed_page_include`,
				`is_deleted`,
				`created_by_user_id`,
				`updated_by_user_id`,
				`date_created`,
				`date_updated`,
				`is_active`
			)
			VALUES (
				'". $promotion_id ."',
				'". $data['page_id'] . "',
				'". $data['to_be_promoted_id'] . "',
				'". $data['coordinates'] . "',
				'". $data['industry'] . "',
				'". $data['gender'] . "',
				". $data['budget_per_day'] . ",
				". $data['bid_per_engagement'] . ",
				". $data['currency_id'] . ",
				'". $data['run_start_date'] . "',
				'". $data['run_end_date'] . "',
				'". $data['run_start_time'] . "',
				'". $data['run_end_time'] . "',
				'". $data['keyword'] . "',
				". $data['is_people_applied_include'] . ",
				". $data['is_people_viewed_job_include'] . ",
				". $data['is_people_viewed_page_include'] . ",
				0,
				'". $data['created_by_user_id'] . "',
				'". $data['created_by_user_id'] . "',
				NOW(),
				NOW(),
				1
			)

		";

		$this->db->beginTransaction();
		try {
			$opendayPromotionStatment = $this->db->prepare($sqlInsert);
			$opendayPromotionStatment->execute();

			$this->db->commit();
			return true;
		}
		catch(PDOException $e) {
	      $this->db->rollBack();
	      return false;
	    }
	}

	public function saveJobPostPromotion($data)
	{
		$promotion_id = $this->getUniqueId();
		$checkIfHaveActivePromotion = $this->checkIfPromotedJobPost($data['to_be_promoted_id']);

		if($checkIfHaveActivePromotion) {
			return false;
		}
		$sqlInsert = "
			INSERT INTO i_promote_job_post (
				`promote_id`,
				`page_id`,
				`job_post_id`,
				`coordinates`,
				`industry`,
				`gender`,
				`budget_per_day`,
				`bid_per_engagement`,
				`currency_id`,
				`run_start_date`,
				`run_end_date`,
				`run_start_time`,
				`run_end_time`,
				`keyword`,
				`is_people_applied_include`,
				`is_people_viewed_job_include`,
				`is_people_viewed_page_include`,
				`is_deleted`,
				`created_by_user_id`,
				`updated_by_user_id`,
				`date_created`,
				`date_updated`,
				`is_active`
			)
			VALUES (
				'". $promotion_id ."',
				'". $data['page_id'] . "',
				'". $data['to_be_promoted_id'] . "',
				'". $data['coordinates'] . "',
				'". $data['industry'] . "',
				'". $data['gender'] . "',
				". $data['budget_per_day'] . ",
				". $data['bid_per_engagement'] . ",
				". $data['currency_id'] . ",
				'". $data['run_start_date'] . "',
				'". $data['run_end_date'] . "',
				'". $data['run_start_time'] . "',
				'". $data['run_end_time'] . "',
				'". $data['keyword'] . "',
				". $data['is_people_applied_include'] . ",
				". $data['is_people_viewed_job_include'] . ",
				". $data['is_people_viewed_page_include'] . ",
				0,
				'". $data['created_by_user_id'] . "',
				'". $data['created_by_user_id'] . "',
				NOW(),
				NOW(),
				1
			)

		";

		$this->db->beginTransaction();
		try {
			$opendayPromotionStatment = $this->db->prepare($sqlInsert);
			$opendayPromotionStatment->execute();

			$this->db->commit();
			return true;
		}
		catch(PDOException $e) {
	      $this->db->rollBack();
	      return false;
	    }
	}


	public function savePagePromotion($data)
	{
		$promotion_id = $this->getUniqueId();

		$checkIfHaveActivePromotion = $this->checkIfPromotedPage($data['to_be_promoted_id']);

		if($checkIfHaveActivePromotion) {
			return false;
		}

		$sqlInsert = "
			INSERT INTO i_promote_page (
				`promote_id`,
				`page_id`,
				`coordinates`,
				`industry`,
				`gender`,
				`budget_per_day`,
				`bid_per_engagement`,
				`currency_id`,
				`run_start_date`,
				`run_end_date`,
				`run_start_time`,
				`run_end_time`,
				`keyword`,
				`is_people_applied_include`,
				`is_people_viewed_job_include`,
				`is_people_viewed_page_include`,
				`is_deleted`,
				`created_by_user_id`,
				`updated_by_user_id`,
				`date_created`,
				`date_updated`,
				`is_active`
			)
			VALUES (
				'". $promotion_id ."',
				'". $data['to_be_promoted_id'] . "',
				'". $data['coordinates'] . "',
				'". $data['industry'] . "',
				'". $data['gender'] . "',
				". $data['budget_per_day'] . ",
				". $data['bid_per_engagement'] . ",
				". $data['currency_id'] . ",
				'". $data['run_start_date'] . "',
				'". $data['run_end_date'] . "',
				'". $data['run_start_time'] . "',
				'". $data['run_end_time'] . "',
				'". $data['keyword'] . "',
				". $data['is_people_applied_include'] . ",
				". $data['is_people_viewed_job_include'] . ",
				". $data['is_people_viewed_page_include'] . ",
				0,
				'". $data['created_by_user_id'] . "',
				'". $data['created_by_user_id'] . "',
				NOW(),
				NOW(),
				1
			)

		";



		$this->db->beginTransaction();
		try {
			$opendayPromotionStatment = $this->db->prepare($sqlInsert);
			$opendayPromotionStatment->execute();

			$this->db->commit();
			return true;
		}
		catch(PDOException $e) {
	      $this->db->rollBack();
	      return false;
	    }
	}

	public function saveUserPromotion($data)
	{
		$promotion_id = $this->getUniqueId();
		$checkIfHaveActivePromotion = $this->checkIfPromotedUser($data['to_be_promoted_id']);

		if($checkIfHaveActivePromotion) {
			return false;
		}

		$sqlInsert = "
			INSERT INTO i_promote_user_profile (
				`promote_id`,
				`user_id`,
				`coordinates`,
				`industry`,
				`gender`,
				`budget_per_day`,
				`bid_per_engagement`,
				`currency_id`,
				`run_start_date`,
				`run_end_date`,
				`run_start_time`,
				`run_end_time`,
				`keyword`,
				`is_people_applied_include`,
				`is_people_viewed_job_include`,
				`is_people_viewed_page_include`,
				`is_deleted`,
				`created_by_user_id`,
				`updated_by_user_id`,
				`date_created`,
				`date_updated`,
				`is_active`
			)
			VALUES (
				'". $promotion_id ."',
				'". $data['to_be_promoted_id'] . "',
				'". $data['coordinates'] . "',
				'". $data['industry'] . "',
				'". $data['gender'] . "',
				". $data['budget_per_day'] . ",
				". $data['bid_per_engagement'] . ",
				". $data['currency_id'] . ",
				'". $data['run_start_date'] . "',
				'". $data['run_end_date'] . "',
				'". $data['run_start_time'] . "',
				'". $data['run_end_time'] . "',
				'". $data['keyword'] . "',
				". $data['is_people_applied_include'] . ",
				". $data['is_people_viewed_job_include'] . ",
				". $data['is_people_viewed_page_include'] . ",
				0,
				'". $data['created_by_user_id'] . "',
				'". $data['created_by_user_id'] . "',
				NOW(),
				NOW(),
				1
			)

		";

		$this->db->beginTransaction();
		try {
			$opendayPromotionStatment = $this->db->prepare($sqlInsert);
			$opendayPromotionStatment->execute();

			$this->db->commit();
			return true;
		}
		catch(PDOException $e) {
	      $this->db->rollBack();
	      return false;
	    }
	}


	public function getPromotedOpenday($user)
	{

		$sql = "
			SELECT * FROM i_promote_openday as promote
			JOIN i_openday as openday on openday.openday_id = promote.openday_id
			JOIN i_openday_time as openday_time on openday.openday_id = openday_time.openday_id
			JOIN i_page as page on page.page_id = openday.page_id
			WHERE promote.is_active = 1
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
			AND promote.is_deleted = 0
		";

		$industryQuery = $this->generateIndustryFullTextQuery($user);
		if($industryQuery != "") {
			$sql .= "AND MATCH(industry) AGAINST('$industryQuery' IN BOOLEAN MODE)";
		}

		$keywordQuery = $this->generateKeywordFullTextQuery($user);

		if($keywordQuery != "") {
			$sql .= "AND (MATCH(keyword) AGAINST('$keywordQuery' IN BOOLEAN MODE) OR `keyword` = '')";
		}

	   try {
	   	  $output = [];
	      $statement = $this->db->prepare($sql);

	      $statement->execute();
	      $statement->setFetchMode(PDO::FETCH_ASSOC);

	      $results = $statement->fetchAll();
	      // var_dump($results);
	      foreach ($results as $promotion) {
	      	$isWithinRadius = $this->checkIfWithinRadius(json_decode($promotion['coordinates']), $user['latitude'], $user['longitude']);

	      	if($this->checkIfGenderQualified($user, $promotion) &&
	      	   !$this->checkIfAlreadyClicked($promotion['promote_id'], $user['user_id'])
	      	   && $isWithinRadius) {

	      	   	// $userAlreadyAppliedforJobInThisPage = $this->$checkIfUSerAlreadyAppliedForAnyJobOfThePage($promotion['page_id'], $user['user_id']);

	      	   	// if($promotion[''])
	      		array_push($output, $promotion);
	      	}
	      }

	      return $output;

	    }
	    catch(PDOException $e){
	      return $e;
	    }


	}


	public function getPromotedJobPost($user)
	{

		$sql = "
			SELECT * FROM i_promote_job_post as promote
			JOIN i_job_post as job_post on job_post.job_post_id = promote.job_post_id
			JOIN i_page as page on page.page_id = promote.page_id
			WHERE promote.is_active = 1
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
			AND promote.is_deleted = 0
		";


		$industryQuery = $this->generateIndustryFullTextQuery($user);
		if($industryQuery != "") {
			$sql .= "AND MATCH(industry) AGAINST('$industryQuery' IN BOOLEAN MODE)";
		}

		$keywordQuery = $this->generateKeywordFullTextQuery($user);
		if($keywordQuery != "") {
			$sql .= "AND (MATCH(keyword) AGAINST('$keywordQuery' IN BOOLEAN MODE) OR `keyword` = '')";
		}


	   try {
	   	  $output = [];
	      $statement = $this->db->prepare($sql);

	      $statement->execute();
	      $statement->setFetchMode(PDO::FETCH_ASSOC);

	      $results = $statement->fetchAll();

	      foreach ($results as $promotion) {
	      	$isWithinRadius = $this->checkIfWithinRadius(json_decode($promotion['coordinates']), $user['latitude'], $user['longitude']);
					var_dump($this->checkIfGenderQualified($user, $promotion) ." " .$this->checkIfAlreadyClicked($promotion['promote_id'], $user['user_id']) . " " . $isWithinRadius);
	      	if($this->checkIfGenderQualified($user, $promotion) &&
	      	   !$this->checkIfAlreadyClicked($promotion['promote_id'], $user['user_id'])
	      	   && $isWithinRadius
	      	   ) {
	      	   	$userApplied = $this->checkIfUserAlreadyAppliedForTheJob($promotion['job_post_id'], $user['user_id']);
							var_dump("applied:" . $userApplied);
							if(!$userApplied) {
								var_dump('user_applied');
	      	   		array_push($output, $promotion);
	      	   	}
	      	   	else if($userApplied && $promotion['is_people_applied_include']) {
								var_dump('user_not_applied');
		      		array_push($output, $promotion);
	      	   	}

	      	}
	      }

	      return $output;

	    }
	    catch(PDOException $e){
	      return $e;
	    }


	}

	public function getPromotedPage($user)
	{

		$sql = "
			SELECT * FROM i_promote_page as promote
			JOIN i_page as page on page.page_id = promote.page_id
			JOIN i_country as country on country.country_id = page.country_id
			WHERE promote.is_active = 1
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
			AND promote.is_deleted = 0
		";

		$industryQuery = $this->generateIndustryFullTextQuery($user);
		if($industryQuery != "") {
			$sql .= "AND MATCH(industry) AGAINST('$industryQuery' IN BOOLEAN MODE)";
		}

		$keywordQuery = $this->generateKeywordFullTextQuery($user);
		if($keywordQuery != "") {
			$sql .= "AND (MATCH(keyword) AGAINST('$keywordQuery' IN BOOLEAN MODE) OR `keyword` = '')";
		}


	   try {
	   	  $output = [];
	      $statement = $this->db->prepare($sql);

	      $statement->execute();
	      $statement->setFetchMode(PDO::FETCH_ASSOC);

	      $results = $statement->fetchAll();

	      foreach ($results as $promotion) {
	      	$isWithinRadius = $this->checkIfWithinRadius(json_decode($promotion['coordinates']), $user['latitude'], $user['longitude']);
	      	if($this->checkIfGenderQualified($user, $promotion) &&
	      	   !$this->checkIfAlreadyClicked($promotion['promote_id'], $user['user_id'])
	      	   && $isWithinRadius) {
	      		array_push($output, $promotion);
	      	}
	      }

	      return $output;

	    }
	    catch(PDOException $e){
	      return $e;
	    }


	}

	public function getPromotedUser($page, $userId)
	{
		$sql = "
			SELECT * FROM i_promote_user_profile as promote
			JOIN i_users_object_data as user on user.user_id = promote.user_id
			WHERE promote.is_active = 1
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
			AND promote.is_deleted = 0
			AND promote.industry = '". $page['industry']. "'";



	   try {
	   	  $output = [];
	      $statement = $this->db->prepare($sql);

	      $statement->execute();
	      $statement->setFetchMode(PDO::FETCH_ASSOC);

	      $results = $statement->fetchAll();

	      foreach ($results as $promotion) {
	      	$isWithinRadius = $this->checkIfWithinRadius(json_decode($promotion['coordinates']), $page['latitude'], $page['longitude']);
	      	if(!$this->checkIfAlreadyClicked($promotion['promote_id'], $userId)
	      	   && $isWithinRadius) {
	      		array_push($output, $promotion);
	      	}
	      }

	      return $output;

	    }
	    catch(PDOException $e){
	      return $e;
	    }


	}

	public function checkIfPromotedOpenday($opendayId)
	{
		$sql = "
			SELECT COUNT(*) as total FROM i_promote_openday
			WHERE is_active = 1
			AND openday_id = '$opendayId'
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $count = $statement->fetch();

		    return ($count['total'] > 0) ? true : false;
		}
		 catch(PDOException $e){
	      return $e;
	    }
	}

	public function checkIfPromotedJobPost($jobPostId)
	{
		$sql = "
			SELECT COUNT(*) as total FROM i_promote_job_post
			WHERE is_active = 1
			AND job_post_id = '$jobPostId'
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $count = $statement->fetch();

		    return ($count['total'] > 0) ? true : false;
		}
		 catch(PDOException $e){
	      return $e;
	    }
	}

	public function checkIfPromotedPage($pageId)
	{
		$sql = "
			SELECT COUNT(*) as total FROM i_promote_page
			WHERE is_active = 1
			AND page_id = '$pageId'
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $count = $statement->fetch();

		    return ($count['total'] > 0) ? true : false;
		}
		 catch(PDOException $e){
	      return $e;
	    }
	}
	public function checkIfPromotedUser($userId)
	{
		$sql = "
			SELECT COUNT(*) as total FROM i_promote_user_profile
			WHERE is_active = 1
			AND user_id = '$userId'
			AND CONCAT(`run_start_date`,' ',`run_start_time`) <= NOW()
			AND CONCAT(`run_end_date`,' ',`run_end_time`) >= NOW()
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $count = $statement->fetch();

		    return ($count['total'] > 0) ? true : false;
		}
		 catch(PDOException $e){
	      return $e;
	    }
	}

	private function  getUniqueId() {

        $sql       = "SELECT SUBSTRING(UUID(), 1, 8) as uniqueId";
        $statement = $this->db->prepare($sql);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $results = $statement->fetchAll();

        return $results[0]['uniqueId'];
    }

    private function getDistance( $latitude1, $longitude1, $latitude2, $longitude2 )
    {
	    $earth_radius = 6371;

	    $dLat = deg2rad( $latitude2 - $latitude1 );
	    $dLon = deg2rad( $longitude2 - $longitude1 );

	    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
	    $c = 2 * asin(sqrt($a));
	    $d = $earth_radius * $c;

	    return $d;
	}

	private function checkIfWithinRadius($coordinates, $latitude2, $longitude2)
	{
		foreach ($coordinates as $coordinate) {

			$distance = $this->getDistance($coordinate->lat, $coordinate->lng, $latitude2, $longitude2);

			if($distance <= $coordinate->radius) {
				return true;
			}
		}

		return false;
	}

	public function getProductId()
	{
		$sql = "
			SELECT product_id FROM i_product
			WHERE product_name = 'Promotion'
			LIMIT 1
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $product = $statement->fetch();

		    return $product['product_id'];
		}
		 catch(PDOException $e){
	      return $e;
	    }
	}

	public function getPromotionDetails($id, $type)
	{
		switch ($type) {
			case 'openday':
				$tableName = "i_promote_openday";
				break;
			case 'job':
				$tableName = "i_promote_job_post";
				break;
			case 'page':
				$tableName = "i_promote_page";
				break;
			case 'user':
				$tableName = "i_promote_user_profile";
				break;
			default:
				return false;
				break;
		}

		$sql = "
			SELECT * FROM $tableName
			WHERE promote_id = '$id'
			LIMIT 1
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $promotion = $statement->fetch();

		    return $promotion;
		}
		 catch(PDOException $e){
	      return $e;
	    }
	}

	public function recordEngagement($promotionId, $promotionType, $created_by_user_id)
	{
		$productId = $this->getProductId();
		$promotion = $this->getPromotionDetails($promotionId, $promotionType);
		$pageId = (array_key_exists("page_id", $promotion)) ? $promotion['page_id'] : '';
		$sql = "
			INSERT INTO `i_billing_transaction` (
				`transaction_id`,
				`page_id`,
				`promote_id`,
				`promote_type`,
				`product_id`,
				`amount`,
				`created_by_user_id`,
				`date_created`
			)
			VALUES (
				'". $this->getUniqueId() ."',
				'". $pageId . "',
				'". $promotionId . "',
				'". $promotionType . "',
				'". $productId . "',
				'". $promotion['bid_per_engagement'] . "',
				'". $created_by_user_id . "',
				NOW()

			)
		";


		$this->db->beginTransaction();
		try {
			$opendayPromotionStatment = $this->db->prepare($sql);
			$opendayPromotionStatment->execute();

			$this->db->commit();
			return true;
		}
		catch(PDOException $e) {
	      $this->db->rollBack();
	      return false;
	    }
	}

	public function computeTotalConsumedAmount($promote_id, $type)
	{
		$sqltotalTransactions = "
			SELECT SUM(amount) as totalConsumedAmount FROM i_billing_transaction
			WHERE promote_id = '$promote_id'
			AND promote_type = '$type'
		";
		$this->db->beginTransaction();

		try {
		    $statement = $this->db->prepare($sqltotalTransactions);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $totalTransactions = $statement->fetch();

			switch ($type) {
				case 'openday':
					$tableName = "i_promote_openday";
					break;
				case 'job':
					$tableName = "i_promote_job_post";
					break;
				case 'page':
					$tableName = "i_promote_page";
					break;
				case 'user':
					$tableName = "i_promote_user_profile";
					break;
				default:
					return false;
					break;
			}

			$consumedAmount = ($totalTransactions['totalConsumedAmount'] > 0) ? $totalTransactions['totalConsumedAmount'] : 0;
			$sql  = "
				UPDATE $tableName
				SET consumed_amount = '". $consumedAmount ."'
				WHERE promote_id = '$promote_id'
			";

			$updateStatement = $this->db->prepare($sql);
			$updateStatement->execute();

			$this->db->commit();
			return $consumedAmount;
		}
		 catch(PDOException $e){
	      return $e;
	    }


	}

	private function checkIfAlreadyClicked($promotionId, $userId)
	{
		$dubaiStartTime = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date("Y-m-d") . " 00:00:00")), "Asia/Dubai");
		$dubaiStartTime->tz('UTC');
		$dubaiEndTime = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date("Y-m-d") . " 23:59:59")), "Asia/Dubai");
		$dubaiEndTime->tz('UTC');

		$sql = "
			SELECT COUNT(*) as total FROM i_billing_transaction
			WHERE promote_id = '$promotionId'
			AND created_by_user_id = '$userId'
			AND date_created >= '" . $dubaiStartTime->format("Y-m-d H:i:s") ."'
			AND date_created <= '" . $dubaiEndTime->format("Y-m-d H:i:s") ."'
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $count = $statement->fetch();

		    return ($count['total'] > 0) ? true : false;
			}
			 catch(PDOException $e){
		      return $e;
		    }

	}

	private function checkIfGenderQualified($user, $promotion) {
		switch ($promotion['gender']) {
			case 'Male':
				return (strtolower($user['gender']) == "male") ? true : false;
				break;
			case 'Female':
				return (strtolower($user['gender']) == "female") ? true : false;
				break;

			default:
				// var_dump("default")
				return true;
				break;
		}
	}

	private function generateIndustryFullTextQuery($user)
	{

		$industryArray = json_decode($user['industry']);
		$output = "";
		if($user['industry']) {
			foreach ($industryArray as $industry) {
				$output .= $industry . " ";
			}
		}

		return $output;
	}

	private function generateKeywordFullTextQuery($user)
	{
		 // Nationality, Position Preferred, Gender
		$positionArray = json_decode($user['position_preferred']);
		$output = "";
		if($user['position_preferred']) {
			foreach ($positionArray as $postion) {
				$output .= $postion . " ";
			}
		}

		if($user['nationality']) {
			$output .= $user['nationality'] . " ";
		}


		if($user['gender']) {
			$output .= $user['gender'] . " ";
		}

		return $output;

	}

	private function checkIfUserAlreadyAppliedForTheJob($jobId, $userId)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM i_user_applied_jobs
			WHERE  user_id = '$userId'
			AND job_id = '$jobId'
			LIMIT 1
		";

		try {
		    $statement = $this->db->prepare($sql);

		    $statement->execute();
		    $statement->setFetchMode(PDO::FETCH_ASSOC);

		    $count = $statement->fetch();

		    return ($count['total'] > 0) ? true : false;
			}
			 catch(PDOException $e){
		      return $e;
		    }
	}

	// private function checkIfUSerAlreadyAppliedForAnyJobOfThePage($pageId, $userId)
	// {
	// 	$jobPostSql = "
	// 		SELECT job_post_id FROM i_job_post as job_post
	// 		JOIN i_job_group as job_group on job_group.job_group_id = job_post.job_group_id
	// 		WHERE page_id = '$pageId'
	// 	";

	// 	$userAppliedSql = "
	// 		SELECT job_id FROM i_user_applied_jobs
	// 		WHERE user_id = '$userId'
	// 	";

	// 	try {
	//       $jobPostStatement = $this->db->prepare($jobPostSql);

	//       $jobPostStatement->execute();
	//       $jobPostStatement->setFetchMode(PDO::FETCH_ASSOC);

	//       $jobPostResults = $jobPostStatement->fetchAll();


	//       $userAppliedStatement = $this->db->prepare($userAppliedSql);

	//       $userAppliedStatement->execute();
	//       $userAppliedStatement->setFetchMode(PDO::FETCH_ASSOC);

	//       $userAppliedResults = $userAppliedStatement->fetchAll();

	//       foreach ($userAppliedResults as $userApplication) {
	//       		if(in_array($userApplication['job_id'], $jobPostResults, true)) {
	//       			return true;
	//       		}
	//       }

	//       return false;

	//     }
	//     catch(PDOException $e){
	//       return $e;
	//     }
	// }

}
