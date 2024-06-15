const form = document.getElementById('create-session-form');
const atletiList = document.getElementById('atleti_list');

const addNewAthlete = document.getElementById("addNewAthlete");
addNewAthlete.addEventListener('click', function(e) {


    const select = document.getElementById("searchAthlete");
    const selectedOption = select.options[select.selectedIndex];
    const selectedOptionId = selectedOption.value;

    const formData = new FormData();
    formData.append("id", selectedOptionId);
    axios.post('api/api-add-athlete.php', formData)
        .then(response => {
        console.log(response.data);
        if(!response.data["error"]){
            console.log(response.data["athlete"])
            const listItem = document.createElement('li');
            const athlete = response.data["athlete"];
            listItem.textContent = athlete["Nome"] + " " + athlete["Cognome"];
            atletiList.appendChild(listItem);

            const form = document.getElementById("update-scores-form");
            const hiddenInput = document.createElement('input');
            hiddenInput.value = athlete["Id"];
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "atleti_ids[]");
            form.appendChild(hiddenInput);
        }
    })

});


