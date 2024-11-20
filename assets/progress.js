let totalOrders = 1000; // Replace dynamically if needed

function processBatch(offset) {
    const batchSize = 100;
    fetch(`?module=highlevelsync&action=sync_previous_orders_batch&offset=${offset}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const progress = Math.min(((offset + batchSize) / totalOrders) * 100, 100);
                updateProgress(progress);

                if (progress < 100) {
                    processBatch(offset + batchSize);
                } else {
                    alert('All orders have been synced successfully!');
                }
            } else {
                alert('An error occurred: ' + data.message);
            }
        });
}

function updateProgress(percent) {
    const progressBar = document.getElementById('progress');
    progressBar.style.width = percent + '%';
    progressBar.textContent = Math.round(percent) + '%';
}
