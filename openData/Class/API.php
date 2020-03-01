<?php


require_once('Schools/SchoolCollection.php');

class API
{
    private static $schools;

    private static $jsonSchools;

    /**
     * API constructor.
     */
    public function __construct()
    {

        self::$schools = new SchoolCollection();
    }

    /**
     * @param $request
     * @return array
     */
    public static function getJsonFromRequest($request)
    {
        return json_decode(file_get_contents($request . "&apikey=751f28826e86d194067705f98a1aad764aeca80563cfd698fce47b9e"), true);;
    }

    private function cmpDiplom(array $a, array $b)
    {
        return strcmp($a['name'], $b['name']);
    }

    public static function setSchoolsJson($request)
    {
        self::$jsonSchools = json_decode(file_get_contents($request . "&apikey=751f28826e86d194067705f98a1aad764aeca80563cfd698fce47b9e"), true);
    }


    public static function getSchoolJson()
    {
        return self::$jsonSchools;
    }

    public function addSchools($school)
    {
        self::$schools->addSchool($school);
    }

    public function getSchools()
    {
        return self::$schools;
    }

    public static function getFacet(array $a, $facet)
    {
        $i = 0;
        foreach ($a as $fac) {
            if (strcmp($fac["name"], $facet) == 0) {
                return $i;
            }
            $i++;
        }
    }

    public static function printOptions($array){


        foreach ($array as $elt) {
            $value = $elt["name"];
            $lib = $elt["name"];
            print ("<option value=\"" . $value . "\">" . $lib . "</option>");
        }
    }

}