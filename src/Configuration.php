<?php

declare(strict_types=1);

namespace LM\WebFramework;

use LM\WebFramework\DataStructures\AppObject;

final class Configuration
{
    private AppObject $configData;

    private string $configFolderPath;

    private string $language;

    public function __construct(string $configFolderPath, string $language)
    {
        $this->configFolderPath = $configFolderPath;
        $this->language = $language;

        $env = file_get_contents("$configFolderPath/.env.json");
        $envLocal = file_get_contents("$configFolderPath/.env.local.json");
        $configData = false !== $envLocal ? json_decode($envLocal, true) : [];
        $configData += false !== $env ? json_decode($env, true) : [];
        $this->configData = new AppObject($configData);
    }

    public function getBoolSetting(string $key): bool
    {
        return $this->configData[$key];
    }
    
    public function getConfigAppData(): AppObject
    {
        return $this->configData;
    }

    public function getErrorLoggedInControllerFQCN(): string
    {
        return $this->getSetting('routeErrorAlreadyLoggedInControllerFQCN');
    }

    public function getErrorNotFoundControllerFQCN(): string
    {
        return $this->getSetting('routeError404ControllerFQCN');
    }

    public function getErrorNotLoggedInControllerFQCN(): string
    {
        return $this->getSetting('routeErrorNotLoggedInControllerFQCN');
    }

    public function getHomeUrl(): string
    {
        return $this->getSetting('homeUrl');
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getPathOfAppDirectory(): string
    {
        return $this->configFolderPath;
    }

    public function getPathOfUploadedFiles(): string
    {
        return $this->configFolderPath . '/' . $this->configData['pathOfUploadedFiles'];
    }

    public function getPublicUrl(): string
    {
        return $this->getSetting('publicUrl');
    }

    public function getRoutes(): AppObject
    {
        return $this->configData['routes'];
    }

    public function getServerErrorControllerFQCN(): string
    {
        return $this->getSetting('serverErrorControllerFQCN');
    }

    public function getSetting(string $key): string
    {
        return $this->configData[$key];
    }

    public function isDev(): bool
    {
        return $this->getBoolSetting('dev');
    }
}