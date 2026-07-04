import { getCourseID, getEmail } from "./userInfo.js";

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
                backgroundColor: ['#8B0000', '#FFBE2E', '#3CB371']
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
        setInterval(getData, 1000);
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
        $.post('../PHP/update_Livefeedback.php', { action: 'reset', course_id: getCourseID(), instructor: getEmail() }, function (response) {
            const data = JSON.parse(response);
            updateChart(data);
        });
    }

    function getData() {
        if (!feedbackMode) {return;}
        $.post('../PHP/update_Livefeedback.php', { action: 'get', course_id: getCourseID(), instructor: getEmail() }, function (response) {
            const data = JSON.parse(response);
	    console.log(data);
            updateChart(data);
        });
    }

    function updateAverageScoreDisplay(data) {
        const totalResponses = data.reduce((acc, val) => acc + val, 0);
        if (totalResponses === 0) return;

        const average = (data[0] * 1 + data[1] * 2 + data[2] * 3) / totalResponses;
        let backgroundColor = '#FFBE2E'; // Default to 'Just Right'
        let averageText = 'Just Right';

        if (average <= 1.5) {
            backgroundColor = 'red';
            averageText = 'Lost';
        } else if (average > 2.5) {
            backgroundColor = 'green';
            averageText = 'Easy';
        }

        $('#average-score-display')
            .text(averageText)
            .css('background-color', backgroundColor);
    }

    setInterval(runUpdate, 1000);
});
