<?php

namespace App\Models;

use PDO;

class Promotion
{


	public function __construct(PDO $db)
  	{
      $this->db = $db;
	}


	public function saveOpendayPromotion($data) 
	{

		$promotion_id = $this->getUniqueId();
		$sqlInsert = "
			INSERT INTO i_promote_openday (
				`promote_id`,
				`page_id`,
				`openday_id`,
				`location_lat`,
				`location_lng`,
				`location_radius`,
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
				'". $data['location_lat'] . "',
				'". $data['location_lng'] . "',
				'". $data['location_radius'] . "',
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
		$sqlInsert = "
			INSERT INTO i_promote_job_post (
				`promote_id`,
				`page_id`,
				`job_post_id`,
				`location_lat`,
				`location_lng`,
				`location_radius`,
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
				'". $promotion_id ."'
				'". $data['page_id'] . "',
				'". $data['to_be_promoted_id'] . "',
				'". $data['location_lat'] . "',
				'". $data['location_lng'] . "',
				'". $data['location_radius'] . "',
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
		$sqlInsert = "
			INSERT INTO i_promote_page (
				`promote_id`,
				`page_id`,
				`location_lat`,
				`location_lng`,
				`location_radius`,
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
				'". $promotion_id ."'
				'". $data['to_be_promoted_id'] . "',
				'". $data['location_lat'] . "',
				'". $data['location_lng'] . "',
				'". $data['location_radius'] . "',
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
		$sqlInsert = "
			INSERT INTO i_promote_user_profile (
				`promote_id`,
				`user_id`,
				`location_lat`,
				`location_lng`,
				`location_radius`,
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
				'". $promotion_id ."'
				'". $data['to_be_promoted_id'] . "',
				'". $data['location_lat'] . "',
				'". $data['location_lng'] . "',
				'". $data['location_radius'] . "',
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

	  private function  getUniqueId() {

        $sql       = "SELECT SUBSTRING(UUID(), 1, 8) as uniqueId";
        $statement = $this->db->prepare($sql);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $results = $statement->fetchAll();

        return $results[0]['uniqueId'];
    }

}