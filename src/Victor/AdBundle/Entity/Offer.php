<?php

namespace Victor\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 *
 * @ORM\Table(name="offer")
 * @ORM\Entity(repositoryClass="Victor\AdBundle\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Victor\AdBundle\Entity\Phone")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="Victor\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Victor\UserBundle\Entity\User")
     */
    private $buyer;

    /**
     * @var
     *
     * @ORM\Column(name="sold", type="boolean")
     */
    private $sold;

    /**
     * @var int
     *
     * @ORM\Column(name="step", type="integer")
     */
    private $step;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="saledate", type="datetime")
     */
    private $saledate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="receivedate", type="datetime")
     */
    private $receivedate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="conformdate", type="datetime")
     */
    private $conformdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendate", type="datetime")
     */
    private $sendate;

    /**
     * @var
     *
     * @ORM\Column(name="topay", type="boolean")
     */
    private $topay;

    /**
     * @var
     *
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid;

    /**
    * @var
    *
    * @ORM\Column(name="payrequest", type="boolean")
    */
    private $payrequest;


    public function __construct()
    {
        $this->date = new \Datetime();
        $this->sold = 0;
        $this->step = 0;
        $this->saledate = new \DateTime();
        $this->receivedate = new \DateTime();
        $this->conformdate = new \DateTime();
        $this->sendate = new \DateTime();
        $this->topay = 0;
        $this->payrequest = 0;
        $this->paid = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Offer
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set phone
     *
     * @param \Victor\AdBundle\Entity\Phone $phone
     *
     * @return Offer
     */
    public function setPhone(\Victor\AdBundle\Entity\Phone $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return \Victor\AdBundle\Entity\Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Set user
     *
     * @param \Victor\UserBundle\Entity\User $user
     *
     * @return Offer
     */
    public function setUser(\Victor\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Victor\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set status
     *
     * @param string $status
     *
     * @return Offer
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set buyer
     *
     * @param \Victor\UserBundle\Entity\User $buyer
     *
     * @return Offer
     */
    public function setBuyer(\Victor\UserBundle\Entity\User $buyer = null)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return \Victor\UserBundle\Entity\User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set sold
     *
     * @param boolean $sold
     *
     * @return Offer
     */
    public function setSold($sold)
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * Get sold
     *
     * @return boolean
     */
    public function getSold()
    {
        return $this->sold;
    }

    /**
     * Set step
     *
     * @param integer $step
     *
     * @return Offer
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return integer
     */
    public function getStep()
    {
        return $this->step;
    }


    /**
     * Set saledate
     *
     * @param \DateTime $saledate
     *
     * @return Offer
     */
    public function setSaledate($saledate)
    {
        $this->saledate = $saledate;

        return $this;
    }

    /**
     * Get saledate
     *
     * @return \DateTime
     */
    public function getSaledate()
    {
        return $this->saledate;
    }

    /**
     * Set receivedate
     *
     * @param \DateTime $receivedate
     *
     * @return Offer
     */
    public function setReceivedate($receivedate)
    {
        $this->receivedate = $receivedate;

        return $this;
    }

    /**
     * Get receivedate
     *
     * @return \DateTime
     */
    public function getReceivedate()
    {
        return $this->receivedate;
    }

    /**
     * Set conformdate
     *
     * @param \DateTime $conformdate
     *
     * @return Offer
     */
    public function setConformdate($conformdate)
    {
        $this->conformdate = $conformdate;

        return $this;
    }

    /**
     * Get conformdate
     *
     * @return \DateTime
     */
    public function getConformdate()
    {
        return $this->conformdate;
    }

    /**
     * Set sendate
     *
     * @param \DateTime $sendate
     *
     * @return Offer
     */
    public function setSendate($sendate)
    {
        $this->sendate = $sendate;

        return $this;
    }

    /**
     * Get sendate
     *
     * @return \DateTime
     */
    public function getSendate()
    {
        return $this->sendate;
    }

    /**
     * Set topay
     *
     * @param boolean $topay
     *
     * @return Offer
     */
    public function setTopay($topay)
    {
        $this->topay = $topay;

        return $this;
    }

    /**
     * Get topay
     *
     * @return boolean
     */
    public function getTopay()
    {
        return $this->topay;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return Offer
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set payrequest
     *
     * @param boolean $payrequest
     *
     * @return Offer
     */
    public function setPayrequest($payrequest)
    {
        $this->payrequest = $payrequest;

        return $this;
    }

    /**
     * Get payrequest
     *
     * @return boolean
     */
    public function getPayrequest()
    {
        return $this->payrequest;
    }
}
