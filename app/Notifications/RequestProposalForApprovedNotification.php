<?php

namespace App\Notifications;

use App\Broadcasting\HrmsNotifyNextApproverChannel;
use App\Enums\ApprovalModels;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Notification;

class RequestProposalForApprovedNotification extends Notification
{
    use Queueable;

    private $token;

    private $model;

    public $id;

    public function __construct($token, Project $model)
    {
        $this->token = $token;
        $this->model = $model;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [HrmsNotifyNextApproverChannel::class];
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'A Project Proposal Request that needs your approval.',
            'module' => 'Projects',
            'request_type' => ApprovalModels::PROJECT_PROPOSAL_REQUEST->name,
            'request_id' => $this->model->id,
            'action' => 'View',
        ];
    }
}
