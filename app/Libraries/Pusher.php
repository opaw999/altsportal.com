<?php

namespace App\Libraries;

require_once ROOTPATH . 'vendor/autoload.php';

use Pusher\Pusher as PusherClient;

class Pusher
{
  // old pusher data
  // private $appId     = '1213133';
  // private $appKey    = 'b49163b6de1bae4c1c2f';
  // private $appSecret = 'ff1f638d357e0d19019c';

  private $appId      = "2061423";
  private $appKey     = "530a8cc03f6febd40e05";
  private $appSecret  = "08d6c17a45aa70937a36";

  private $options = [
    'cluster' => 'ap1',
    'useTLS'  => true,
  ];

  private $pusher;

  public function __construct()
  {
    $this->pusher = new PusherClient(
      $this->appKey,
      $this->appSecret,
      $this->appId,
      $this->options
    );
  }

  public function broadcast($channel, $event, $data)
  {
    $this->pusher->trigger($channel, $event, $data);
  }
}
