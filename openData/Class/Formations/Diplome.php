<?php


class Diplome
{
    private int $cycle;

    private string $diplome_rgp;

    private string $libIntitule_A;

    private string $libIntitule_B;

    /**
     * Diplome constructor.
     * @param int $cycle
     * @param string $diplome_rgp
     * @param string $libIntitule_A
     * @param string $libIntitule_B
     */
    public function __construct($request)
    {
        $jsonForm = API::getJsonFromRequest($request);

//        diplom = $jsonForm["records"][0]["fields"];
//        $this->cycle = ;
//        $this->diplome_rgp = $diplome_rgp;
//        $this->libIntitule_A = $libIntitule_A;
//        $this->libIntitule_B = $libIntitule_B;
    }

    /**
     * @return int
     */
    public function getCycle(): int
    {
        return $this->cycle;
    }

    /**
     * @return string
     */
    public function getDiplomeRgp(): string
    {
        return $this->diplome_rgp;
    }

    /**
     * @return string
     */
    public function getLibIntituleA(): string
    {
        return $this->libIntitule_A;
    }

    /**
     * @return string
     */
    public function getLibIntituleB(): string
    {
        return $this->libIntitule_B;
    }



}