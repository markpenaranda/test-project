<?php
namespace App\Validations;

use \Respect\Validation\Validator as V;

class UpdateOpenDayValidation extends BaseValidation
{

  public function initRules()
  {
    $this->rules['event_name'] = V::alnum()->length(4, 200)->setName('Event name');
  }

}
