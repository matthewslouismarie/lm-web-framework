<?php

namespace LM\WebFramework\Routing;

class UriBuilder implements IUriBuilder
{
    private $config;

    public function __construct(string $config_filename)
    {
        $config_file = file_get_contents($config_filename);
        $this->config = json_decode($config_file, true);
    }

    public function getUri(string $resource_name): string
    {
        return $this->getProtocol().$this->getServerName().'/'.$this->config['prefix'].$resource_name;
    }

    private function getProtocol(): string
    {
        return $this->config['protocol'];
    }

    private function getServerName(): string
    {
        return $this->config['server-name'];
    }
}