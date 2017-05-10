<?php 

namespace App\Helpers;

class SocketNotifier {

  protected $socketBaseUrl = "https://openday.jobsglobal.com:3000";

  private function send($type, $userId, $message, $title = "", $link = "") 
  {
    $title = urlencode($title);
    $message = urlencode($message);
    $url = $this->socketBaseUrl . "/notifier/" . $userId;
    $params = "?message=" . $message . "&category=". $type . "&link=" . $link . "&tag=" . $title;

    $url = $url . $params;
    
    $curl = curl_init();

    $action_url = $url;
      curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT =>  "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48",
        ));
    $resp = curl_exec($curl);
  }

  public function flash($userId, $message, $title = "", $link = "") 
  {
    $this->send("flash", $userId, $message, $title, $link);
  } 

  public function persistent($userId, $message, $title = "", $link = "") 
  {
    $this->send("persistent", $userId, $message, $title, $link);
  } 


}