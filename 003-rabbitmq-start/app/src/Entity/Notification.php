<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification")
 */
class Notification
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", length=11, unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $notificationId;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Regex(pattern="/^([A-Z|a-z|0-9| |.|,]){1,255}$/")
     */
    private $message;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @return int
     */
    public function getNotificationId(): int
    {
        return $this->notificationId;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}
