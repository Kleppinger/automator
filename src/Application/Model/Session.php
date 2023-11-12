<?php

namespace Automator\Application\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "sessions")]
class Session
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    public string $identifier;

    #[ORM\OneToOne(inversedBy: 'session', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|null $user = null;

    /**
     * @var bool
     *
     * When this session is set to true, it means the user did an successful login, and
     * when he logs in a second time, the current session using the user gets logged out
     */
    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private bool $overridesSession;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $expires;

    #[ORM\Column(type: 'boolean')]
    private bool $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function isOverridesSession(): bool
    {
        return $this->overridesSession;
    }

    public function setOverridesSession(bool $overridesSession): void
    {
        $this->overridesSession = $overridesSession;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    public function setExpires(\DateTime $expires): void
    {
        $this->expires = $expires;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }



}