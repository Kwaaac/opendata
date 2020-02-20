<?php


class Diploma
{
    private $niveau;

    private $diplome_rgp;

    private $libIntitule_A;

    private $libIntitule_B;

    /**
     * Diplome constructor.
     * @param int $cycle
     * @param string $diplome_rgp
     * @param string $libIntitule_A
     * @param string $libIntitule_B
     */
    public function __construct($formation)
    {
        $this->niveau = [$formation["niveau_lib"] => $formation["niveau"]];
        $this->diplome_rgp = $formation["diplome_rgp"];
        $this->libIntitule_A = $formation["libelle_intitule_1"];
        if (isset($formation["libelle_intitule_2"])) {
            $this->libIntitule_B = $formation["libelle_intitule_2"];
        }

    }

    public static function getClass()
    {
        return "Diploma";
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