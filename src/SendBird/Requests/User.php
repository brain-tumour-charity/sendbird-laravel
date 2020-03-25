<?php

namespace SendBird\Requests;

use Carbon\Carbon;

class User extends BaseRequest
{
    public function listUsers()
    {
        return $this->request('/users', 'get');
    }

    public function viewAUser($user_id)
    {
        return $this->request("/users/{$user_id}", 'get');
    }

    public function createUser($user_id, $username, $thumbnail = null)
    {
        $body = [
            'user_id' => $user_id,
            'nickname' => $username,
            'profile_url' => $thumbnail,
            'issue_access_token' => true,
            'issue_session_token' => true,
            'has_ever_logged_in' => true
        ];
        return $this->request('/users', 'post', $body);
    }

    public function createUserIssueToken($user_id, $username = null)
    {
        $body = [
            'user_id' => $user_id,
            'nickname' => $username,
            'profile_url' => null,
            'issue_access_token' => true,
            'has_ever_logged_in' => true
        ];
        return $this->request('/users', 'post', $body);
    }

    public function updateUser($user_id, $username, $thumbnail = null)
    {
        $body = [
            'nickname' => $username,
            'profile_url' => $thumbnail,
            'issue_access_token' => true,
            'issue_session_token' => true,
            'is_active' => true,
            'has_ever_logged_in' => true,
            'last_seen_at' => Carbon::now()->timestamp
        ];
        return $this->request("/users/{$user_id}", 'put', $body);
    }

    public function issueAccessToken($user_id)
    {
        $body = [
            'issue_access_token' => true,
            'is_active' => true,
            'has_ever_logged_in' => true,
            'last_seen_at' => Carbon::now()->timestamp
        ];
        return $this->request("/users/{$user_id}", 'put', $body);
    }

    public function getMetadata($user_id, $key)
    {
        $key = urlencode($key);
        $metadata = $this->request("/users/{$user_id}/metadata/{$key}", 'get');
        if (isset($metadata[$key])) {
            return $metadata[$key];
        }

        return null;
    }

    public function createMetadata($user_id, $key, $value)
    {
        return $this->updateMetadata($user_id, $key, $value);
    }

    public function updateMetadata($user_id, $key, $value)
    {
        $key = urlencode($key);
        $body = [
            'value' => $value,
            'upsert' => true
        ];
        return $this->request("/users/{$user_id}/metadata/{$key}", 'put', $body);
    }

    public function deleteMetadata($user_id, $key)
    {
        $key = urlencode($key);
        $response = $this->request("/users/{$user_id}/metadata/{$key}", 'delete');
        return count($response) == 0 ?: $response;
    }

    public function addDeviceToken($user_id, $token_type, $token)
    {
        $body = [
            'user_id' => $user_id,
            'token_type' => $token_type
        ];

        $type = $token_type === "gcm" ? "gcm_reg_token" : "apns_device_token";

        $body[$type] = $token;
        return $this->request("/users/{$user_id}/push/{$token_type}", 'post', $body);
    }
}
