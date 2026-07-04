import { setCourseID } from "./userInfo.js";
import { getCourses } from "./userInfo.js";

$(document).ready(function () {
    
    let course_list = document.getElementById('Courses');
    let courses = getCourses();
    let courseArray = courses.split(" ");
    courseArray.forEach(course => {
        let courseItem = document.createElement('option');
        courseItem.value = course;
        courseItem.textContent = course;
        course_list.appendChild(courseItem);
    });
});

document.getElementById("Courses").addEventListener("change", function () {
    var selectedCourse = this.value;
    setCourseID(selectedCourse);
    console.log("Selected Course: " + selectedCourse);
    // Add your desired logic here
});
