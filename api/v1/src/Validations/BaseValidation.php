<?php
namespace App\Validations;

class BaseValidation
{

  /**
   * List of constraints
   *
   * @var array
   */
  protected $rules = [];

  /**
   * List of customized messages
   *
   * @var array
   */
  protected $messages = [];

  /**
   * List of returned errors in case of a failing assertion
   *
   * @var array
   */
  public $errors = [];

  /**
   * Just another constructor
   *
   * @return void
   */
  public function __construct()
  {
      $this->initRules();
      $this->initMessages();
  }

  // /* Abstract Methods
  //  */
  // abstract public function initRules();
  // abstract public function initMessages();
  public function initMessages()
  {
    $this->messages = [
        'alpha'                 => '{{name}} must only contain alphabetic characters.',
        'alnum'                 => '{{name}} must only contain alpha numeric characters and dashes.',
        'numeric'               => '{{name}} must only contain numeric characters.',
        'noWhitespace'          => '{{name}} must not contain white spaces.',
        'length'                => '{{name}} must length between {{minValue}} and {{maxValue}}.',
        'email'                 => 'Please make sure you typed a correct email address.',
        'creditCard'            => 'Please make sure you typed a valid card number.',
        'date'                  => 'Make sure you typed a valid date for the {{name}} ({{format}}).',
        'password_confirmation' => 'Password confirmation doesn\'t match.'
    ];
  }
  /**
 * Assert validation rules.
 *
 * @param array $inputs
 *   The inputs to validate.
 * @return boolean
 *   True on success; otherwise, false.
 */
  public function assert(array $inputs)
  {
      $isSuccess = true;
      foreach ($this->rules as $rule => $validator) {
          try {
              $validator->assert($inputs[$rule]);
          } catch (\Respect\Validation\Exceptions\NestedValidationException $ex) {
              array_push($this->errors, $ex->findMessages($this->messages));
              $isSuccess = false;
          }
      }
      return $isSuccess;
  }

}
