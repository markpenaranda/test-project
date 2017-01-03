<?php
namespace App\Models;

class OpenDayAttendees extends BaseModel
{
  protected $tableName = "i_openday_attendees";
  // Indicate columns that can be explicitly filter via REST CALL.
  protected $filterable = ['openday_id', 'user_id', 'status', 'is_scheduled'];

  // Visible columns in REST
  // protected $visible = ['time_id','openday_id','start_time', 'end_time', 'is_filled', 'date_filled'];

    // Timestamp Columns
    protected $created_at = "date_joined";

  // Soft Delete
  // public $softDelete = "is_deleted";


}
