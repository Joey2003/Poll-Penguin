function handleOTPResult(res) // Function to handle specified response
{
    if (res.ok)
    {
        //document.getElementById("otpResult").innerText = "Account created successfully"; // Notify the user that the account was created

        // Further redirection account confirmation page
        window.location.replace("../HTML/AccountConfirmation.html");
    }
    else
    {
        //document.getElementById("otpResult").innerText = res.message; // Invalid OTP
        alert(res.message); // Popup message
    }

}

export function otpPostResult(res) // Function for checking the data
{
    if (res instanceof Response) // Check if the response is an instance of a Response object
    {
        res.json().then(data => { // If so, parse the JSON data
            handleOTPResult(data); // Send to handle response function
        }).catch(error => { // Check if there is an error parsing the JSON
            handleOTPResult({ ok: false, message: error.message }); // Send the error
        });
    } 
    else
    {
        handleOTPResult(res); // Handle if it is already a plain object
    }
}


/* Test Cases */

// Open testOTP.html in browser
// Navigate to the console


/* Successful response test case */

// Type in: otpPostResult(successResponse);
// Expected Result: Webpage displays "Account created successfully"
let successResponse = new Response(JSON.stringify({ok:true}),{
    status:200,
    headers:{
        'Content-type': 'application/json'
    }
});

/* Invalid Login test case */

// Type in: otpPostResult(wrongInfo);
// Expected Result: Webpage displays "Wrong One-time-password"
let wrongInfo = new Response(JSON.stringify({ok:false, message:"Wrong One-time-password"}),{
    status:401,
    headers:{
        'Content-type': 'application/json'
    }
});

/* Server down test case */

// Type in: testFakeServer();
// Expected Result: Webpage displays "Server not responding"
function testFakeServer()
{
    const originalFetch = window.fetch; // Save original fetch

    if(originalFetch)
    {
        console.log("Fetch saved");
    }

    window.fetch = mockFetch; // Override global with mock fetch

    try
    {
        send('username', 'password', 'otp', otpPostResult); // Call test function
    }
    catch (error)
    {
        console.error("Error during test: ", error);
    }
    finally
    {
        window.fetch = originalFetch; // Restore original fetch

        if(window.fetch == originalFetch) // Making sure fetch changed
        {
            console.log("Fetch restored"); // Verification that nothing *fucked up*
        }
        else
        {
            console.log("Fetch restoration did indeed, fuck up"); /* NOT GOOD */
        }
    }
}

function mockFetch(url, options) {
    return new Promise((resolve, reject) => {
        reject(new Error('Network response was not ok.')); // Simulate a network error
    });
}

function send(u, p, otp, c) { // POST request for testing 
    fetch('https://www.example.com/nonexistentpage', { // Non-existent URL for testing
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({u, p, otp}) // Convert u, p, otp to a JSON string
    })
    .then(response => {
        if (response.ok) { // If response is ok, parse json data
            return response.json();
        } else {
            throw new Error('Network response was not ok.'); // If not, thrown an error
        }
    })
    .then(data => c(data)) // Call the callback function with parsed data
    .catch(error => {
        console.error("Error: ", error); // Log error in the console
        c({ok: false, message: "Server not responding."}); // Call the callback function with an error message
    });
}