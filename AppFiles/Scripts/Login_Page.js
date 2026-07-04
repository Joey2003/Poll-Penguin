
document.getElementById('loginForm').addEventListener('submit', function(event) {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    if (username === '' || password === '') {
        event.preventDefault();
        alert('Username and Password are required.');
    } else if (!username.endsWith('@buffalo.edu')) {
        event.preventDefault();
        alert('Username must be an @buffalo.edu email.');
    }
});
