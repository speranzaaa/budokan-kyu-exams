document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('create-session-form');
    const atletiList = document.getElementById('atleti_list');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const selectedGrade = formData.get('grado');

        if (selectedGrade) {
            fetch(`php/filter_atleti.php?grado=${selectedGrade}`)
                .then(response => response.json())
                .then(data => {
                    atletiList.innerHTML = '';

                    if (data.length > 0) {
                        const list = document.createElement('ul');
                        data.forEach(atleta => {
                            const listItem = document.createElement('li');
                            listItem.textContent = `${atleta.nome} - ${atleta.grado} - ${atleta.presenze}`;
                            list.appendChild(listItem);
                        });
                        atletiList.appendChild(list);
                    } else {
                        atletiList.textContent = 'No athletes found for the selected grade.';
                    }

                    // After displaying athletes, submit the form via AJAX
                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    }).then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        }
                    });
                });
        } else {
            fetch(form.action, {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            });
        }
    });
});
