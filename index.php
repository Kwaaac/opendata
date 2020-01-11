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
</head>
<?php

function cmpDiplom(array $a, array $b)
{
    return strcmp($a['name'], $b['name']);
}

?>
<body>
<div class="form-popup" id="myForm">
    <div>
        <form></form>

    </div>
    <table class="fixed_headers" id="table_DF">
        <thead>
        <tr>
            <th>Name</th>
            <th>Color</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody id="body_DF">
        <tr>
            <td>Apple</td>
            <td>Red</td>
            <td>These are red.</td>
        </tr>
        <tr>
            <td>Pear</td>
            <td>Green</td>
            <td>These are green.</td>
        </tr>
        <tr>
            <td>Grape</td>
            <td>Purple / Green</td>
            <td>These are purple and green.</td>
        </tr>
        <tr>
            <td>Orange</td>
            <td>Orange</td>
            <td>These are orange.</td>
        </tr>
        <tr>
            <td>Banana</td>
            <td>Yellow</td>
            <td>These are yellow.</td>
        </tr>
        <tr>
            <td>Kiwi</td>
            <td>Green</td>
            <td>These are green.</td>
        </tr>
        <tr>
            <td>Plum</td>
            <td>Purple</td>
            <td>These are Purple</td>
        </tr>
        <tr>
            <td>Watermelon</td>
            <td>Red</td>
            <td>These are red.</td>
        </tr>
        <tr>
            <td>Tomato</td>
            <td>Red</td>
            <td>These are red.</td>
        </tr>
        <tr>
            <td>Cherry</td>
            <td>Red</td>
            <td>These are red.</td>
        </tr>
        <tr>
            <td>Cantelope</td>
            <td>Orange</td>
            <td>These are orange inside.</td>
        </tr>

        </tbody>
    </table>
</div>

<div class="form-popup" id="myForm2">
    <label>Diplômes</label>
    <select name="Diplômes">
        <option value="0"> BTS</option>
        <option value="1"> DUT</option>
    </select>

    <br>

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
            <th>Écoles</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_POST['bac']) && isset($_POST['formation']) && isset($_POST['acad'])) {
            $refineBac = "";
            $refineForm = "";
            $refineAcad = "";

            if($_POST['bac'] != "none"){
                $refineBac = "&refine.niveau_lib=".$_POST['bac'];
            }
            if($_POST['formation']){
                $refineForm = "&refine.libelle_intitule_1=".$_POST['formation'];
            }

            if($_POST['acad']){
                $refineAcad = "&refine.aca_etab_lib=".$_POST['acad'];
            }

            $request = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&q=diplome&rows=100&facet=rentree_lib&facet=etablissement&facet=etablissement_lib&refine.rentree_lib=2017-18".$refineBac.$refineForm.$refineAcad;
            $etablissementFormation = file_get_contents($request);
            $resultsEtablissements = json_decode($etablissementFormation, true);


            foreach ($resultsEtablissements["facet_groups"][2]["facets"] as $etablissement) {

                // marquer les etablissments
                print(" 
                <tr>
                    <td>
                        <div class=\"school-box\">
                            <h3>" . $etablissement["name"] . "</h3>
                            <h5>adresse</h5>
        
                            <h4><label>Formations prodiguées</label></h4>
                       
                            <button class=\"open-button\" onclick=\"openForm(0, 0)\">Diplome et Formations</button>
        
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
