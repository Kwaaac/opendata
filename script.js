var heigth = window.innerHeight - 15;

document.getElementById("f1").style.height = heigth + "px";

document.getElementById("mapid").style.height = heigth + 15 + "px";

var mymap = L.map('mapid').setView([51.505, -0.09], 13.5);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11'
}).addTo(mymap);

var marker = L.marker([51.5, -0.09]).addTo(mymap);



function clicky(x, y) {
    mymap.setView([x , y], 13.5);
}

mymap.on('click', clicky);


L.marker([48.8582499, 2.3184738]).addTo(map)
    .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
    .openPopup();

function openForm() {
    let tab = document.getElementsByClassName("form-popup");
    for(let i = 0; i < tab.length; i++){
        tab[i].style.display = "block";
    }
    document.getElementById("mapid").style.height = 400 + "px";
}

function closeForm() {
    let tab = document.getElementsByClassName("form-popup");
    for(let i = 0; i < tab.length; i++){
        tab[i].style.display = "none";
    }


    document.getElementById("mapid").style.height = heigth + 15 + "px";
} 
