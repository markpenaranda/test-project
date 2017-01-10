<?php

namespace App\Controllers;

use App\Models\User;
/**
 * Class OpenDayController
 * @package App\Controllers
 */

class UserController extends BaseController
{
   public $openDayResouce, $createOpenDayValidation;

   public function __construct(
       User $user
   ){
       $this->user = $user;
   }

   public function show($request, $response, $args) 
   {
       $user = $this->user->getUserById($args['user_id']);
       
       if($user) {
        return $response->withStatus(200)->withJson($user);
       }
       return $response->withStatus(400);
   }

   public function getUserBySamePage($request, $response, $args) 
   {
       $page = $this->user->getUserPageId($args['user_id']);
       $users = $this->user->getUsersByPageId($page['page_id']);

       return $response->withStatus(200)->withJson($users);

   }

}

