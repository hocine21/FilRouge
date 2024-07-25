
    document.addEventListener("DOMContentLoaded", function() {
        console.log("DOM fully loaded and parsed");

        function logVisit() {
            const visitData = {
            date: new Intl.DateTimeFormat('fr-FR', { 
                year: 'numeric', 
                month: '2-digit', 
                day: '2-digit', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit', 
                timeZone: 'Europe/Paris' 
            }).format(new Date()),
            page: window.location.pathname
        };

            console.log("Sending visit data:", visitData);

            fetch('http://localhost:8000/api/log-visit', { // Assurez-vous que cette URL est correcte
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(visitData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        logVisit();
    });

