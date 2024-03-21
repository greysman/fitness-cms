<?php

namespace App\Notifications;

use App\Models\Cms\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class newReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->review = Review::findOrFail($id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/admin/review/' . $this->review->id . '/edit', [], true);

        return (new MailMessage)
                    ->from('no-reply@proart-fitness.ru')
                    ->subject('У вас новый отзыв на сайте ' . env('APP_NAME'))
                    ->line($this->review->name . ' оставил(-a) новый отзыв.')
                    ->line("Отзыв: " . $this->review->text)
                    ->action('Подробнее', $url);
    }

    /**
     * Get the telegram representation of the notification.
     * 
     * @param  mixed  $notifiable
     * @return \NotificationChannels\Telegram\TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        $url = url('/admin/review/' . $this->review->id . '/edit');
        $message = $this->review->name . " оставил(-a) новый отзыв: \n" . $this->review->text;

        return TelegramMessage::create()
            ->to($notifiable->routes['telegram'])
            ->content($message)
            ->button('Подробнее', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
