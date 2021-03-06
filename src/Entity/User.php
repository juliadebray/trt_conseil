<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function PHPUnit\Framework\isEmpty;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company_address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Offers::class, mappedBy="author_id", orphanRemoval=true)
     */
    private $offers_published;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $curriculum_vitae;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="candidate", orphanRemoval=true)
     */
    private $candidatures;

    public function __construct()
    {
        $this->offers_published = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
    }

    public function __toString()
    {
        if($this->getRoles() == ['ROLE_ADMIN'])
        {
            return 'Administrateur: ' . $this->getUserIdentifier();
        }
        if($this->getRoles() == ['ROLE_RECRUITER'])
        {
            return 'Recruteur: ' . $this->getUserIdentifier();
        }
        if($this->getRoles() == ['ROLE_CANDIDATE'])
        {
            return 'Candidat : ' . $this->getUserIdentifier();
        }
        if($this->getRoles() == ['ROLE_CONSULTANT'])
        {
            return 'Consultant : ' . $this->getUserIdentifier();
        }
        return 'erreur';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles[] = current($roles);

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(?string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getCompanyAddress(): ?string
    {
        return $this->company_address;
    }

    public function setCompanyAddress(?string $company_address): self
    {
        $this->company_address = $company_address;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Offers[]
     */
    public function getOffersPublished(): Collection
    {
        return $this->offers_published;
    }

    public function addOffersPublished(Offers $offersPublished): self
    {
        if (!$this->offers_published->contains($offersPublished)) {
            $this->offers_published[] = $offersPublished;
            $offersPublished->setAuthorId($this);
        }

        return $this;
    }

    public function removeOffersPublished(Offers $offersPublished): self
    {
        if ($this->offers_published->removeElement($offersPublished)) {
            // set the owning side to null (unless already changed)
            if ($offersPublished->getAuthorId() === $this) {
                $offersPublished->setAuthorId(null);
            }
        }

        return $this;
    }

    public function getCurriculumVitae(): ?string
    {
        return $this->curriculum_vitae;
    }

    public function setCurriculumVitae(?string $curriculum_vitae): self
    {
        $this->curriculum_vitae = $curriculum_vitae;

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setCandidate($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getCandidate() === $this) {
                $candidature->setCandidate(null);
            }
        }

        return $this;
    }
}
