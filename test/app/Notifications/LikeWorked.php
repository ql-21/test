<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Work;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LikeWorked extends Notification implements ShouldQueue
{
    use Queueable;

    public  $work;
    public  $user;

    /**
     * Create a new notification instance.
     * @return void
     */
    public function __construct(Work $work,User $user)
    {
        $this->work = $work;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }


    public function toDatabase($notifiable)
    {
        $work = $this->work;
        $user = $this->user;
//        $links = $this->work->links(['#reply'.$this->reply->id]);

        return [
            'work_id'=>$work->id,
            'work_title'=>$work->title,
            'user_id'=>$user->id,
            'user_name'=>$user->nickname,
            'user_avatar'=>$user->avatar,
        ];
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
