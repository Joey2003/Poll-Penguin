


function makeGetRequest() {
    let url = 'script.php';

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        }
    })
        .then(res => res.json())
        .then(data => {
            console.log('Data received:', data);
        })
        .catch(e => {
            console.error('Error proccesssing GET request:', e);
        });
}



function makePostRequest() {
    let user = {
        'name': 'Rimuru',
        'age': 40,
        'type': 'Slime'
    }
    let url = 'script.php'
    fetch(url, {
        'method': 'POST',
        'headers': {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify(user)
    })
        .then(res => res.json())
        .then(data => {
            console.log(data);
        })
        .catch(e => {
            console.error('Error:', e)
        });
}

// makeGetRequest();
// makePostRequest();









    //Basic syntax on fetch
// fetch(url, options).then(res => {
//     //handle response
// }).catch(error => {
//     //handle error
// })

// make a POST request