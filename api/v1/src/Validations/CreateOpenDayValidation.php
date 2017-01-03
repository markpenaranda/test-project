<?php
namespace App\Validations;

use \Respect\Validation\Validator as V;

class CreateOpenDayValidation extends BaseValidation
{

  public function initRules()
  {
    $this->rules['event_name'] = V::alnum()->length(4, 30)->setName('Event name');
  }

}
