<?php

namespace SendBird\Requests;

class Group extends BaseRequest
{
    public function createChannel($name, $cover_url = null, $isPublic = true, $users = [], $hidden = [], $extras = [])
    {
        $body = [
            'name' => $name,
            'cover_url' => $cover_url,
            'is_public' => $isPublic,
            'user_ids' => $users,
            'strict' => true
        ];

        if (!empty($extras)) {
            $body = array_merge($body, $extras);
        }

        if (!empty($hidden)) {
            $hidden_status = [];
            foreach ($hidden as $id) {
                $hidden_status[$id] = "hidden_allow_auto_unhide";
            }
            $body['hidden_status'] = json_decode(json_encode($hidden_status));
        }

        return $this->request('/group_channels', 'post', $body);
    }

    public function viewAChannel($channel_url)
    {
        $body = [
            'channel_url' => $channel_url,
            'show_member' => true
        ];

        return $this->request("/group_channels/{$channel_url}", 'get', $body);
    }

    public function listMembers($channel_url)
    {
        $body = [
            'channel_url' => $channel_url
        ];

        return $this->request("/group_channels/{$channel_url}/members", 'get', $body);
    }

    public function inviteAsMembers($channel_url, $users = [])
    {
        $body = [
            'channel_url' => $channel_url,
            'user_ids' => $users
        ];

        return $this->request("/group_channels/{$channel_url}/invite", 'post', $body);
    }

    public function leaveChannel($url, $users = [], $should_leave_all = false)
    {
        $body = [
            'user_ids' => $users,
            'should_leave_all' => $should_leave_all
        ];

        return $this->request("/group_channels/{$url}/leave", 'PUT', $body);
    }

    public function deleteChannel($url)
    {
        return $this->request("/group_channels/{$url}", 'delete');
    }
}
