let feedbackChart;
let feedbackMode = false;

$(document).ready(function () {
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

    $('#startFeedback').click(function () {
        feedbackMode = true;
        alert('Feedback mode started');
        resetData();
    });

    $('#stopFeedback').click(function () {
        feedbackMode = false;
        alert('Feedback mode stopped');
    });

    function updateChart(data) {
        feedbackChart.data.datasets[0].data = data;
        feedbackChart.update();
        updateAverageScoreDisplay(data);
    }

    function resetData() {
        $.post('../PHP/handle_live_feedback.php', { action: 'reset' }, function (response) {
            const data = JSON.parse(response);
            updateChart(data);
        });
    }

    function simulateLostUpdate() {
        if (feedbackMode) {
            $.post('../PHP/handle_live_feedback.php', { category: 'Lost' }, function (response) {
                const data = JSON.parse(response);
                updateChart(data);
            });
        }
    }

    function simulateJustRightUpdate() {
        if (feedbackMode) {
            $.post('../PHP/handle_live_feedback.php', { category: 'Just Right' }, function (response) {
                const data = JSON.parse(response);
                updateChart(data);
            });
        }
    }

    function simulateEasyUpdate() {
        if (feedbackMode) {
            $.post('../PHP/handle_live_feedback.php', { category: 'Easy' }, function (response) {
                const data = JSON.parse(response);
                updateChart(data);
            });
        }
    }

    function updateAverageScoreDisplay(data) {
        const totalResponses = data.reduce((acc, val) => acc + val, 0);
        if (totalResponses === 0) return;

        const average = (data[0] * 1 + data[1] * 2 + data[2] * 3) / totalResponses;
        let backgroundColor = 'yellow'; // Default to 'Just Right'
        let color = 'black'; // Text color for 'Just Right'
        let averageText = 'Just Right';

        if (average <= 1.5) {
            backgroundColor = 'red';
            color = 'white'; // Text color for 'Lost'
            averageText = 'Lost';
        } else if (average > 2.5) {
            backgroundColor = 'green';
            color = 'white'; // Text color for 'Easy'
            averageText = 'Easy';
        }

        $('#average-score-display')
            .text(averageText)
            .css('background-color', backgroundColor);
    }

    function updateAverageDisplay() {
        $.post('../PHP/handle_live_feedback.php', function (response) {
            const data = JSON.parse(response);
            updateChart(data);
        });
    }

    // Simulate a feedback update
    setInterval(simulateLostUpdate, 5000);
    setInterval(simulateJustRightUpdate, 3000);
    setInterval(simulateEasyUpdate, 1000);

    // Update average display every two minutes
    setInterval(updateAverageDisplay, 120000);

    // Initial call to update average display
    updateAverageDisplay();
});
