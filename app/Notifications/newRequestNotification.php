<?php

namespace App\Notifications;

use App\Models\Crm\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class newRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;
    protected $isNewClient;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $request, bool $isNewClient = true)
    {
        $this->request = Request::with('contact')->findOrFail($request);
        $this->isNewClient = $isNewClient;
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
        $url = url('/admin/request/' . $this->request->id . '/show', [], true);

        return (new MailMessage)
                    ->from('no-reply@proart-fitness.ru')
                    ->subject($this->isNewClient ? 'Запрос от нового клиента на сайте ' . env('APP_NAME') : 'Новый запрос на сайте ' . env('APP_NAME'))
                    ->line($this->isNewClient ? 'Запрос от нового клиента на сайте!' : 'Новый запрос на сайте!')
                    ->line("Интересуется: " . $this->request->title)
                    ->line("Зал: " . $this->request->gym->address)
                    ->line("Контакт: " . $this->request->contact->name .", " . $this->request->contact->phone)
                    ->action('Подробнее', $url)
                    ->line('Обработайте запрос как можно скорее.');
    }

    /**
     * Get the telegram representation of the notification.
     * 
     * @param  mixed  $notifiable
     * @return \NotificationChannels\Telegram\TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        $url = url('/admin/request/' . $this->request->id . '/show');
        $message = $this->isNewClient 
            ? 'Запрос от нового клиента на сайте ' . env('APP_NAME') 
            : 'Новый запрос на сайте ' . env('APP_NAME');
        $message .= " \"" . $this->request->title . "\"\n";
        $message .= "Зал: " . $this->request->gym->address . "\n";
        $message .= "Контакт: " . $this->request->contact->name . ", " . $this->request->contact->phone;

        return TelegramMessage::create()
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
