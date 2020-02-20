var heigth = window.innerHeight - 15;

document.getElementById("f1").style.height = heigth + "px";

document.getElementById("mapid").style.height = heigth + 15 + "px";

var mymap = L.map('mapid').setView([48.8339902, 2.4020576], 13.5);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11'
}).addTo(mymap);
console.log(etablissement);
for (var etab in etablissement) {
    var coords = etablissement[etab];
    L.marker([coords["x"], coords["y"]]).addTo(mymap);
}

function setViewOnEtablissement(x, y) {
    mymap.setView([x, y], 13.5);
}

function openForm(id) {
    let tab = document.getElementsByClassName("form-popup");
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "block";

    }
    document.getElementById("mapid").style.height = 400 + "px";
    var coords = etablissement[id];
    setViewOnEtablissement(coords["x"], coords["y"]);

}

function closeForm() {
    let tab = document.getElementsByClassName("form-popup");
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "none";
    }


    document.getElementById("mapid").style.height = heigth + 15 + "px";
} 
