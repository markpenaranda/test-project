<?php
namespace App\Models;

class OpenDayTime extends BaseModel
{
  protected $tableName = "i_openday_time";
  // Indicate columns that can be explicitly filter via REST CALL.
  protected $filterable = ['openday_id'];

  // Visible columns in REST
  protected $visible = ['time_id','openday_id','start_time', 'end_time'];


}
