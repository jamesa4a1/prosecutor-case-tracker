<?php

namespace App\Notifications;

use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CaseAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public CaseModel $case,
        public ?User $assignedBy = null
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $assignerName = $this->assignedBy?->name ?? 'An administrator';

        return (new MailMessage)
            ->subject('New Case Assigned - ' . $this->case->case_number)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("{$assignerName} has assigned a new case to you.")
            ->line("**Case Number:** {$this->case->case_number}")
            ->line("**Title:** {$this->case->title}")
            ->line("**Offense:** {$this->case->offense}")
            ->line("**Status:** {$this->case->status?->label()}")
            ->action('View Case', url('/cases/' . $this->case->id))
            ->line('Please review the case details at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'case_assigned',
            'case_id' => $this->case->id,
            'case_number' => $this->case->case_number,
            'case_title' => $this->case->title,
            'assigned_by' => $this->assignedBy?->name,
            'assigned_by_id' => $this->assignedBy?->id,
            'message' => "You have been assigned to case {$this->case->case_number}",
        ];
    }
}
