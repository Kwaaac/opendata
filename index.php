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
                <option value="bac"> Entre Bac +1 et Bac +7 et plus</option>
                <option value="bac1">Bac +1</option>
                <option value="bac2">Bac +2</option>
                <option value="bac3">Bac +3</option>
                <option value="bac4">Bac +4</option>
                <option value="bac5">Bac +5</option>
                <option value="bac6">Bac +6</option>
                <option value="bac7">Bac +7 et plus</option>
            </select>
        </div>

        <div class="selectFilter">
            <label>Grandes disiplines de formations</label>
            <br>
            <select class="box-filter" name="formation" id="formationSelect">
                <option value="-1"> N'importe quel formation</option>
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
            <select name="acad" id="bac-select">
                <option value="-1">Toute la france</option>
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

        $etablissementFormation = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&sort=-rentree_lib&facet=rentree_lib&facet=etablissement_type2&facet=etablissement_type_lib&facet=etablissement&facet=etablissement_lib&facet=champ_statistique&facet=dn_de_lib&facet=cursus_lmd_lib&facet=diplome_rgp&facet=diplome_lib&facet=typ_diplome_lib&facet=diplom&facet=niveau_lib&facet=disciplines_selection&facet=gd_disciscipline_lib&facet=discipline_lib&facet=sect_disciplinaire_lib&facet=spec_dut_lib&facet=localisation_ins&facet=com_etab&facet=com_etab_lib&facet=uucr_etab&facet=uucr_etab_lib&facet=dep_etab&facet=dep_etab_lib&facet=aca_etab&facet=aca_etab_lib&facet=reg_etab&facet=reg_etab_lib&facet=com_ins&facet=com_ins_lib&facet=uucr_ins&facet=dep_ins&facet=dep_ins_lib&facet=aca_ins&facet=aca_ins_lib&facet=reg_ins&facet=reg_ins_lib&refine.etablissement_type2=Universit%C3%A9&refine.sect_disciplinaire_lib=Math%C3%A9matiques+appliqu%C3%A9es+et+sciences+sociales&fields=etablissement,etablissement_lib");
        $resultsEtablissements = json_decode($etablissementFormation, true);


        foreach ($resultsEtablissements["records"] as $etablissement) {

            // marquer les etablissments
            $requestGeo = $etablissement["fields"]["etablissement"];

            $etablissementGeolocalisation = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&sort=uo_lib&rows=1&facet=uai&fields=uai,coordonnees&refine.uai=" . $requestGeo);


            $resultsEtablissementsGeo = json_decode($etablissementGeolocalisation, true);

            $coords = [0, 0];

            foreach ($resultsEtablissementsGeo["records"] as $etab) {
                $coords = $etab["fields"]["coordonnees"];
            }
            print(" <tr>
            <td>
                <div class=\"school-box\">
                    <h3>" . $etablissement["fields"]["etablissement_lib"] . "</h3>
                    <h5>adresse</h5>

                    <h4><label>Formations prodiguées</label></h4>
                    <script>
                   let x =" . $coords[0] . ";
                   let y =" . $coords[1] . ";
                   </script>
                
                    <button class=\"open-button\" onclick=\"openForm(x, y)\">Diplome et Formations</button>

                </div>
            </td>
        </tr>");
        }
        ?>
        </tbody>
    </table>
</div>

<div id="mapid"></div>
<script src="script.js"></script>

</body>
</html>
