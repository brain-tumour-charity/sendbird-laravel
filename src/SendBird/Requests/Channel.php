<?php

namespace SendBird\Requests;

class Channel extends BaseRequest
{
    public function createChannel($name, $url, $cover_url, $operators = [])
    {
        $body = [
            'name' => $name,
            'channel_url' => $url,
            'cover_url' => $cover_url,
            'operator_ids' => $operators,
        ];

        return $this->request('/open_channels', 'post', $body);
    }

    public function viewAChannel($channel_url)
    {
        return $this->request("/open_channels/{$channel_url}", 'get', $body);
    }

    public function listParticipants($channel_url)
    {
        $body = [
            'channel_url' => $channel_url
        ];

        return $this->request("/open_channels/{$channel_url}/participants", 'get', $body);
    }

    public function viewBanForChannel($channel_url, $user_id)
    {
        return $this->request("/open_channels/{$channel_url}/ban/{$user_id}", 'get');
    }

    public function banUser($channel_url, $user_id, $seconds = -1, $agent_id = null, $reason = null)
    {
        $body = [
            'user_id' => $user_id,
            'seconds' => $seconds,
        ];

        if ($agent_id) {
            $body['agent_id'] = $agent_id;
        }

        if ($reason) {
            $body['description'] = $reason;
        }

        return $this->request("/open_channels/{$channel_url}/ban", 'post', $body);
    }

    public function unbanUser($channel_url, $user_id)
    {
        return $this->request("/open_channels/{$channel_url}/ban/{$user_id}", 'delete');
    }
}
