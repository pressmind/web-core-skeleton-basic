<?php
namespace Pressmind;
use Exception;
use Pressmind\Log\Writer;
use Pressmind\REST\Client;

if(!file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.json')) {
    echo "Welcome to the initial installation of your new pressmind web-core project.\n";
    echo "Please enter some initial configuration data.\n";

    $default_config_file = dirname(dirname(__DIR__))
        . DIRECTORY_SEPARATOR
        . 'vendor'
        . DIRECTORY_SEPARATOR
        . 'Pressmind'
        . DIRECTORY_SEPARATOR
        . 'sdk'
        . DIRECTORY_SEPARATOR
        . 'config.default.json';

    $config = json_decode(file_get_contents($default_config_file), true);

    $db_host = readline("Enter Database Host [127.0.0.1]: ");
    $db_port = readline("Enter Database Port [3306]: ");
    $db_name = readline("Enter Database Name: ");
    $db_user = readline("Enter Database Username: ");
    $db_password = readline("Enter Database User Password: ");
    $pressmind_api_key = readline("Enter Pressmind API Key: ");
    $pressmind_api_user = readline("Enter Pressmind API User: ");
    $pressmind_api_password = readline("Enter Pressmind API Password: ");

    if(empty($db_host)) $db_host = '127.0.0.1';
    if(empty($db_port)) $db_port = '3306';

    $config['development']['database']['username'] = $db_user;
    $config['development']['database']['password'] = $db_password;
    $config['development']['database']['host'] = $db_host;
    $config['development']['database']['port']= $db_port;
    $config['development']['database']['dbname'] = $db_name;

    $config['development']['rest']['client']['api_key'] = $pressmind_api_key;
    $config['development']['rest']['client']['api_user'] = $pressmind_api_user;
    $config['development']['rest']['client']['api_password'] = $pressmind_api_password;

    file_put_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$args = $argv;
$args[1] = isset($argv[1]) ? $argv[1] : null;

$namespace = 'Pressmind\ORM\Object';

if($args[1] != 'only_static') {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'static_models.php';
    /** @var array $models */
    foreach ($models as $model) {
        try {
            /** @var ORM\Object\AbstractObject $model_name */
            $model_name = $namespace . $model;
            Writer::write('Creating database table for model: ' . $model_name, Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
            $scaffolder = new DB\Scaffolder\Mysql(new $model_name());
            $scaffolder->run($args[1] === 'drop_tables');
            foreach ($scaffolder->getLog() as $scaffolder_log) {
                Writer::write($scaffolder_log, Writer::OUTPUT_FILE, 'install', Writer::TYPE_INFO);
            }
        } catch (Exception $e) {
            Writer::write($model_name, Writer::OUTPUT_BOTH, 'install', Writer::TYPE_ERROR);
            Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'install', Writer::TYPE_ERROR);
        }
    }

    try {
        Writer::write('Requesting and parsing information on media object types ...', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
        $importer = new Import();
        $ids = [];
        $client = new Client();
        $response = $client->sendRequest('ObjectType', 'getAll');
        $config = Registry::getInstance()->get('config');
        $media_types = [];
        $media_types_pretty_url = [];
        $media_types_allowed_visibilities = [];
        foreach ($response->result as $item) {
            $media_types[$item->id_type] = $item->type_name;
            $ids[] = $item->id_type;
            $pretty_url = [
                'prefix' => '/' . HelperFunctions::human_to_machine($item->type_name) . '/',
                'field' => ['name' => 'name'],
                'strategy' => 'none',
                'suffix' => '/'
            ];
            $media_types_pretty_url[$item->id_type] = $pretty_url;
            $media_types_allowed_visibilities[$item->id_type] = [30];
        }
        $config['data']['media_types'] = $media_types;
        $config['data']['media_types_pretty_url'] = $media_types_pretty_url;
        $config['data']['media_types_allowed_visibilities'] = $media_types_allowed_visibilities;
        Registry::getInstance()->get('config_adapter')->write($config);
        Registry::getInstance()->add('config', $config);
        $importer->importMediaObjectTypes($ids);
    } catch (Exception $e) {
        Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'install', Writer::TYPE_ERROR);
    }
}

if($args[1] == 'with_static' || $args[1] == 'only_static') {
    try {
        Writer::write('Dumping static data, this may take a while ...', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
        Writer::write('Data will be dumped using "gunzip" and "mysql" with "shell_exec". Dump data in ' . HelperFunctions::buildPathString([dirname(__DIR__), 'src', 'data']) . ' by hand if shell_exec fails', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
        $config = Registry::getInstance()->get('config');
        Writer::write('Dumping data for pmt2core_airlines', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
        shell_exec("gunzip < " . HelperFunctions::buildPathString([dirname(__DIR__), 'src', 'data', 'pmt2core_airlines.sql.gz']) . " | mysql --host=" . $config['database']['host'] . " --user=" . $config['database']['username'] . " --password=" . $config['database']['password'] . " " . $config['database']['dbname']);
        Writer::write('Dumping data for pmt2core_airports', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
        shell_exec("gunzip < " . HelperFunctions::buildPathString([dirname(__DIR__), 'src', 'data', 'pmt2core_airports.sql.gz']) . " | mysql --host=" . $config['database']['host'] . " --user=" . $config['database']['username'] . " --password=" . $config['database']['password'] . " " . $config['database']['dbname']);
        Writer::write('Dumping data for pmt2core_banks', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
        shell_exec("gunzip < " . HelperFunctions::buildPathString([dirname(__DIR__), 'src', 'data', 'pmt2core_banks.sql.gz']) . " | mysql --host=" . $config['database']['host'] . " --user=" . $config['database']['username'] . " --password=" . $config['database']['password'] . " " . $config['database']['dbname']);
        Writer::write('Dumping data for pmt2core_geozip', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
        shell_exec("gunzip < " . HelperFunctions::buildPathString([dirname(__DIR__), 'src', 'data', 'pmt2core_geozip.sql.gz']) . " | mysql --host=" . $config['database']['host'] . " --user=" . $config['database']['username'] . " --password=" . $config['database']['password'] . " " . $config['database']['dbname']);
    } catch (Exception $e) {
        Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'install', Writer::TYPE_ERROR);
    }
} else {
    Writer::write('Necessary static data has not been dumped. Dump static data by calling "install.php with_static" or "install.php only_static"', Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
    Writer::write('You can also dump the data by hand. Data resides here: ' . HelperFunctions::buildPathString([dirname(__DIR__), 'src', 'data']), Writer::OUTPUT_BOTH, 'install', Writer::TYPE_INFO);
}

echo '!!!ATTENTION: Please have a look at the CHANGES.md file, there might be important information on breaking changes!!!!' . "\n";
