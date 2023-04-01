<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75)]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(length: 1000)]
    private ?string $tariff = null;

    #[ORM\Column(length: 255)]
    private ?string $typeOfOffer = null;

    #[ORM\Column]
    private ?int $nbMinimumPlaces = null;

    #[ORM\Column]
    private ?int $numberOrderPage = null;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'offer', orphanRemoval: true, cascade: ['persist'], fetch: "EAGER")]
    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    #[ORM\PrePersist]
    public function setPublishedAt(): self
    {
        $this->publishedAt = new \DateTime();

        return $this;
    }

    public function getTariff(): ?string
    {
        return $this->tariff;
    }

    public function setTariff(string $tariff): self
    {
        $this->tariff = $tariff;

        return $this;
    }

    public function getTypeOfOffer(): ?string
    {
        return $this->typeOfOffer;
    }

    public function setTypeOfOffer(string $typeOfOffer): self
    {
        $this->typeOfOffer = $typeOfOffer;

        return $this;
    }

    public function getNbMinimumPlaces(): ?int
    {
        return $this->nbMinimumPlaces;
    }

    public function setNbMinimumPlaces(int $nbMinimumPlaces): self
    {
        $this->nbMinimumPlaces = $nbMinimumPlaces;

        return $this;
    }

    public function getNumberOrderPage(): ?int
    {
        return $this->numberOrderPage;
    }

    public function setNumberOrderPage(int $numberOrderPage): self
    {
        $this->numberOrderPage = $numberOrderPage;

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $file->setOffer($this);
            $this->files->add($file);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            if ($file->getOffer() === $this) {
                $file->setOffer(null);
            }
        }

        return $this;
    }
}
