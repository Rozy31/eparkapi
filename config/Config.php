<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-REquested-With, X-Auth-User");
// ini_set('display_errors', '0');
date_default_timezone_set("Asia/Manila");
set_time_limit(1000);

class Connection

{
    protected $dsn = "pgsql:host=ec2-3-224-8-189.compute-1.amazonaws.com;port=5432;dbname=dbio4itjqhpvel;";
    protected $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false
    ];

    public function connect()
    {
        return new \PDO($this->dsn, 'hngmyazemcltft', '80ef102420f32af9f61d98d1d43a0bc7ca6b34ace7ac5a68036fae77dc506538', $this->options);
    }
}
