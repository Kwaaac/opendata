<?php
require_once('Class/API.php');
//openData/Class/
require_once('Class/Schools/School.php');
require_once('Class/Formations/Diploma.php');

$api = new API();

function cmpDiplom(array $a, array $b)
{
    return strcmp($a['name'], $b['name']);
}

if (isset($_POST['bac']) && isset($_POST['formation']) && isset($_POST['acad'])) {

    if (isset($_POST['bac'])) {
        $refineBac = "";
        $refineForm = "";
        $refineAcad = "";

        if (strcmp($_POST['bac'], "none") != 0) {

            $refineBac = "&refine.diplome_rgp=" . $_POST['bac'];

        }
        if (strcmp($_POST['formation'], "none") != 0) {
            $refineForm = "&refine.sect_disciplinaire_lib=" . $_POST['formation'];

        }

        if (strcmp($_POST['acad'], "none") != 0) {
            $refineAcad = "&refine.localisation_ins=" . $_POST['acad'];

        }
    }

    $request = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&q=diplome&rows=100&facet=rentree_lib&facet=etablissement&facet=etablissement_lib&refine.rentree_lib=2017-18" . $refineBac . $refineForm . $refineAcad;

    $api->setSchoolsJson($request);
}
?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="UTF-8">
    <title>TP prog serveur</title>
    <link href="stylesheet.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
            integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
            crossorigin=""></script>

    <script src="jquery-3.4.1.min.js"></script>

    <script>
        var etablissement = {};

        <?php
        if (isset($_POST['bac']) && isset($_POST['formation']) && isset($_POST['acad'])) {
            //Récupération des écoles via la seconde BDD
            $resultsEtablissements = $api->getSchoolJson();
            $formCount = 0;
            if (isset($resultsEtablissements)) {

                if ($resultsEtablissements["nhits"] > 0) {

                    $uai = API::getFacet($resultsEtablissements["facet_groups"], "etablissement");
                    $etabs = $resultsEtablissements["facet_groups"][$uai]["facets"];

                    $jsonForm = [];
                    foreach ($etabs as $etablissement) {
                        $requestSchool = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&sort=uo_lib&facet=com_code&facet=uai&facet=type_d_etablissement&facet=com_nom&facet=dep_nom&facet=aca_nom&facet=reg_nom&facet=pays_etranger_acheminement&fields=uai,com_code,uo_lib,url,adresse_uai,coordonnees&refine.uai=" . $etablissement["name"];
                        $school = new School($requestSchool, $refineBac, $refineForm);
                        if ($school->isValid() == false) {
                            continue;
                        }
                        $schoolUAI = $school->getUai();

                        print("var formations = [];");
                        foreach ($school->getFormations()->getArray() as $diploms) {
                            $jsonForm[] = $diploms->toString();
                            print("var formation = [];\n");
                            foreach ($diploms->toString() as $dip) {
                                print ("formation.push(\"" . $dip . "\");\n");
                            }
                            print("formations.push(formation);\n");
                        }

                        $api->addSchools($school);


                        //Transmission des infos de chaques écoles dans une array en JS
                        print ("
var school = [];
school[\"x\"]=\"" . $school->getX() . "\";
school[\"y\"]=\"" . $school->getY() . "\";
school[\"name\"]=\"" . $school->getName() . "\";
school[\"address\"]=\"" . $school->getAddress() . "\";
school[\"url\"]=\"" . $school->getUrl() . "\";
school[\"formations\"]=formations;

etablissement[\"" . $schoolUAI . "\"]=school;
");


                    }
                }
            }
        }
        ?>
    </script>
</head>


<body>
<div class="form-popup" id="myForm_DF">
    <table class="fixed_headers" id="table_DF">
        <thead>
        <tr>
            <th>Diplome</th>
            <th>Année</th>
            <th>Libelle Formation</th>
            <th>Specialite</th>
        </tr>
        </thead>
        <tbody id="body_DF">
        </tbody>
    </table>
</div>

<div class="form-popup" id="myForm2">
    <button class="closeButton" onclick="closeForm()">On ferme ça</button>
</div>

<div class="filtres" id="f1">
    <form action="index.php" method="post">
        <div class="selectFilter">
            <label>Diplômes</label> <br>
            <select name="bac" id="bac-select">
                <option value="none"> N'importe quel niveau</option>

                <?php
                $requestNiveau = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&q=diplome&rows=0&facet=diplome_rgp&facet=rentree_lib&facet=niveau_lib&refine.rentree_lib=2017-18";
                $jsonBac = API::getJsonFromRequest($requestNiveau);

                $nbFacet_niveau = API::getFacet($jsonBac["facet_groups"], "diplome_rgp");

                //Ne peut facto le tri, dans la méthode printOption, elle ne fonctionne plus
                usort($jsonBac["facet_groups"][$nbFacet_niveau]["facets"], 'cmpDiplom');

                API::printOptions($jsonBac["facet_groups"][$nbFacet_niveau]["facets"]);
                ?>

            </select>
        </div>
        <div class="selectFilter">
            <label>Secteur disciplinaire</label>
            <br>
            <select class="box-filter" name="formation" id="formationSelect">
                <option value="none"> N'importe quel formation</option>

                <?php
                $requestFormation = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&refine.rentree_lib=2017-18&rows=0&facet=sect_disciplinaire_lib";
                $jsonFormation = API::getJsonFromRequest($requestFormation);

                $nbFacet_formation = API::getFacet($jsonFormation["facet_groups"], "sect_disciplinaire_lib");
                usort($jsonFormation["facet_groups"][$nbFacet_formation]["facets"], 'cmpDiplom');

                API::printOptions($jsonFormation["facet_groups"][$nbFacet_formation]["facets"]);
                ?>

            </select>
        </div>
        <div class="selectFilter">
            <label>Localisation</label>
            <br>
            <select name="acad" id="academie-select">
                <option value="none">Toute la france</option>
                <?php
                $reqAcademie = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=0&facet=localisation_ins&facet=rentree_lib&refine.rentree_lib=2017-18";
                $jsonAcademie = API::getJsonFromRequest($reqAcademie);


                $nbFacet_acad = API::getFacet($jsonAcademie["facet_groups"], "localisation_ins");
                usort($jsonAcademie["facet_groups"][$nbFacet_acad]["facets"], 'cmpDiplom');

                API::printOptions($jsonAcademie["facet_groups"][$nbFacet_acad]["facets"]);

                ?>
            </select>
        </div>
        <div id="research">
            <input type="submit" value="Rechercher">
        </div>
    </form>

    <div id="footer">
        <a href="https://github.com/Kwaaac/opendata" target="_blank">
            Pour en savoir plus
        </a>
    </div>

    <hr>

    <table class="fixed_headers" id="ecoles">
        <thead>
        <tr>
            <th>
                <?php
                if (isset($etabs)) {
                    $schoolsArray = $api->getSchools()->getArray();

                    if($api->getSchools()->count() == 0){
                        print("Aucun résultat trouvé");
                    }else{
                        print ("Nombres d'écoles trouvées: " . $api->getSchools()->count());
                    }

                }
                ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php

        if (isset($schoolsArray)) {
            foreach ($schoolsArray as $school) {
                print("
                    <tr>
                        <td>
                            <div class=\"school-box\">
                                <h3>" . $school->getName() . "</h3>
                                <h5>" . $school->getAddress() . "</h5>
                                <a href=\"" . $school->getUrl() . "\">" . $school->getUrl() . "</a>
            
                                <h4><label>Formations prodiguées</label></h4>
                                  
                                <button name=\"school\" id=\"" . $school->getUai() . "\" class=\"open-button\" value=\"" . $school->getUai() . "\" onclick=\"openForm('" . $school->getUai() . "')\">Diplome et Formations</button>
                                
                            </div>
                        </td>
                    </tr>
                ");
            }
        }
        ?>
        </tbody>
    </table>

</div>
<div id="mapid"></div>
<script src="script.js"></script>

</body>
</html>
