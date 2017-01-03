<?php
namespace App\Models;

class OpenDayTimeBreakdown extends BaseModel
{
  protected $tableName = "i_openday_time_breakdown";
  // Indicate columns that can be explicitly filter via REST CALL.
  protected $filterable = ['openday_id', 'scheduled_user_id'];

  // Visible columns in REST
  protected $visible = ['time_breakdown_id','openday_id','time_start', 'time_end', 'is_filled', 'date_filled'];

  // Timestamp
  protected $updated_at = "date_updated";

  // Soft Delete
  public $softDelete = "is_deleted";


}
