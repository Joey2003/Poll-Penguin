import { getEmail } from './userInfo.js';
import { getCourseID  } from './userInfo.js';

document.getElementById('submit').addEventListener('click', function(event) {
    event.preventDefault();
    handleFormFeedback();
});

function handleFormFeedback() {
    console.log('handleFormFeedback called'); //确认函数被调用
    const feedbackForm = document.getElementById('mood');  
    const formData = new FormData(feedbackForm);  

    const feedback = formData.get('choice');  
    const email = getEmail();  
    const course_id = getCourseID();  
  
    //log email to the console
     console.log('Email:', email);

    if (!feedback) {
        alert('Please select a mood.');
        return;
    }

    const data = { feedback: feedback, email: email, course_id: course_id};  

    fetch('../PHP/studentFeedbackSubmission.php', {  
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(result => {
        if (result.ok) {
            alert('Feedback submitted successfully');
        } else {
            console.error('Error:', result.message);
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

