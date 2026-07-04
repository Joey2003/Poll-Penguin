import { AccountCreationResult } from './AccountCreation.js'

//sends POST request containing account creation details
document.addEventListener('DOMContentLoaded', function () {
    let signUpBtn = document.getElementById('submit');
    signUpBtn.onclick = function send_sign_up_info() {
        let user = document.getElementById('username').value;
        let user_role = document.getElementById('options').value
        let pass = document.getElementById('password').value;

        if (user == "" || pass == "") {
            alert("Please fill in all fields");
            return;
        }

        let payload = {
            username: user,
            role: user_role,
            password: pass
        };

        let url = '../PHP/CreatAccount.php'; // PHP file that handles account creation
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(res => AccountCreationResult(res))
            .then(data => {
                //AccountCreationResult(data); //needs to be imported correctly

            })
            .catch(e => {
                console.error('Error:', e);
            });
    };
});