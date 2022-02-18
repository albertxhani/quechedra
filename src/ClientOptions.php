<?php

namespace Quechedra;

class ClientOptions
{
    public $redis = [
        "host" => "",
        "port" => "",
        "auth" => [
            "username" => "",
            "password" => "",
        ]
    ];

    public $concurrency = "10";

    public $retries = 20;

}