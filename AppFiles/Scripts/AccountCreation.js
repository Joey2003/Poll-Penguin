export async function AccountCreationResult(response) {
    /* let message = document.getElementById('message'); */

    if (response.ok) {
        let result = await response.json();

        if (typeof result.success !== 'undefined') {
            if (result.success) {
                // let otp = result.message;
                /*  message.textContent = 'Account created successfully!';
                message.style.color = 'green'; */
                window.location.replace("../HTML/OTP.html");
            } else {
                /*  message.textContent = 'Error: ' + (result.message || 'Unknown error');
                message.style.color = 'red'; */
                alert(result.message);
            }
        } else {
            /*  message.textContent = 'Account created successfully!';
            message.style.color = 'green'; */
            alert(result.message);
            window.location.replace("../HTML/OTP.html");
        }
    }

}

/*    // Mocking responses for testing
const successResponse = new Response(JSON.stringify({ success: true, message: "Account created successfully" }), {
status: 200,
headers: { 'Content-Type': 'application/json' }
});

const errorResponse = new Response(JSON.stringify({ success: false, message: "Email already exists" }), {
status: 400,
headers: { 'Content-Type': 'application/json' }
});

const genericErrorResponse = new Response(JSON.stringify({ message: "An error occurred" }), {
status: 500,
headers: { 'Content-Type': 'application/json' }
});

// Test the function with different mock responses
RequestResult(successResponse);
handlePostRequestResult(errorResponse);
handlePostRequestResult(genericErrorResponse); */