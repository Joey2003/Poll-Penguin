import { loginPostResult } from '../Scripts/loginResult.js';
import {setEmail} from '../Scripts/userInfo.js';

//sends POST request containing login information 
function send_login_post_request(event) {
    event.preventDefault(); // Prevent form from reloading the page

    let user = document.getElementById('username').value;
    let pass = document.getElementById('password').value;
    setEmail(user);

    let payload = {
        username: user,
        password: pass
    };

    let url = '../PHP/Login_Page.php'; // php script that handles the information
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
        .then(res => loginPostResult(res)
            // if (!res.ok) {
            //     throw new Error('Network response was not ok');
            // }
            // return res.json();

        )
        .then(data => {
            // Handle the response data
            console.log(data);

        })
        .catch(e => {
            console.error('Error:', e);
        });
}
document.getElementById('loginForm').addEventListener('submit', send_login_post_request);


