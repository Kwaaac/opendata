var heigth = window.innerHeight - 15;

document.getElementById("f1").style.height = heigth + "px";

document.getElementById("mapid").style.height = heigth + 15 + "px";

var mymap = L.map('mapid').setView([48.8339902, 2.4020576], 13.5);

var popup = undefined;

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11'
}).addTo(mymap);
console.log(etablissement);
for (var etab in etablissement) {
    var ecole = etablissement[etab];
    var marker = L.marker([ecole["x"], ecole["y"]]).addTo(mymap);
    marker.bindPopup("<h1>" + ecole["name"] + "</h1><br >" + "<a href='" + ecole["url"]
        + "'>" + ecole["url"] + "</a><br >" + ecole["address"]);
}

function setViewOnEtablissement(x, y) {
    mymap.setView([x, y], 13.5);
}

function openForm(id) {
    clearTab();


    let tab = document.getElementsByClassName("form-popup");
    console.log(tab.length);
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "block";
    }

    document.getElementById("myForm_DF").style.visibility = "visible";

    if (document.getElementById("mapid").clientHeight >= heigth) {
        let heigth_bis = document.getElementById("myForm_DF").offsetHeight;

        document.getElementById("mapid").style.height = (document.getElementById("mapid").clientHeight - heigth_bis - 15) + "px";
    }
    var ecole = etablissement[id];

    popup = L.popup()
        .setLatLng([ecole["x"], ecole["y"]])
        .setContent(("<h1>" + ecole["name"] + "</h1><br >" + "<a href='" + ecole["url"]
            + "'>" + ecole["url"] + "</a><br >" + ecole["address"]))
        .openOn(mymap);


    let txt = "";

    for (let i = 0; i < ecole["formations"].length; i++) {
        txt += "<tr>";
        for (let j = 0; j < ecole["formations"][i].length; j++) {
            txt += "<td>" + ecole["formations"][i][j] + "</td>";
        }
        txt += "</tr>";
    }


    $("#body_DF").append(txt);

    setViewOnEtablissement(ecole["x"], ecole["y"]);

}

var tabHtml = "<tr>";

tabHtml += "<td>" + "" + "</td>";

tabHtml += "</tr>";

function clearTab() {
    $("#body_DF").empty();
    mymap.closePopup();
}

function closeForm() {
    let tab = document.getElementsByClassName("form-popup");
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "none";
    }

    clearTab();

    document.getElementById("mapid").style.height = heigth + 15 + "px";
} 
