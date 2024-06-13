const form = document.getElementById('create-session-form');
const atletiList = document.getElementById('atleti_list');

const addNewAthlete = document.getElementById("addNewAthlete");
addNewAthlete.addEventListener('click', function(e) {
    const listItem = document.createElement('li');
    listItem.textContent = "ciao";
    atletiList.appendChild(listItem);
    console.log("ciao");
});

const s1 = document.getElementById("grado");
console.log(s1.textContent);
const select = document.getElementById("searchAthlete");

