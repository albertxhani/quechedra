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

    public $concurreny = "10";

    public $retries = 20;

}