<?php

namespace Duxravel\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Manage extends Notification
{
    use Queueable;

    private string $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
        //
    }

    /**
     * @param $notifiable
     * @return string[]
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'invoice' => $this->invoice,
        ];
    }
}
