// get element by id email
import { getEmail } from './userInfo.js';
import { setCourses } from './userInfo.js';

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("email").textContent = getEmail();

    // get courses from database
    let url = "../PHP/enrolledCourses.php";
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: getEmail() })
    })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            // Handle the response data
            // <div class="course-list">
            //                 <div>
            //                     <h2>CSE 115</h2>
            //                     <p>Instructor: Instructor Name</p>
            //                 </div>
            //                 <div>
            //                     <h2>CSE 116</h2>
            //                     <p>Instructor: Instructor Name</p>
            //                 </div>
            //             </div>
            console.log(data);
            let courseList = document.getElementById('course-list');
            let courses = "";
            data.forEach(course => {
                courses += course.course_id + " ";
                let courseItem = document.createElement('div');
                courseItem.id = course.course_id + " d";
                courseList.appendChild(courseItem);

                let courseListing = document.getElementById(courseItem.id);
                let courseName = document.createElement('h2');
                courseName.textContent = course.course_id;
                courseListing.appendChild(courseName);
            });
            setCourses(courses);
        })
        .catch(e => {
            console.error('Error:', e);
        });

});