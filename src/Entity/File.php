<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\OneToOne(mappedBy: 'file', cascade: ['persist', 'remove'])]
    private ?Partnership $partnership = null;

    #[ORM\ManyToMany(targetEntity: Offer::class, mappedBy: 'files')]
    private Collection $partnerships;

    public function __construct()
    {
        $this->partnerships = new ArrayCollection();
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

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath(string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getPartnership(): ?Partnership
    {
        return $this->partnership;
    }

    public function setPartnership(?Partnership $partnership): self
    {
        // unset the owning side of the relation if necessary
        if ($partnership === null && $this->partnership !== null) {
            $this->partnership->setFile(null);
        }

        // set the owning side of the relation if necessary
        if ($partnership !== null && $partnership->getFile() !== $this) {
            $partnership->setFile($this);
        }

        $this->partnership = $partnership;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getPartnerships(): Collection
    {
        return $this->partnerships;
    }

    public function addPartnership(Offer $partnership): self
    {
        if (!$this->partnerships->contains($partnership)) {
            $this->partnerships->add($partnership);
            $partnership->addFile($this);
        }

        return $this;
    }

    public function removePartnership(Offer $partnership): self
    {
        if ($this->partnerships->removeElement($partnership)) {
            $partnership->removeFile($this);
        }

        return $this;
    }
}
