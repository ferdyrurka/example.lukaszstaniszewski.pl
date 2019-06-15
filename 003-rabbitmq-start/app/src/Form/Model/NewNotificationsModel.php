<?php
declare(strict_types=1);

namespace App\Form\Model;

/**
 * Class NewNotificationsModel
 * @package App\Form\Model
 */
class NewNotificationsModel
{
    /**
     * @var string|null
     */
    private $jsonData;

    /**
     * @return string|null
     */
    public function getJsonData(): ?string
    {
        return $this->jsonData;
    }

    /**
     * @param string|null $jsonData
     */
    public function setJsonData(?string $jsonData): void
    {
        $this->jsonData = $jsonData;
    }
}
