<?php

if (file_exists(__DIR__.'/config.php')) {
    require_once(__DIR__.'/config.php');
}

class test_client {

    static protected $t; // target.

    static protected $tests;

    static protected $results;

    public function __construct($baseurl = 'https://dev.moodle.com', $wstoken = '') {

        self::$t = new StdClass;

        // Setup this settings for tests.
        self::$t->baseurl = $baseurl; // The remote Moodle url to push in.
        self::$t->wstoken = $wstoken; // the service token for access.

        self::$t->service = '/webservice/rest/server.php';
    }

    public static function test_get_ticket($uidsource = 'id', $uid = 0) {

        if (empty(self::$t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $params = array('wstoken' => self::$t->wstoken,
                        'wsfunction' => 'auth_ticket_get_ticket',
                        'moodlewsrestformat' => 'json',
                        'uidsource' => $uidsource,
                        'uid' => $uid,
                        'term' => 'short');

        $serviceurl = self::$t->baseurl.self::$t->service;

        return self::send($serviceurl, $params);
    }

    public static function test_validate_ticket($ticket = '') {

        if (empty(self::$t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $params = array('wstoken' => self::$t->wstoken,
                        'wsfunction' => 'auth_ticket_validate_ticket',
                        'moodlewsrestformat' => 'json',
                        'ticket' => $ticket);

        $serviceurl = self::$t->baseurl.self::$t->service;

        return self::send($serviceurl, $params);
    }

    protected static function send($serviceurl, $params) {
        $ch = curl_init($serviceurl);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        echo "Firing CUrl $serviceurl ... \n";
        print_r($params);
        if (!$result = curl_exec($ch)) {
            echo "CURL Error : ".curl_errno($ch).' '.curl_error($ch)."\n";
            return;
        }

        if (preg_match('/EXCEPTION/', $result)) {
            echo $result;
            return;
        }

        $objresult = json_decode($result);
        if (empty($objresult)) {
            echo "JSon failure on : $result\n";
        }
        print_r($objresult);
        return $objresult;
    }

    public static function define_tests() {

        self::$tests = array();

        $test = new StdClass;
        $test->name = 'Generate a ticket by user id';
        $test->function = 'test_get_ticket';
        $test->params = array('id', 2);
        self::$tests[] = $test;

        $test = new StdClass;
        $test->name = 'Generate a ticket by username';
        $test->function = 'test_get_ticket';
        $test->params = array('username', 'admin'); 
        self::$tests[] = $test;

        $test = new StdClass;
        $test->name = 'Validate ticket with the previous result';
        $test->function = 'test_validate_ticket';
        $test->params = array('$results[-1]'); 
        self::$tests[] = $test;

        $test = new StdClass;
        $test->name = 'Test validation on empty ticket';
        $test->function = 'test_validate_ticket';
        $test->params = array(''); // Test empty ticket.
        self::$tests[] = $test;

        $ticketvalue = 'NEVBMzQwNkYwNTNCN0I4RTk5MkUyNkUxRDMyRTJFNzFFMTcyRjJBNkZFMjkwMUVBMDMzQTVBN0YxMzJGMTQxQ0Ey';
        $ticketvalue .= 'RUQ4NEQ1RDQ4Mzc2QUMxQjI2NEQ4NUNFQTI3ODU5QTZCRjk3ODQ5Qzk0QUY0NENCQkM2NDRFNTY1Q0RCRTA0RTZ';
        $ticketvalue .= 'EMDlDQkVDNDlGNEFBQUE3ODM1QkZFNzA0NzgwMA==';

        $test = new StdClass;
        $test->name = 'Test validation on obsolete (or not compatible) ticket';
        $test->function = 'test_validate_ticket';
        $test->params = array($ticketvalue);
        self::$tests[] = $test;

        $test = new StdClass;
        $test->name = 'Test validation on malformed non empty ticket';
        $test->function = 'test_validate_ticket';
        $test->params = array('9999999999999999999999999999999999999999');
        self::$tests[] = $test;
    }

    // Dynamically replace a param by a previous result.
    public static function preprocess_params(&$params, $currentix) {
        foreach ($params as &$p) {
            if (preg_match('/\\$results\\[(-)?(\\d+)\\]/', $p, $matches)) {
                // We are a result reference.
                // matches[1] = sign
                $sign = $matches[1];
                $ix = $matches[2];

                // matches[2] = ix
                if (empty($sign)) {
                    $p = self::$results[$ix];
                } else {
                    $p = self::$results[$currentix - $matches[2]];
                }
            }
        }
    }

    public static function run_tests($argv) {
        $calledtests = @$argv[1];

        $calledtestixs = array();
        $all = false;
        if (empty($calledtests) || ($calledtests == 'all')) {
            $all = true;
        } else {
            $calledtestixs = explode(',', $calledtests);
        }

        $ix = 1;
        foreach (self::$tests as $test) {

            if (in_array($ix, $calledtestixs) || $all) {
                echo "Running test $ix ###################### {$test->name} \n";
                self::preprocess_params($test->params, $ix - 1);
                self::$results[$ix - 1] = call_user_func_array("test_client::".$test->function, $test->params);
            }
            $ix++;
        }
    }
}

// Effective test scenario.
if (file_exists(__DIR__.'/config.php')) {
    echo "Initializing with config at $baseurl / $wstoken\n";
    $client = new test_client($baseurl, $wstoken); // Initialise class.
} else {
    $client = new test_client(); // Initialise class.
}

\test_client::define_tests();
\test_client::run_tests($argv);