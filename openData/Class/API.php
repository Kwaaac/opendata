<?php

class API
{
    private static SchoolCollection $schools;

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
    public static function getJsonFromRequest($request): array
    {
        return json_decode(file_get_contents($request."&apikey=751f28826e86d194067705f98a1aad764aeca80563cfd698fce47b9e"), true);
    }


    /**
     * @param $request
     */
    public function addSchools($school)
    {
        self::$schools->addSchool();
    }

}