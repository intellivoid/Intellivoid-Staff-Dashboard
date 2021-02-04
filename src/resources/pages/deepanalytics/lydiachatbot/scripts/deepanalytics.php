<?php

    use DeepAnalytics\DeepAnalytics;
    use DeepAnalytics\Exceptions\DataNotFoundException;
    use DeepAnalytics\Objects\Date;
    use DeepAnalytics\Utilities;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Response;
    use IntellivoidAPI\Objects\AccessRecord;

    if(isset($_GET['action']))
    {
        switch($_GET['action'])
        {
            case "deepanalytics.locale":
                da_get_locale();
                break;

            case "deepanalytics.get_range":
                da_get_range();
                break;

            case "deepanalytics.get_monthly_data":
                da_get_monthly_data();
                break;

            case "deepanalytics.get_hourly_data":
                da_get_hourly_data();
                break;
        }
    }

    /**
     * Returns the locale data defined in the language file
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    function da_get_locale()
    {
        $Results = array(
            'status' => true,
            'payload' => array(
                'DEEPANALYTICS_NO_DATA_ERROR' => "No Data Available",
                'DEEPANALYTICS_GENERIC_ERROR' => "DeepAnalytics Error (%s)",
                'DEEPANALYTICS_MONTHLY_USAGE' => "Monthly Usage",
                'DEEPANALYTICS_DAILY_USAGE' => "Daily Usage",
                'DEEPANALYTICS_DATA_SELECTOR' => "Data",
                'DEEPANALYTICS_DATE_SELECTOR' => "Date",
                'DEEPANALYTICS_DATA_ALL' => "All"
            )
        );

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
        exit(0);
    }

    /**
     * Fetches the data from the DeepAnalytics database
     *
     * @param string $source
     * @param string $name
     * @param DeepAnalytics $deepAnalytics
     * @param Date $selectedDate
     * @return array|null
     */
    function db_hourly_data_fetch(string $source, string $name, DeepAnalytics $deepAnalytics, Date $selectedDate): ?array
    {
        try
        {
            $hourlyDataResults = $deepAnalytics->getHourlyData(
                $source, $name, 0, true,
                (int)$_POST["year"], (int)$_POST["month"], (int)$_POST["day"]);

            $return_results = [
                //"name" => $hourlyDataResults->Name,
                "total" => $hourlyDataResults->Total,
                "data" =>[]
            ];

            foreach($hourlyDataResults->getData(true) as $key => $value)
            {
                $return_results["data"][Utilities::generateFullHourStamp($selectedDate, $key)] = $value;
            }

            return $return_results;
        }
        catch(DataNotFoundException)
        {
            return null;
        }
    }

    /**
     * Returns the hourly data which is used to be rendered in the linechart
     *
     * @noinspection DuplicatedCode
     * @noinspection PhpNoReturnAttributeCanBeAddedInspection
     */
    function da_get_hourly_data()
    {
        if(isset($_POST['year']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 10,
                'message' => 'Missing parameter \'year\''
            );

            Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            Response::finishRequest();
            exit(0);
        }

        if(isset($_POST['month']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 11,
                'message' => 'Missing parameter \'month\''
            );

            Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            Response::finishRequest();
            exit(0);
        }

        if(isset($_POST['day']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 12,
                'message' => 'Missing parameter \'day\''
            );

            Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            Response::finishRequest();
            exit(0);
        }

        /** @var DeepAnalytics $deepAnalytics */
        $deepAnalytics = DynamicalWeb::getMemoryObject('deepanalytics');

        $SelectedDate = new Date();
        $SelectedDate->Year = (int)$_POST['year'];
        $SelectedDate->Month = (int)$_POST['month'];
        $SelectedDate->Day = (int)$_POST['day'];

        $Results = array();

        $Results["messages"] = db_hourly_data_fetch("tg_lydia", "messages", $deepAnalytics, $SelectedDate);
        $Results["created_sessions"] = db_hourly_data_fetch("tg_lydia", "created_sessions", $deepAnalytics, $SelectedDate);
        $Results["ai_responses"] = db_hourly_data_fetch("tg_lydia", "ai_responses", $deepAnalytics, $SelectedDate);

        $Results = array(
            'status' => true,
            'results' => $Results
        );

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
        exit(0);
    }

    /**
     * Fetches the data from the DeepAnalytics database
     *
     * @param string $source
     * @param string $name
     * @param DeepAnalytics $deepAnalytics
     * @return array|null
     */
    function db_monthly_data_fetch(string $source, string $name, DeepAnalytics $deepAnalytics): ?array
    {

        try
        {
            $monthlyData = $deepAnalytics->getMonthlyData(
                $source, $name, 0, true,
                (int)$_POST["year"], (int)$_POST["month"]);

            $return_results = [
                "total" => $monthlyData->Total,
                "data" => []
            ];

            foreach($monthlyData->getData(true) as $key => $value)
            {
                $return_results['data'][Utilities::generateHourlyStamp(
                    (int)$_POST['year'], (int)$_POST['month'], $key
                )] = $value;
            }

            return $return_results;
        }
        catch(DataNotFoundException)
        {
            return null;
        }
    }

    /**
     * Returns the monthly data that's used to render the linechart
     * @noinspection PhpNoReturnAttributeCanBeAddedInspection
     */
    function da_get_monthly_data()
    {
        if(isset($_POST['year']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 10,
                'message' => 'Missing parameter \'year\''
            );

            header('Content-Type: application/json');
            print(json_encode($Results));
            exit(0);
        }

        if(isset($_POST['month']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 11,
                'message' => 'Missing parameter \'month\''
            );

            header('Content-Type: application/json');
            print(json_encode($Results));
            exit(0);
        }

        /** @var DeepAnalytics $deepAnalytics */
        $deepAnalytics = DynamicalWeb::getMemoryObject('deepanalytics');

        $AnalyticalResults = array();

        $AnalyticalResults["messages"] = db_monthly_data_fetch("tg_lydia", "messages", $deepAnalytics);
        $AnalyticalResults["created_sessions"] = db_monthly_data_fetch("tg_lydia", "created_sessions", $deepAnalytics);
        $AnalyticalResults["ai_responses"] = db_monthly_data_fetch("tg_lydia", "ai_responses", $deepAnalytics);

        $Results = array(
            "status" => true,
            "results" => $AnalyticalResults
        );

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
        exit(0);
    }

    /**
     * Returns the data range that's available
     * @noinspection PhpNoReturnAttributeCanBeAddedInspection
     */
    function da_get_range()
    {
        /** @var DeepAnalytics $deepAnalytics */
        $deepAnalytics = DynamicalWeb::getMemoryObject('deepanalytics');

        /** @var AccessRecord $AccessRecord */
        $AccessRecord = DynamicalWeb::getMemoryObject('access_record');

        $Results = array(

            "messages" => array(
                "monthly" => $deepAnalytics->getMonthlyDataRange(
                    "tg_lydia", "messages", $AccessRecord->ID),
                "hourly" => $deepAnalytics->getHourlyDataRange(
                    "tg_lydia", "messages", $AccessRecord->ID),
                "text" => "Messages"
            ),

            "created_sessions" => array(
                "monthly" => $deepAnalytics->getMonthlyDataRange(
                    "tg_lydia", "created_sessions", $AccessRecord->ID),
                "hourly" => $deepAnalytics->getHourlyDataRange(
                    "tg_lydia", "created_sessions", $AccessRecord->ID),
                "text" => "Created Sessions"
            ),

            "ai_responses" => array(
                "monthly" => $deepAnalytics->getMonthlyDataRange(
                    "tg_lydia", "ai_responses", $AccessRecord->ID),
                "hourly" => $deepAnalytics->getHourlyDataRange(
                    "tg_lydia", "ai_responses", $AccessRecord->ID),
                "text" => "AI Messages"
            ),

        );

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
        exit(0);
    }