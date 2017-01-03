<?php

namespace App\Models;

use PDO;

class OpenDay extends BaseModel
{
    protected $tableName = "i_openday";
    // Indicate columns that can be explicitly filter via REST CALL.
    protected $filterable = ['openday_id', 'event_name', 'event_date'];
    // Indicate columns that can be explicitly search *LIKE* via REST CALL.
    protected $searchable = ['event_name'];
    // Visible columns in REST
    protected $visible = ['event_name', 'openday_id', 'introduction', 'event_date', 'stopped_adding_queue', 'time_interval_per_candidate'];

    // Timestamp Columns
    protected $created_at = "date_created";
    protected $updated_at = "date_updated";

    // Indicate the column for softDelete;
    public $softDelete = "is_deleted";


}
