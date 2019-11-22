<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TP prog serveur</title>
    <link href="stylesheet.css" rel="stylesheet">
</head>
<body>
<div class="filtres" id="f1">
    <div id="topBar">
        <label>
            <input type="button" value="☰">
            <input id="general search" type="text" placeholder="Search..">
        </label>
    </div>

    <div id="filters">
        <label>Niveau d'étude</label>
        <select name="bad" id="bac-select">
            <option value="0"> N'importe</option>
            <option value="1">Bac +1</option>
            <option value="2">Bac +2</option>
            <option value="3">Bac +3</option>
            <option value="4">Bac +4</option>
            <option value="5">Bac +5</option>
            <option value="6">Bac +6</option>
            <option value="7+">Bac +7 et plus</option>
        </select>
    </div>

</div>


<script src="script.js"></script>
</body>
</html>