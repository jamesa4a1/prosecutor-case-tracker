<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    protected User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to the Prosecutor Case Tracking System')
            ->greeting('Welcome, ' . $this->user->name . '!')
            ->line('Your account has been successfully created in the **Prosecutor Case Tracking System**.')
            ->line('**Account Details:**')
            ->line('- **Email:** ' . $this->user->email)
            ->line('- **Role:** ' . $this->user->role->value)
            ->line('You can now access the system to manage cases, track hearings, and collaborate with your team.')
            ->action('Login to Dashboard', url('/login'))
            ->line('If you did not create this account, please contact the system administrator immediately.')
            ->salutation('Best regards, Office of the Prosecutor');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'message' => 'Welcome to the Prosecutor Case Tracking System',
        ];
    }
}
