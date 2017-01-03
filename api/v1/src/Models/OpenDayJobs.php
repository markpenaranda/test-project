<?php
namespace App\Models;

class OpenDayJobs extends BaseModel
{
  protected $tableName = "i_openday_link_job";
  // Indicate columns that can be explicitly filter via REST CALL.
  protected $filterable = ['openday_id'];

  // Visible columns in REST
  // protected $visible = ['time_id','openday_id','start_time', 'end_time', 'is_filled', 'date_filled'];

  // Timestamp
  // protected $updated_at = "date_updated";

  // Soft Delete
  // public $softDelete = "is_deleted";


}
