function handleResponse(res) // Function for handling specified responses
{
    if (res.ok)
        {
            //document.getElementById("loginResult").innerText = "Successful Login"; // Leaving here for testing purposes
            //res.role
    
            console.log("Successful Login"); // Logging it in the terminal
    
            /* Redirect to dashboard page */
            if (res.role === "instructor")
                {
                    window.location.replace('../HTML/InstructorLanding.html'); 
                }
                else
                {
                    window.location.replace('../HTML/student_landing_page.html');
                }
    
        }
        else
        {
            //document.getElementById("loginResult").innerText = res.message; // Displaying error on webpage
            alert(res.message);
            console.log(res.message); // Logging in terminal
        }
}

export function loginPostResult(res) // Function for checking / parsing data
{
    if (res instanceof Response) // Check if the response is an instance of a Response object
    {
        res.json().then(data => { // If so, parse the JSON data
            handleResponse(data); // Send to handle response function
        }).catch(error => { // Check if there is an error parsing the JSON
            handleResponse({ ok: false, message: error.message}); // Send the error
        });
    } 
    else
    {
        handleResponse(res); // Handle if it is already a plain object
    }

}

/* Test Cases */

// Open testLogin.html in browser
// Navigate to the console


/* Successful response test case */

// Type in: loginPostResult(successResponse);
// Expected Result: Webpage displays "Successful login"
let successResponse = new Response(JSON.stringify({ok:true}),{
    status:200,
    headers:{
        'Content-type': 'application/json'
    }
});

/* Invalid Login test case */

// Type in: loginPostResult(wrongInfo);
// Expected Result: Webpage displays "Wrong Username or Password"
let wrongInfo = new Response(JSON.stringify({ok:false, message:"Wrong Username or Password"}),{
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

    if(originalFetch) // Making sure original fetch is saved
    {
        console.log("Fetch saved"); // Verification
    }

    window.fetch = mockFetch; // Override global with mock fetch

    try
    {
        send('username', 'password', loginPostResult); // Call test function
    }
    catch (error) // Check for error
    {
        console.error("Error during test: ", error); // Log it
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

function mockFetch(url, options) { // Creating mock fetch for invalid server
    return new Promise((resolve, reject) => {
        // Simulate a network error
        reject(new Error('Network response was not ok.'));
    });
}

function send(u, p, c) { // Post function for invalid server
    fetch('https://www.example.com/nonexistentpage', { // Non-existent URL for testing
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({u, p}) // Convert u & p to a JSON string
    })
    .then(response => {
        if (response.ok) { // Is response is ok, parse json data
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

//loginPostResult(successResponse);
//loginPostResult(wrongInfo);
//testFakeServer();