import { InviteResult } from './CourseCreation.js'

document.getElementById('invite').addEventListener('click', function(event) {
    event.preventDefault();
    handleFormSubmit();
});

function handleFormSubmit() {
    // 读取上传的 .csv 文件并提取学生信息
    var fileInput = document.getElementById('drop_box'); //fileInput获取HTML文件输入元素（即用户选择文件的输入框）
    var file = fileInput.files[0]; //file获取用户在文件输入框中选定的第一个文件。
    var reader = new FileReader(); //reader是一个FileReader对象，用于读取用户选定的文件内容。
    
    reader.onload = function(event) {  //当FileReader成功读取文件内容时，会调用这个回调函数。
        var studentData = event.target.result; //event.target.result包含文件内容（以文本形式）
        var students = parseStudentData(studentData); //studentData存储文件内容。parseStudentData(studentData)解析文件内容，提取学生信息，并将其转换为学生对象数组。
        var payload = createPayload(students); //createPayload(students)创建包含课程信息和学生数据的payload对象
        sendPayloadToServer(payload); //sendPayloadToServer(payload)将payload发送到服务器
    };
        if(file){
    reader.readAsText(file);  //reader.readAsText(file)方法开始读取文件内容，并将其作为文本读取。读取完成后会自动触发reader.onload回调函数。
    } else{
            alert('Please select a CSV file to upload.');
          }
    
    

}

//为了让handleFormSubmit函数正常工作，还需要定义支持函数：parseStudentData、createPayload和sendPayloadToServer。这些函数分别用于解析文件内容、创建数据包并发送到服务器




function parseStudentData(data) { //定义一个名为 parseStudentData 的函数，接受一个参数 data，代表包含学生信息的字符串
    // 将文件内容按行拆分
    var lines = data.split('\n'); //data.split('\n') 将整个文件内容按换行符分割成一个数组，每个元素代表文件中的一行。lines 是一个数组，每个元素是文件中的一行字符串。
    var students = []; //初始化一个空数组 students，用来存储解析后的学生对象。

    lines.forEach(function(line) { //使用 forEach 方法遍历 lines 数组中的每一行。
        var parts = line.split(','); //将每一行按逗号分隔成多个部分，生成一个数组 parts。
        if (parts.length === 3) { // 确保每行有三个部分
            students.push({
                firstName: parts[0].trim(),
                lastName: parts[1].trim(),
                email: parts[2].trim()
            });                        //如果条件满足，创建一个学生对象：
                              //firstName 为 parts[0]，即第一个部分，并去掉首尾的空格。
                             //lastName 为 parts[1]，即第二个部分，并去掉首尾的空格。
                            //email 为 parts[2]，即第三个部分，并去掉首尾的空格。
                           //将这个学生对象添加到 students 数组中。
        }
    });               //解析后的结果将是：   result  
   // { firstName: "John", lastName: "Doe", email: "john.doe@example.com" },
  //{ firstName: "Jane", lastName: "Smith", email: "jane.smith@example.com" }


    return students;
}
//该函数接收一个参数 students，这是一个包含学生信息的数组。这个数组中的每个
//元素通常是一个对象，包含学生的名字、姓氏和邮箱等信息。


function createPayload(students) {                //courseData 是一个对象，用于存储课程信息和学生列表。该对象包含以下属性：
    var courseData = {
        prefix: document.getElementById('pre_fix').value,
        number: document.getElementById('c_num').value,
        name: document.getElementById('c_name').value,
        term: document.getElementById('Term').value,
        students: students
    };
    return courseData; //该函数返回 courseData 对象，这个对象包含了所有从页面表
                       //单元素中获取的课程信息以及传入的学生列表
}
//{ result
//    prefix: "CS",
//    number: "101",
//    name: "Introduction to Programming",
//    term: "Fall 2024",
//    students: [
//        { firstName: "John", lastName: "Doe", email: "john.doe@example.com" },
//        { firstName: "Jane", lastName: "Smith", email: "jane.smith@example.com" }
//   ]
//}





function sendPayloadToServer(payload) {
    fetch('../PHP/invite_validation.php', {   //使用 fetch 函数向服务器发送请求，目标 URL 是 b.php
        method: 'POST', //method: 'POST' 指定请求方法为 POST，这意味着我们要向服务器发送数据。
        headers: { // //headers: { 'Content-Type': 'application/json' } 指定请求头，表示发送的数据类型是 JSON。
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)  //body: JSON.stringify(payload) 将 payload 对象转换为 JSON 字符串，并作为请求的主体发送。
    })
    .then(response => InviteResult(response))  //.then(response => response.json()) 处理服务器的响应，
                                        //并将响应体解析为 JSON 格式。response.json() 返回一个解析后的 
                                        //JSON 对象，并将其传递给下一个 .then()。
    .then(data => {
        handleServerResponse(data);     //.then(data => { handleServerResponse(data); }) 
                                         //使用解析后的 JSON 数据调用 handleServerResponse 函数。
                                         //这个函数是你定义的，用于处理服务器返回的数据
    })

    .catch(error => {
        console.error('Error:', error);  //.catch(error => { console.error('Error:', error); })
                                        // 捕获并处理请求过程中可能出现的任何错误，并在控制台打印错误信息。
    });
}
                                   
//总结
//函数作用: sendPayloadToServer 函数的作用是将传入的 payload 对象以 JSON 格式发送到服务器的 b.php 文件，并处理服务器的响应。
//具体步骤:
//使用 fetch 发送一个 POST 请求到 b.php，并将 payload 转换为 JSON 字符串作为请求体。
//处理服务器的响应，将响应体解析为 JSON 格式，并调用 handleServerResponse 函数来处理解析后的数据。
//如果请求过程中出现任何错误，捕获错误并打印错误信息。





function handleServerResponse(data) {
    
    if (data.ok) {
        
        console.log("Post successful");
    } else {
        console.log("Post Un-successful");
    }
}



//用户点击“Finish”按钮，触发点击事件监听器。
//监听器调用handleFormSubmit函数。
//handleFormSubmit函数读取文件内容，解析学生数据，创建数据包，并发送到服务器。
//服务器响应后，sendPayloadToServer函数的then方法调用handleServerResponse函数。
//handleServerResponse函数处理服务器响应，并在页面上显示成功或错误消息。
//通过上述流程，您的应用程序能够处理表单提交、读取和解析文件内容、创建并发送数据包到服务器，以及处理服务器响应并在页面上显示反馈消息。
