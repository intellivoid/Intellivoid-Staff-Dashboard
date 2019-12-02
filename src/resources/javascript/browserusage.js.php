<?PHP

    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');
    HTML::importScript('db_render_helper');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $Results = get_distinct($IntellivoidAccounts->database, 'tracking_user_agents', 'browser');
    $DeviceUsage = array();

    if(count($Results) > 0)
    {
        foreach($Results as $result)
        {
            $DeviceUsage[$result['browser']] = get_total_items($IntellivoidAccounts->database, 'tracking_user_agents', 'id', 'browser', $result['browser']);
        }
    }
?>

google.charts.load("current", {
    packages: ["corechart"]
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable(
        <?PHP
            $JsResults = [
                ['Task', 'Browser Usage']
            ];
            foreach($DeviceUsage as $device => $value)
            {
                $JsResults[] = [$device, $value];
            }
            HTML::print(json_encode($JsResults), false);

        ?>
    );

    var options = {
        pieHole: 0.4,
        'backgroundColor': 'transparent',
        colors: ['#007bff', '#ff6258', '#19d895', '#ff7f2e', '#24e8a6', '#065efd'],
        chartArea: {
            width: 260
        },
        legend: {
            textStyle: {
                color: '#fff'
            }
        }
    };

    var Donutchart = new google.visualization.PieChart(document.getElementById('browser-chart'));
    Donutchart.draw(data, options);
}