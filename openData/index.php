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

    <script>
        var etablissement = {};
    </script>
</head>
<?php
require_once('Class/API.php');

$api = new API();


function getFacet(array $a, $facet)
{
    $i = 0;
    foreach ($a as $fac) {
        if (strcmp($fac["name"], $facet) == 0) {
            return $i;
        }
        $i++;
    }
}

function cmpDiplom(array $a, array $b)
{
    return strcmp($a['name'], $b['name']);
}

if (isset($_POST['bac']) && isset($_POST['formation']) && isset($_POST['acad']) || isset($_POST['etabs'])) {

    if (isset($_POST['bac'])) {
        $refineBac = "";
        $refineForm = "";
        $refineAcad = "";


        if (strcmp($_POST['bac'], "none") != 0) {

            $refineBac = "&refine.niveau_lib=" . $_POST['bac'];

        }
        if (strcmp($_POST['formation'], "none") != 0) {
            $refineForm = "&refine.libelle_intitule_1=" . $_POST['formation'];

        }

        if (strcmp($_POST['acad'], "none") != 0) {
            $refineAcad = "&refine.aca_etab_lib=" . $_POST['acad'];

        }
    }

    if (isset($_POST['etabs'])) {
        $schoolUAI = $_POST['school'];
        $request = $_POST['etabs'];

    } else {
        $request = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&q=diplome&rows=100&facet=rentree_lib&facet=etablissement&facet=etablissement_lib&refine.rentree_lib=2017-18" . $refineBac . $refineForm . $refineAcad;
    }
    $api->setSchoolsJson($request);

}

?>
<body>
<div class="form-popup" id="myForm">
    <table class="fixed_headers" id="table_DF">
        <thead>
        <tr>
            <th>Diplome</th>
            <th>Libelle Formation</th>
            <th>Specialite</th>
        </tr>
        </thead>
        <tbody id="body_DF">
        <?php
        if (isset($_POST['school']) && isset($_POST['diplome_niveau'])) {

            $requestFormBySchool = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=50&sort=-rentree_lib&facet=rentree_lib&facet=diplome_rgp&refine.rentree_lib=2017-18&fields=diplome_rgp,libelle_intitule_1,libelle_intitule_2&refine.etablissement=" . $schoolUAI . $_POST['diplome_formation'] . $_POST['diplome_niveau'];
            $fileRequestFormBySchool = file_get_contents($requestFormBySchool);
            $jsonFormBySchool = json_decode($fileRequestFormBySchool, true);

            foreach ($jsonFormBySchool["records"] as $diplomas) {
                $specialite = "";

                if (isset($diplomas["fields"]["libelle_intitule_2"])) {
                    $specialite = $diplomas["fields"]["libelle_intitule_2"];
                }

                print ("
                <tr>
                    <td>
                        " . $diplomas["fields"]["diplome_rgp"] . "
                    </td>
                    
                    <td>
                        " . $diplomas["fields"]["libelle_intitule_1"] . "
                    </td>
                    
                    <td>
                        " . $specialite . "
                    </td>
                    
                </tr>
                ");
            }
        }
        ?>

        </tbody>
    </table>
</div>


<div class="form-popup" id="myForm2">
    <!--
    <label>Diplômes</label>
    <select name="Diplômes">
        <option value="0"> BTS</option>
        <option value="1"> DUT</option>
    </select>

    <br>
    -->
    <button class="closeButton" onclick="closeForm()">On ferme ça</button>
</div>


<div class="filtres" id="f1">
    <form action="index.php" method="post">
        <div class="selectFilter">
            <label>Niveau d'étude</label> <br>
            <select name="bac" id="bac-select">
                <option value="none"> N'importe quel niveau</option>

                <?php
                $reqBac = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&q=diplome&rows=0&facet=rentree_lib&facet=niveau_lib&refine.rentree_lib=2017-18");
                $jsonBac = json_decode($reqBac, true);

                usort($jsonBac["facet_groups"][0]["facets"], 'cmpDiplom');

                foreach ($jsonBac["facet_groups"][0]["facets"] as $bac) {
                    $value = $bac["name"];
                    $libBac = $bac["name"];
                    print ("<option value=\"" . $value . "\">" . $libBac . "</option>");
                }
                ?>

            </select>
        </div>

        <div class="selectFilter">
            <label>Grandes disiplines de formations</label>
            <br>
            <select class="box-filter" name="formation" id="formationSelect">
                <option value="none"> N'importe quel formation</option>

                <?php
                $reqFormation = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&refine.rentree_lib=2017-18&rows=0&facet=libelle_intitule_1");
                $jsonFormation = json_decode($reqFormation, true);

                usort($jsonFormation["facet_groups"][0]["facets"], 'cmpDiplom');

                foreach ($jsonFormation["facet_groups"][0]["facets"] as $formation) {
                    $value = $formation["name"];
                    $libFormation = $formation["name"];
                    print ("<option value=\"" . $value . "\">" . $libFormation . "</option>");
                }
                ?>

            </select>
        </div>
        <div class="selectFilter">
            <label>Académie</label>
            <br>
            <select name="acad" id="academie-select">
                <option value="none">Toute la france</option>
                <?php
                $reqAcademie = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=0&facet=aca_etab_lib&facet=rentree_lib&refine.rentree_lib=2017-18");
                $jsonAcademie = json_decode($reqAcademie, true);

                usort($jsonAcademie["facet_groups"][1]["facets"], 'cmpDiplom');

                foreach ($jsonAcademie["facet_groups"][1]["facets"] as $formation) {

                    $value = $formation["name"];
                    $libAcademie = $formation["name"];
                    print ("<option value=\"" . $value . "\">" . $libAcademie . "</option>");
                }

                ?>
            </select>
        </div>
        <input type="submit" value="Rechercher">
    </form>
    <hr>

    <table class="fixed_headers">
        <thead>
        <tr>
            <th>
                <?php
                $resultsEtablissements = $api->getSchoolJson();
                if (isset($resultsEtablissements)) {
                    if ($resultsEtablissements["nhits"] > 0) {
                        $uai = getFacet($resultsEtablissements["facet_groups"], "etablissement");

                        $etabs = $resultsEtablissements["facet_groups"][$uai]["facets"];
                        print("Nombres d'écoles trouvées: " . count($etabs));
                    }


                }
                ?></th>
        </tr>
        </thead>
        <tbody>
        <form action="index.php" method="post">


            <input type="hidden" name="etabs" value="<?php echo $request; ?>">
            <input type="hidden" name="diplome_niveau" value="<?php echo $refineBac; ?>"
            <input type="hidden" name="diplome_formation" value="<?php echo $refineForm; ?>"

            <?php
            if (isset($_POST['bac']) && isset($_POST['formation']) && isset($_POST['acad']) || isset($_POST['etabs'])) {
                foreach ($etabs as $etablissement) {

                    $requestSchool = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&sort=uo_lib&facet=com_code&facet=uai&facet=type_d_etablissement&facet=com_nom&facet=dep_nom&facet=aca_nom&facet=reg_nom&facet=pays_etranger_acheminement&fields=uai,com_code,uo_lib,url,adresse_uai,coordonnees&refine.uai=" . $etablissement["name"];
                    $school = new School($requestSchool);

                    /*
                        print ("


                        <script>
                            var coord = {};
                            coord[\"x\"]  =" . $x . ";
                            coord[\"y\"]  =" . $y . ";
                          
                            
                            
                            etablissement[\"" . $uai . "\"] = coord;
                        </script>
                       ");
                    */

                    print(" 
                    <tr>
                        <td>
                            <div class=\"school-box\">
                                <h3>" . $school->getName() . "</h3>
                                <h5>" . $school->getAddress() . "</h5>
                                <a href=\"" . $school->getUrl() . "\">" . $school->getUrl() . "</a>
            
                                <h4><label>Formations prodiguées</label></h4>
                                
                                <button name=\"school\" type=\"submit\" id=\"" . $uai . "\" class=\"open-button\" value=\"" . $uai . "\" >Diplome et Formations</button>
            
                            </div>
                        </td>
                    </tr>
                ");
                }
            }
            ?>
        </form>
        </tbody>
    </table>
</div>

<div id="mapid"></div>
<script src="script.js"></script>
<?php

if (isset($schoolUAI)) {
    print ("
        <script> 
           openForm(\"" . $schoolUAI . "\");
        </script>");
}

?>

</body>
</html>
