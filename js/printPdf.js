document.addEventListener('DOMContentLoaded', (event) => {
    const btn = document.querySelector("#btn");

    btn.addEventListener('click', function(e) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'pt', 'letter');
        let y = 20;
        doc.setLineWidth(2);
        doc.text(200, y + 30, "Esami");
        doc.autoTable({
            html: '#table',
            startY: 70,
            theme: 'grid',
            columnStyles: {
                0: {
                    halign: 'left',
                    tableWidth: 100,
                },
                1: {
                    tableWidth: 100,
                },
                2: {
                    halign: 'left',
                    tableWidth: 100,
                },
                3: {
                    halign: 'left',
                    tableWidth: 100,
                }
            },
        });
        doc.save('esami.pdf');
    });
});