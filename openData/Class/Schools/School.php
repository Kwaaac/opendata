<?php

require_once('Class/Formations/DiplomaCollection.php');

class School
{
    private $uai;

    private $name;

    private $address;

    private $url;

    private $x;

    private $y;

    private $formations;


    /**
     * Schools constructor.
     * @param string $request
     */
    public function __construct(string $request, string $niveau, string $formation)
    {

        $jsonSchool = API::getJsonFromRequest($request);

        $x = 0;
        $y = 0;
        $name = "Pas de nom disponible";
        $address = "Pas d'adresse disponible";
        $url = "Pas de site disponible";
        $uai = 0;
        $formations = new DiplomaCollection();


        if ($jsonSchool["nhits"] > 0) {
            $x = $jsonSchool["records"][0]["fields"]["coordonnees"][0];
            $y = $jsonSchool["records"][0]["fields"]["coordonnees"][1];
            if (isset($jsonSchool["records"][0]["fields"]["adresse_uai"])) {
                $address = $jsonSchool["records"][0]["fields"]["adresse_uai"];
            }
            $name = $jsonSchool["records"][0]["fields"]["uo_lib"];
            $url = $jsonSchool["records"][0]["fields"]["url"];
            $uai = $jsonSchool["records"][0]["fields"]["uai"];
        }

        if ($uai != 0) {
            $requestFormBySchool = API::getJsonFromRequest("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=50&sort=-rentree_lib&facet=rentree_lib&facet=diplome_rgp&refine.rentree_lib=2017-18&fields=niveau,niveau_lib,diplome_rgp,libelle_intitule_1,libelle_intitule_2&refine.etablissement=" . $uai . "&refine.libelle_intitule_1=" . $formation . "&refine.niveau_lib=" . $niveau);

            foreach ($requestFormBySchool["records"] as $formation) {
                $formations->addDiploma(new Diploma($formation["fields"]));
            }
        }

        $this->uai = $uai;
        $this->name = $name;
        $this->address = $address;
        $this->url = $url;
        $this->x = $x;
        $this->y = $y;
        $this->formations = $formations;
    }

    /**
     * @return int|mixed
     */
    public
    function getUai()
    {
        return $this->uai;
    }

    /**
     * @return mixed|string
     */
    public
    function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed|string
     */
    public
    function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed|string
     */
    public
    function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int|mixed
     */
    public
    function getX()
    {
        return $this->x;
    }

    /**
     * @return int|mixed
     */
    public
    function getY()
    {
        return $this->y;
    }

    /**
     * @return DiplomaCollection
     */
    public function getFormations()
    {
        return $this->formations;
    }

    public static function getClass()
    {
        return "School";
    }

}