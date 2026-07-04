function handleResponse(response) {
    try {
        if (response.ok) {
            // add the students to the list
            let students = response.message;
            document.getElementById('newStudents').textContent = students.length;
            const prefix = document.getElementById('pre_fix').value;
            const number = document.getElementById('c_num').value;
            const courseId = prefix + number;
            for (let student of students) {
                const studentEntry = document.createElement('div');
                studentEntry.className = 'student-entry';

                const studentInfo = document.createElement('div');
                studentInfo.className = 'student-info';
                studentInfo.innerHTML = `<h3>${student.student_first_name} ${student.student_last_name}</h3><p>${student.student_email}</p>`;
                studentEntry.appendChild(studentInfo);

                const removeButton = document.createElement('button');
                removeButton.className = 'remove-button';
                removeButton.textContent = 'Remove';
                removeButton.onclick = function() {
                    // call wes' function to remove student from course
                    removeStudent(studentEntry, courseId, student.student_email);
                };
                studentEntry.appendChild(removeButton);

                // Add the new student entry to the list
                document.getElementById('studentList').appendChild(studentEntry);
                
            }
        } else {
            alert(result.message);
            console.log(result.message);
        }
    } catch (error) {
        alert(error.message);
    }
}

export function InviteResult(res) // Function for checking / parsing data
{
    console.log(res)
    if (res instanceof Response) // Check if the response is an instance of a Response object
    {
        res.json().then(data => { // If so, parse the JSON data
            handleResponse(data); // Send to handle response function
        }).catch(error => { // Check if there is an error parsing the JSON
            handleResponse({ ok: false, message: error.message }); // Send the error
        });
    }
    else {
        handleResponse(res); // Handle if it is already a plain object
    }
}

function removeStudent(entry, courseId, email) {
    fetch('../PHP/removeStudent.php', {   //?? fetch ??????????,?? URL ? b.php
        method: 'POST', //method: 'POST' ??????? POST,????????????????
        headers: { // //headers: { 'Content-Type': 'application/json' } ?????,?????????? JSON?
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({email: email, courseId: courseId})  //body: JSON.stringify(payload) ? payload ????? JSON ???,???????????
    })
    .then(response => {
        if (response instanceof Response) {
            response.json().then(data => {
                if (data.ok) {
                    entry.remove();
                }
                alert(data.message);
            })
        }
    })
    .catch(error => {
        console.error('Error:', error);  //.catch(error => { console.error('Error:', error); })
                                        // ???????????????????,????????????
    });
}

