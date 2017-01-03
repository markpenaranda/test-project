<?php

namespace App\Models;

use PDO;

class User extends BaseModel
{
    protected $tableName = "i_users";
    // Indicate columns that can be explicitly filter via REST CALL.
    protected $filterable = ['user_id', 'username', 'primary_email', 'primary_mobile', 'cv'];
   
    // Visible columns in REST
    protected $visible = ['user_id', 'username', 'name', 'primary_email', 'first_name', 'middle_name', 'last_name', 'primary_mobile', 'cv'];

    // Timestamp Columns
    protected $created_at = "date_created";
    protected $updated_at = "date_updated";

    // Indicate the column for softDelete;
    public $softDelete = "is_deleted";


}
