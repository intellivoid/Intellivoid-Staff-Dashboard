<?php

    use COASniffle\COASniffle;
    use COASniffle\Exceptions\KhmException;
    use COASniffle\Exceptions\OtlException;
    use COASniffle\Handlers\KHM;
    use COASniffle\Handlers\OTL;
    use DynamicalWeb\Runtime;

    Runtime::import('COASniffle');
    new COASniffle();

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
        try
        {
            return KHM::registerHost($ip_address, $user_agent);
        }
        catch (KhmException $e)
        {
            throw new Exception($e->getErrorMessage(), $e->getStatusCode());
        }
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
        try
        {
            $OTLUser = OTL::verifyCode($code, $host_id, $user_agent, "Intellivoid Staff Dashboard");

            return $OTLUser->toArray();
        }
        catch (OtlException $e)
        {
            throw new Exception($e->getErrorMessage(), $e->getStatusCode());
        }
    }