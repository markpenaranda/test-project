<?php 

namespace App\Controllers;

use App\Models\Notification;
use App\Helpers\Paginate;
use App\Helpers\SocketNotifier;

class NotificationController extends BaseController 
{

  protected $notificationResource, $paginate;

  public function __construct(
    Notification $notification,
    Paginate $paginate,
    SocketNotifier $socketNotifier
  ){
    $this->notificationResource = $notification;
    $this->paginate = $paginate;
    $this->notifier = $socketNotifier;
  }

  public function index($request, $response)
  {
    $userId = $request->getParam('user_id');
    $paginate = $this->paginate->init($request->getParam('page'));

    $notifications = $this->notificationResource
                          ->all($userId, $paginate);

    $output =  array(
        'success' => true,
        'data' => $notifications
      );

    return $response->withStatus(200)->withJson($output);
  }


  public function store($request, $response, $args) 
  {
    $save = $this->notificationResource
                 ->save(
                    $request->getParam('name'), 
                    $request->getParam('message'), 
                    $request->getParam('link') 
                  );

    return array(
        'success' => true,
              );
  }

  public function read($request, $response, $args)
  {
    $notificationId = $args['id'];
    $this->notificationResource
         ->setAsRead($notificationId);

    return array(
        'success' => true
              );
  }

  public function setAsRead($request, $response, $args)
  {
    $userId = $request->getParam('user_id');
    $this->notificationResource->setAllAsReadByUserId($userId);

    $output =  array( 'success' => true );
    return $response->withStatus(200)->withJson($output);
  }

  public function countNotifications($request, $response, $args) 
  {
    $userId = $request->getParam('user_id');
    $data = $this->notificationResource->countUnread($userId);

    return $response->withStatus(200)->withJson(array (
        'success' => true,
        'total' => (int) $data
      ));
  }


  public function testNotify($request, $response, $args) 
  {
    $title="Openday Interview";
    $message = "Interviewing: Candidate No. 3 \n You are Candidate No: 45";
    $userId = $request->getParam("userId");
    $link = $request->getParam("link");

    $this->notificationResource->save($userId, $message, "TEST", $link);
    $this->notifier->flash($userId, $message, $title, $link);


    return $response->withStatus(200)->withJson(true);

  }



}