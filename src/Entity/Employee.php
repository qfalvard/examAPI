<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("all_employees")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("all_employees")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("all_employees")
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="employees")
     * @ORM\JoinColumn(nullable=true)
     */
    private $job;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("all_employees")
     */
    private $employementDate;

    public function __construct()
    {
        $this->setEmployementDate(new \DateTime());
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job)
    {
        $this->job = $job;

        return $this;
    }

    public function getEmployementDate()
    {
        return $this->employementDate->format('d-m-Y');
    }

    public function setEmployementDate(\DateTimeInterface $employementDate): self
    {
        $this->employementDate = $employementDate;

        return $this;
    }
}
