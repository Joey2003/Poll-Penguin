// make a global variable of the user's information
export function setEmail(user) {
    sessionStorage.setItem('email', user);
}

export function getEmail() {
    return sessionStorage.getItem('email');
}

export function clearEmail() {
    sessionStorage.removeItem('email');
}

export function setCourseID(courseID) {
    sessionStorage.setItem('courseID', courseID);
}

export function getCourseID() {
    return sessionStorage.getItem('courseID');
}

export function clearCourseID() {
    sessionStorage.removeItem('courseID');
}

export function setCourses(courses) {
    sessionStorage.setItem('courses', courses);
}

export function getCourses() {
    return sessionStorage.getItem('courses');
}
