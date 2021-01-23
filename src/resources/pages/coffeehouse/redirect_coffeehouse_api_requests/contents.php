<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $Application = $IntellivoidAccounts->getApplicationManager()->getApplication(
            ApplicationSearchMethod::byName, 'CoffeeHouse'
    );

    Actions::redirect(DynamicalWeb::getRoute('api/request_records', array(
        'filter' => 'application_id',
        'value' => $Application->ID
    )));
