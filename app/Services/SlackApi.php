<?php declare(strict_types=1);

namespace App\Services;

class SlackApi
{

    /**
     * Post Slack Message
     * @param string $title
     * @param string $actionText
     * @param string $actionUrl
     */
    public static function message($title = '*Title*', $actionText = null, $actionUrl = null)
    {
        $message = [
            'channel'    => config('slack.channel'),
            'username'   => config('slack.username'),
            'icon_emoji' => config('slack.emoji'),
            'blocks'     => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => $title,
                    ],
                ],
            ],
        ];
        if (isset($actionText, $actionUrl)) {
            data_set($message, 'blocks.0.accessory', [
                'type' => 'button',
                'url'  => $actionUrl,
                'text' => [
                    'type' => 'plain_text',
                    'text' => $actionText,
                ],
            ]);
        }
        static::send($message);
    }

    /**
     * Send Message
     * @param array $data
     */
    protected static function send(array $data)
    {
        try{
            $ch = curl_init(config('slack.endpoint'));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_exec($ch);
            curl_close($ch);
        }catch (\Exception $exception){
            logger($exception);
        }
    }
}
