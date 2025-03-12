<?php

namespace App\Notifications;

use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $bid;

    /**
     * Create a new notification instance.
     */
    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $status = ucfirst($this->bid->status);
        $jobTitle = $this->bid->job->title;

        return (new MailMessage)
            ->subject("Bid {$status} - {$jobTitle}")
            ->line("Your bid for the job '{$jobTitle}' has been {$this->bid->status}.")
            ->line("Bid Amount: $" . number_format($this->bid->amount, 2))
            ->action('View Bid', route('bids.show', $this->bid))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'bid_id' => $this->bid->id,
            'job_id' => $this->bid->job_id,
            'status' => $this->bid->status,
            'amount' => $this->bid->amount,
            'job_title' => $this->bid->job->title,
        ];
    }
} 