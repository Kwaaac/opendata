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
        $libB = "Pas de Discipline";
        $this->niveau = $formation["niveau_lib"];
        $this->diplome_rgp = $formation["diplome_rgp"];
        $this->libIntitule_A = $formation["libelle_intitule_1"];
        if (isset($formation["discipline_lib"])) {
            $libB = $formation["discipline_lib"];
        }

        $this->libIntitule_B = $libB;

    }

    public function toString()
    {

        return $array = [
            "diplome_rgp" => $this->diplome_rgp,
            "niveau" => $this->niveau,
            "lib1"=>$this->libIntitule_A,
            "lib2"=>$this->libIntitule_B,
        ];
    }

    public static function getClass()
    {
        return "Diploma";
    }

    /**
     * @return int
     */
    public function getCycle()
    {
        return $this->cycle;
    }

    /**
     * @return string
     */
    public function getDiplomeRgp()
    {
        return $this->diplome_rgp;
    }

    /**
     * @return string
     */
    public function getLibIntituleA()
    {
        return $this->libIntitule_A;
    }

    /**
     * @return string
     */
    public function getLibIntituleB()
    {
        return $this->libIntitule_B;
    }


}