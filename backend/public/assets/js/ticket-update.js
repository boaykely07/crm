function updateStatus(ticketId, status) {
    if (confirm('Êtes-vous sûr de vouloir mettre à jour le statut de ce ticket ?')) {
        const url = `${ticketConfig.baseUrl}index.php/agent/tickets/${ticketId}/status`;
        
        console.log('URL appelée:', url);
        console.log('Statut à mettre à jour:', status);
        
        $.ajax({
            url: url,
            method: 'POST',
            data: { 
                status: status,
                [ticketConfig.csrfName]: ticketConfig.csrfToken
            },
            dataType: 'json',
            success: function(response) {
                console.log('Réponse reçue:', response);
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message || 'Une erreur est survenue lors de la mise à jour du statut.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', error);
                console.error('Status:', status);
                console.error('Réponse du serveur:', xhr.responseText);
                
                if (xhr.status === 401 || xhr.status === 403) {
                    window.location.href = `${ticketConfig.baseUrl}index.php/login`;
                } else {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message || 'Une erreur est survenue.');
                    } catch (e) {
                        alert('Une erreur est survenue lors de la mise à jour du statut. Veuillez réessayer.');
                    }
                }
            }
        });
    }
} 