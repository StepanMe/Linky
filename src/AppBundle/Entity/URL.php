<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 04.12.2017
 * Time: 13:23
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="urls")
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\URLRepository")
 */
class URL
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="url_id",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="short_url",type="string",length=8,nullable=true)
     */
    private $shortUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="long_url",type="string",length=1024,nullable=false)
     */
    private $longUrl;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * @param string $shortUrl
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * @return mixed
     */
    public function getLongUrl()
    {
        return $this->longUrl;
    }

    /**
     * @param mixed $longUrl
     */
    public function setLongUrl($longUrl)
    {
        $this->longUrl = $longUrl;
    }
}