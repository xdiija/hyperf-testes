<?php declare(strict_types=1);

namespace App\Model\User;

use Hyperf\DbConnection\Model\Model;

/**
 * @property $id
 * @property $username
 * @property $email
 * @property $created_at
 * @property $updated_at
 */
class UserInterface extends Model
{
    private ?int $id = null;
    private ?string $username;
    private ?string $name;
    private ?string $email;
    private ?string $contentLanguage;
    private ?string $country;

    public function getId(): int
    {
        if ($this->id == null)
            return -1;
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getContentLanguage(): string
    {
        return $this->contentLanguage;
    }

    public function setContentLanguage(?string $contentLanguage): void
    {
        $this->contentLanguage = $contentLanguage;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getUrl(): string
    {
        return $this->Url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public static function setFromUser(User $user): self
    {
        $userInterface = new self();
        $userInterface->setId($user->getId());
        $userInterface->setUsername($user->getUsername());
        $userInterface->setName($user->getSetting('name'));
        $userInterface->setEmail($user->getSetting('email'));
        $userInterface->setContentLanguage($user->getSetting('content_language'));
        $userInterface->setCountry($user->getSetting('country'));
        $userInterface->setUrl($user->getSetting('url'));
        return $userInterface;
    }
}