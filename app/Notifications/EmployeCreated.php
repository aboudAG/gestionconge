<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeCreated extends Notification
{
    use Queueable;

    protected $matricule;
    protected $password;

    public function __construct($matricule, $password)
    {
        $this->matricule = $matricule;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Votre identifiant: ' . $this->matricule)
                    ->line('Votre mot de passe est: ' . $this->password)
                    ->line('Merci d\'utiliser notre application!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}