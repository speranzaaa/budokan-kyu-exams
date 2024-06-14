const form = document.getElementById('create-session-form');
const atletiList = document.getElementById('atleti_list');

const addNewAthlete = document.getElementById("addNewAthlete");
addNewAthlete.addEventListener('click', function(e) {
    const listItem = document.createElement('li');
    listItem.textContent = "ciao";
    atletiList.appendChild(listItem);

    const select = document.getElementById("searchAthlete");
    const selectedOption = select.options[select.selectedIndex];
    const selectedOptionId = selectedOption.value;

    const formData = new FormData();
    formData.append("id", selectedOptionId);
    axios.post('api/api-add-athlete.php', formData)
        .then(response => {
        console.log(response.data);
        if(!response.data["error"]){
            console.log("ok");
        }
    })

});


