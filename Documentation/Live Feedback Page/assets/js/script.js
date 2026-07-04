let feedbackChart;
let feedbackMode = false;

$(document).ready(function() {
    const ctx = document.getElementById('feedbackChart').getContext('2d');
    feedbackChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lost', 'Just Right', 'Easy'],
            datasets: [{
                label: 'Display',
                data: [0, 0, 0],
                backgroundColor: ['red', 'yellow', 'green']
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    $('#startFeedback').click(function() {
        feedbackMode = true;
        alert('Feedback mode started');
        resetData();
    });

    $('#stopFeedback').click(function() {
        feedbackMode = false;
        alert('Feedback mode stopped');
    });

    function updateChart(data) {
        feedbackChart.data.datasets[0].data = data;
        feedbackChart.update();
    }

    function resetData() {
        $.post('update_feedback.php', { action: 'reset' }, function(response) {
            const data = JSON.parse(response);
            updateChart(data);
        });
    }
    function simulateLostUpdate() {
        if (feedbackMode) {
            $.post('update_feedback.php', { category: 'Lost' }, function(response) {
                const data = JSON.parse(response);
                updateChart(data);
            });
        }
    }

    function simulateJustRightUpdate() {
        if (feedbackMode) {
            $.post('update_feedback.php', { category: 'Just Right' }, function(response) {
                const data = JSON.parse(response);
                updateChart(data);
            });
        }
    }

    function simulateEasyUpdate() {
        if (feedbackMode) {
            $.post('update_feedback.php', { category: 'Easy' }, function(response) {
                const data = JSON.parse(response);
                updateChart(data);
            });
        }
    }

    // Simulate a feedback update
    setInterval(simulateLostUpdate, 5000);
    setInterval(simulateJustRightUpdate, 3000);
    setInterval(simulateEasyUpdate, 1000);
});
