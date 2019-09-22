<?php

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

    $AuthenticationConfiguration = DynamicalWeb::getConfiguration('auth');
    if($AuthenticationConfiguration['localhost_development'])
    {
        define('AUTH_ENDPOINT', "http://" . $AuthenticationConfiguration['local_host'] . "/auth/");
    }
    else
    {
        define('AUTH_ENDPOINT', "https://" . $AuthenticationConfiguration['remote_endpoint'] . "/auth/");
    }

    /**
     * Gets the host ID from an IP Address and User Agent
     *
     * @param string $ip_address
     * @param string $user_agent
     * @return string
     * @throws Exception
     */
    function khm_GetHostId(string $ip_address, string $user_agent): string
    {
        $ip_address = urlencode($ip_address);
        $user_agent = urlencode($user_agent);

        $request_url = AUTH_ENDPOINT . "khm?remote_host=" . $ip_address . "&user_agent=" . $user_agent;
        $response = json_decode(file_get_contents($request_url), true);

        if($response['status'] == false)
        {
            throw new Exception($response['message'], $response['response_code']);
        }

        return $response['host_id'];
    }

    /**
     * Verifies the given authentication code, returns account details on success
     *
     * @param string $code
     * @param string $host_id
     * @param string $user_agent
     * @return array
     * @throws Exception
     */
    function otl_VerifyCode(string $code, string $host_id, string $user_agent): array
    {
        $vendor = urlencode("Intellivoid Staff Dashboard");
        $host_id = urlencode($host_id);
        $code = urlencode($code);
        $user_agent = urlencode($user_agent);

        $request_url = AUTH_ENDPOINT . "otl?auth_code=" . $code . "&host_id=" . $host_id . "&user_agent=" . $user_agent . "&vendor=" . $vendor;
        $response = json_decode(file_get_contents($request_url), true);

        if($response['status'] == false)
        {
            throw new Exception($response['message'], $response['response_code']);
        }

        return $response['account'];
    }