<?php

namespace App\Entity;

use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SurveyRepository::class)]
class Survey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column]
    private ?bool $activated = null;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: SurveyAnswer::class, orphanRemoval: true)]
    private Collection $SurveyAnswers;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: SurveyOption::class, orphanRemoval: true)]
    private Collection $SurveyOptions;

    public function __construct()
    {
        $this->SurveyAnswers = new ArrayCollection();
        $this->SurveyOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function isActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * @return Collection<int, SurveyAnswer>
     */
    public function getSurveyAnswers(): Collection
    {
        return $this->SurveyAnswers;
    }

    public function addSurveyAnswer(SurveyAnswer $surveyAnswer): self
    {
        if (!$this->SurveyAnswers->contains($surveyAnswer)) {
            $this->SurveyAnswers->add($surveyAnswer);
            $surveyAnswer->setSurvey($this);
        }

        return $this;
    }

    public function removeSurveyAnswer(SurveyAnswer $surveyAnswer): self
    {
        if ($this->SurveyAnswers->removeElement($surveyAnswer)) {
            // set the owning side to null (unless already changed)
            if ($surveyAnswer->getSurvey() === $this) {
                $surveyAnswer->setSurvey(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SurveyOptions>
     */
    public function getSurveyOptions(): Collection
    {
        return $this->SurveyOptions;
    }

    public function addSurveyOption(SurveyOptions $surveyOption): self
    {
        if (!$this->SurveyOptions->contains($surveyOption)) {
            $this->SurveyOptions->add($surveyOption);
            $surveyOption->setSurvey($this);
        }

        return $this;
    }

    public function removeSurveyOption(SurveyOptions $surveyOption): self
    {
        if ($this->SurveyOptions->removeElement($surveyOption)) {
            // set the owning side to null (unless already changed)
            if ($surveyOption->getSurvey() === $this) {
                $surveyOption->setSurvey(null);
            }
        }

        return $this;
    }
}
