<?php
/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[ORM\UniqueConstraint(name: 'uq_categories_name', columns: ['name'])]
#[UniqueEntity(fields: ['name'])]
class Category
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 45)]
    private ?string $name = null;

    /**
     * Slug.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

//    /**
//     * Created at.
//     *
//     * @var DateTime|null
//     */
//    #[ORM\Column(type: 'datetime')]
//    #[Assert\Type(DateTime::class)]
//    #[Gedmo\Timestampable(on: 'create')]
//    private ?DateTime $createdAt = null;
//
//    /**
//     * Updated at.
//     *
//     * @var DateTime|null
//     */
//    #[ORM\Column(type: 'datetime')]
//    #[Assert\Type(DateTime::class)]
//    #[Gedmo\Timestampable(on: 'update')]
//    private ?DateTime $updatedAt = null;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for slug.
     *
     * @return string|null Slug
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Setter for slug.
     *
     * @param string $slug Slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Getter for createdAt.
     *
     * @return DateTime|null CreatedAt
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * Setter for createdAt.
     *
     * @param DateTime|null $createdAt CreatedAt
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for updatedAt.
     *
     * @return DateTime|null UpdatedAt
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updatedAt.
     *
     * @param DateTime|null $updatedAt UpdatedAt
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}