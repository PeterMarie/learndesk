function ajax(url, cFunction) {
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        cFunction(this);
        }
    };
    $('#win-position-value').text("got here too");
    xhttp.open("GET", url, true);
    /* xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");*/
    xhttp.send();
}

function ajaxpost(url, cFunction, formData){
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            cFunction(this);
        }
    };
    xhr.send(formData);
}

document.addEventListener("DOMContentLoaded", function(){
    load_current_students();
    load_assignments();
    load_assigned_courses();
    load_profile_data();
});

function load_current_students(){
    let formdata = new FormData();
    formdata.append('task', 'get_current_students');
    ajaxpost("backend.php", show_current_students, formdata);
    return;
}
function load_assignments(){
    let formdata = new FormData();
    formdata.append('task', 'get_assignments');
    ajaxpost("backend.php", show_assignments, formdata);
    return;
}
function load_assigned_courses(){
    let formdata = new FormData();
    formdata.append('task', 'get_assigned_courses');
    ajaxpost("backend.php", show_assigned_courses, formdata);
    return;
}
function load_profile_data(){
    let formdata = new FormData();
    formdata.append('task', 'get_profile_data');
    ajaxpost("backend.php", show_profile_data, formdata);
    return;
}

//AJAX Action Functions
document.getElementById("set-assignment-form").addEventListener("submit", async (e) => {
  e.preventDefault();

  const course_id = document.getElementById('select_course').value;
  const assignment_title = document.getElementById('assignment_title').value;
  const assignment_description = document.getElementById('assignment_description').value;
  const due_date = document.getElementById('due_date').value;
  const assignment_brief = document.getElementById('assignment_brief').files[0];

  if((course_id != "") && (course_id > 0) && (assignment_title != "") && (assignment_description != "") && (due_date != "")){
        let formdata = new FormData();
        formdata.append('course_id', course_id);
        formdata.append('assignment_title', assignment_title);
        formdata.append('assignment_description', assignment_description);
        formdata.append('due_date', due_date);
        formdata.append('assignment_brief', assignment_brief);
        formdata.append('task', 'set_assignment');
        ajaxpost('backend.php', assignment_set, formdata);
      }
});

function show_current_students(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        if(Object.keys(data['current_students']).length <= 0){
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.setAttribute('colspan', 4);
            td.innerText = "There are no students registered to your assigned courses";
            tr.appendChild(td);
            document.getElementById('students-table').appendChild(tr);
        }
        else {
            for(let i = 1; i <= Object.keys(data['current_students']).length; i++){
                let tr = document.createElement('tr');
                let td1 = document.createElement('td');
                td1.innerText = i;
                let td2 = document.createElement('td');
                td2.innerText = data['current_students'][i]['name'];
                let td3 = document.createElement('td');
                td3.innerText = data['current_students'][i]['department'];
                let td4 = document.createElement('td');
                td4.innerText = data['current_students'][i]['reg_no'];
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                document.getElementById('students-table').appendChild(tr);
            }
        }
    }
}

function show_submitted_assignments(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
    }
}

function show_assigned_courses(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        if(Object.keys(data['assigned_courses']).length <= 0){
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.setAttribute('colspan', 3);
            td.innerText = "You have no assigned courses. Please contact your Faculty or Department";
            tr.appendChild(td);
            document.getElementById('assigned-courses-table').appendChild(tr);
            
            let option = document.createElement('option');
            option.innerText = "No Assigned Courses";
            document.getElementById('select_course').appendChild(option);
        }
        else {
            let option = document.createElement('option');
            option.innerText = "Select course";
            document.getElementById('select_course').appendChild(option);

            for(let i = 1; i <= Object.keys(data['assigned_courses']).length; i++){
                let tr = document.createElement('tr');
                let td1 = document.createElement('td');
                td1.innerText = i;
                let td2 = document.createElement('td');
                td2.innerText = data['assigned_courses'][i]['code'];
                let td3 = document.createElement('td');
                td3.innerText = data['assigned_courses'][i]['name'];
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                document.getElementById('assigned-courses-table').appendChild(tr);

                let option = document.createElement('option');
                option.innerText = data['assigned_courses'][i]['name'];
                option.setAttribute('value', data['assigned_courses'][i]['id']);
                document.getElementById('select_course').appendChild(option);
            }
        }
    }
}

function assignment_set(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        alert('successful');
    }
}

function show_assignments(response){
    alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        if(Object.keys(data['assignments']).length <= 0){
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.setAttribute('colspan', 5);
            td.innerText = "No  Assignments Available";
            tr.appendChild(td);
            document.getElementById('assignments-table').appendChild(tr);
        }
        else {
            for(let i = 1; i <= Object.keys(data['assignments']).length; i++){
                let tr = document.createElement('tr');
                let td1 = document.createElement('td');
                td1.innerText = i;
                let td2 = document.createElement('td');
                td2.innerText = data['assignments'][i]['student'];
                let td3 = document.createElement('td');
                td3.innerText = data['assignments'][i]['title'];
                let td4 = document.createElement('td');
                td4.innerText = data['assignments'][i]['course_code'];
                let td5 = document.createElement('td');
                let button = document.createElement('button');
                let button2 = document.createElement('button');
                switch (data['assignments'][i]['status']) {
                    case 'pending':
                        button.setAttribute('onclick', 'close_submission(' + data['assignments'][i]['id'] + ')');
                        button.innerText = "Close Submission";
                        td5.appendChild(button);
                        break;

                    case 'graded':
                        button.setAttribute('onclick', 'view_assignment(' + data['assignments'][i]['id'] + ')');
                        button.innerText = "View Assignment";
                        td5.appendChild(button);
                        button2.setAttribute('onclick', 'view_grade(' + data['assignments'][i]['id'] + ')');
                        button2.innerText = "View Assignment";
                        td5.appendChild(button2);
                        break;

                    case 'submitted':
                        button.setAttribute('onclick', 'view_assignment(' + data['assignments'][i]['id'] + ')');
                        button.innerText = "View Assignment";
                        td5.appendChild(button);
                        button2.setAttribute('onclick', 'set_grade(' + data['assignments'][i]['id'] + ')');
                        button2.innerText = "View Assignment";
                        td5.appendChild(button2);
                        break;

                    case 'expired':
                        button.setAttribute('onclick', 'reopen_submission(' + data['assignments'][i]['id'] + ')');
                        button.innerText = "View Assignment";
                        td5.appendChild(button);
                        break;

                    case 'extension':
                        button.setAttribute('onclick', 'grant_extension(' + data['assignments'][i]['id'] + ')');
                        button.innerText = "View Assignment";
                        td5.appendChild(button); +34667645851
                        button2.setAttribute('onclick', 're(' + data['assignments'][i]['id'] + ')');
                        button2.innerText = "View Assignment";
                        td5.appendChild(button2);
                        break;
                
                    default:
                        break;
                }
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                tr.appendChild(td5);
                document.getElementById('assignments-table').appendChild(tr);
            }
        }
    }
}
function show_profile_data(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        document.getElementById('staff-no').value = data['username'];
        document.getElementById('first-name').value = data['firstname'];
        document.getElementById('last-name').value = data['lastname'];
        document.getElementById('middle-name').value = data['middlename'];
        document.getElementById('email').value = data['email'];
        document.getElementById('first-name').value = data['firstname'];
    }
}
