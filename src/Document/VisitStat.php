<?php
// src/Document/VisitStat.php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="visit_stats")
 */
class VisitStat
{
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Field(type="date")
     */
    private $date;

    /**
     * @ODM\Field(type="string")
     */
    private $page;

    /**
     * @ODM\Field(type="integer")
     */
    private $visitCount;

    /**
     * @ODM\Field(type="string")
     */
    private $userAgent;

    /**
     * @ODM\Field(type="string")
     */
    private $ipAddress;

    // Constructor for initializing default values
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->visitCount = 0;
    }

    // Getters and setters
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getVisitCount(): ?int
    {
        return $this->visitCount;
    }

    public function setVisitCount(int $visitCount): self
    {
        $this->visitCount = $visitCount;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }
}