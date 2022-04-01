<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: "Indiquer le nom de la personne")]
    #[Assert\Length(
        min: 2,
        max: 80,
        minMessage: "Nom trop court !",
        maxMessage: "Nom trop long !"
    )]
    private $nom;

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: "Indiquer le prénom de la personne")]
    #[Assert\Length(
        min: 2,
        max: 80,
        minMessage: "Prénom trop court !",
        maxMessage: "Prénom trop long !"
    )]
    private $prenom;

    #[ORM\Column(type: 'boolean')]
    private $tireAuSort;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type(Address::class)]
    #[Assert\NotBlank(message: "Indiquer le prénom de la personne")]
    #[Assert\Length(
        min: 6,
        max: 255,
        minMessage: "Adresse email trop courte !",
        maxMessage: "Adresse email trop longue !"
    )]
    private $email;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dateDuGateau;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTireAuSort(): ?bool
    {
        return $this->tireAuSort;
    }

    public function setTireAuSort(bool $tireAuSort): self
    {
        $this->tireAuSort = $tireAuSort;

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

    public function getDateDuGateau(): ?\DateTimeInterface
    {
        return $this->dateDuGateau;
    }

    public function setDateDuGateau(?\DateTimeInterface $dateDuGateau): self
    {
        $this->dateDuGateau = $dateDuGateau;

        return $this;
    }
}
