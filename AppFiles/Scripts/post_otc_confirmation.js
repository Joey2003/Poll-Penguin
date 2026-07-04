import { otpPostResult } from './otpResult.js';



//Sends POST requests 
document.addEventListener('DOMContentLoaded', function () {
    let otc_btn = document.getElementById('submit');
    otc_btn.onclick = function send_confirmation_code() {
        let otc = document.getElementById('otp').value
        if (otc == "") {
            alert("Please enter the OTP code");
            return;
        }
        //let display = document.getElementById('display');
        //let user = document.getElementById('username');

        let payload = { otp: otc }
        let url = '../PHP/handle_otc_confrimation.php' //php file that handles otc confirmation

        fetch(url, {
            'method': 'POST',
            'headers': {
                'Content-Type': 'application/json;'
            },
            body: JSON.stringify(payload)
        })
            //otpPostResult(res.json())
            // send response from server to result handling function
            .then(res => otpPostResult(res))
            .then(data => {
                //otpPostResult(data); // has to be export function

            })
            .catch(e => {

                console.error('Error:', e)
            });
    }
})
