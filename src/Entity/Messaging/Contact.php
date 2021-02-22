<?php

namespace App\Entity\Messaging;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Users\User;
use App\Repository\Messaging\ContactRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"contact:read"}},
 *     denormalizationContext={"groups"={"contact:write"}},
 * )
 */
class Contact
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     * @Groups({"contact:read", "contact:write"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     * @Groups({"contact:read", "contact:write"})
     * @Assert\NotBlank()
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"contact:read", "contact:write"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"contact:read", "contact:write"})
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Groups({"contact:read", "contact:write"})
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"contact:read", "contact:write"})
     * @Assert\NotBlank()
     */
    private $user;
    
    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"contact:read"})
     */
    protected $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    
    
}
