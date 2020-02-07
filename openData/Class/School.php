<?php


use http\Url;

class School
{
    private int $uai;

    private string $name;

    private string $address;

    private string $url;

    private int $x;

    private int $y;

    /**
     * Schools constructor.
     * @param string $request
     */
    public function __construct(string $request)
    {

        $jsonSchool = API::getJsonFromRequest($request);

        $x = 0;
        $y = 0;
        $name = "Pas de nom disponible";
        $address = "Pas d'adresse disponible";
        $url = "Pas de site disponible";
        $uai = 0;

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

        $this->uai = $uai;
        $this->name = $name;
        $this->address = $address;
        $this->url = $url;
        $this->x = $x;
        $this->y = $y;
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

    public static function getClass()
    {
        return "School";
    }

}