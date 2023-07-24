<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    use UuidEntity;
    use DateTimeEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'name', length: 255)]
    private string $name;

    #[ORM\Column(name: 'address', length: 255)]
    private string $address;

    #[ORM\Column(name: 'country', length: 256)]
    private string $country;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Employee::class, cascade: ['persist', 'remove'])]
    private Collection $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->createUuid();
        $this->setDateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    /**
     * @param Collection<Employee> $employees
     *
     * @return $this
     */
    public function setEmployees(Collection $employees): self
    {
        $this->employees = $employees;

        return $this;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setCompany($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getCompany() === $this) {
                $employee->setCompany(null);
            }
        }

        return $this;
    }
}
