<?php

namespace SendBird\Requests;

class Message extends BaseRequest
{
    public function sendMessage($messsage, $messsage_type, $sender_id, $channel_url, $channel_type = 'group_channels', $extras = [])
    {
        $body = [
            'channel_type' => $channel_type,
            'channel_url' => $channel_url,
            'message_type' => $messsage_type,
            'message' => $messsage,
            'user_id' => $sender_id
        ];

        if (!empty($extras)) {
            $body = array_merge($body, $extras);
        }

        return $this->request("/{$channel_type}/{$channel_url}/messages", 'post', $body);
    }

    public function sendAdminMessage($messsage, $channel_url, $channel_type = 'group_channels')
    {
        $body = [
            'channel_type' => $channel_type,
            'channel_url' => $channel_url,
            'message_type' => 'ADMM',
            'message' => $messsage
        ];

        return $this->request("/{$channel_type}/{$channel_url}/messages", 'post', $body);
    }

    public function deleteMessage($channel_type = 'open_channels', $channel_url, $message_id)
    {
        return $this->request("/{$channel_type}/{$channel_url}/messages/{$message_id}", 'delete');
    }
}
