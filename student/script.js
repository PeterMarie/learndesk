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
    load_pending_assignments();
    load_submitted_assignments();
    load_registered_courses();
    load_profile_data();
});

function load_pending_assignments(){
    let formdata = new FormData();
    formdata.append('task', 'get_pending_assignments');
    ajaxpost("backend.php", show_pending_assignments, formdata);
    return;
}
function load_submitted_assignments(){
    let formdata = new FormData();
    formdata.append('task', 'get_submitted_assignments');
    ajaxpost("backend.php", show_submitted_assignments, formdata);
    return;
}
function load_registered_courses(){
    let formdata = new FormData();
    formdata.append('task', 'get_registered_courses');
    ajaxpost("backend.php", show_registered_courses, formdata);
    return;
}
function load_profile_data(){
    let formdata = new FormData();
    formdata.append('task', 'get_profile_data');
    ajaxpost("backend.php", show_profile_data, formdata);
    return;
}
function submit_assignment(){
    let formdata = new FormData();
    formdata.append('task', 'get_profile_data');
    ajaxpost("backend.php", show_profile_data, formdata);
    return;
}
function request_profile_edit(){
    let formdata = new FormData();
    formdata.append('task', 'get_profile_data');
    ajaxpost("backend.php", show_profile_data, formdata);
    return;
}
function find_assignments(){
    let course_id = document.getElementById('select_course').value;
    if((course_id != "") && (course_id > 0)){
        let formdata = new FormData();
        formdata.append('task', 'get_pending_assignments_in_course');
        formdata.append('course_id', course_id);
        ajaxpost("backend.php", show_select_assignments, formdata);
    } else {
        document.getElementById('select_assignment').innerHTML = "";
    }
    return;
}
document.getElementById("submit-assignment-form").addEventListener("submit", async (e) => {
  e.preventDefault();

  const assignment_id = document.getElementById('select_assignment').value;
  const assignment = document.getElementById('assignment_file').files[0];

  if((assignment_id != "") || (assignment_id > 0)){
        let formdata = new FormData();
        formdata.append('assignment_id', assignment_id);
        formdata.append('assignment_file', assignment);
        formdata.append('task', 'submit_assignment');
        ajaxpost('backend.php', assignment_submitted, formdata)
      }
});

//AJAX Action Functions
function show_pending_assignments(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        if(Object.keys(data['assignments']).length <= 0){
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.setAttribute('colspan', 4);
            td.innerText = "You're all caught up! No Pending Assignments Available";
            tr.appendChild(td);
            document.getElementById('p-assignments-table').appendChild(tr);
        }
        else {
            for(let i = 1; i <= Object.keys(data['assignments']).length; i++){
                let tr = document.createElement('tr');
                let td1 = document.createElement('td');
                td1.innerText = i;
                let td2 = document.createElement('td');
                td2.innerText = data['assignments'][i]['course'];
                let td3 = document.createElement('td');
                td3.innerText = data['assignments'][i]['name'];
                let td4 = document.createElement('td');
                td4.innerText = data['assignments'][i]['due_date'];
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                document.getElementById('p-assignments-table').appendChild(tr);
            }
        }
    }
}

function show_submitted_assignments(response){
   // alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        if(Object.keys(data['assignments']).length <= 0){
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.setAttribute('colspan', 5);
            td.innerText = "You haven't submitted any assignments yet";
            tr.appendChild(td);
            document.getElementById('s-assignments-table').appendChild(tr);
        }
        else {
            for(let i = 1; i <= Object.keys(data['assignments']).length; i++){
                let tr = document.createElement('tr');
                let td1 = document.createElement('td');
                td1.innerText = i;
                let td2 = document.createElement('td');
                td2.innerText = data['assignments'][i]['course'];
                let td3 = document.createElement('td');
                td3.innerText = data['assignments'][i]['name'];
                let td4 = document.createElement('td');
                td4.innerText = data['assignments'][i]['date_submitted'];
                let td5 = document.createElement('td');
                td5.innerText = data['assignments'][i]['grade'];
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                tr.appendChild(td5);
                document.getElementById('s-assignments-table').appendChild(tr);
            }
        }
    }
}

function show_registered_courses(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        if(Object.keys(data['registered_courses']).length <= 0){
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.setAttribute('colspan', 4);
            td.innerText = "You have no registered courses. Please contact your Faculty or Department";
            tr.appendChild(td);
            document.getElementById('registered-courses-table').appendChild(tr);
            
            let option = document.createElement('option');
            option.innerText = "No Registered Courses";
            document.getElementById('select_course').appendChild(option);
        }
        else {
            let option = document.createElement('option');
            option.innerText = "Select course";
            document.getElementById('select_course').appendChild(option);

            for(let i = 1; i <= Object.keys(data['registered_courses']).length; i++){
                let tr = document.createElement('tr');
                let td1 = document.createElement('td');
                td1.innerText = i;
                let td2 = document.createElement('td');
                td2.innerText = data['registered_courses'][i]['code'];
                let td3 = document.createElement('td');
                td3.innerText = data['registered_courses'][i]['name'];
                let td4 = document.createElement('td');
                td4.innerText = data['registered_courses'][i]['instructor'];
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                document.getElementById('registered-courses-table').appendChild(tr);

                let option = document.createElement('option');
                option.innerText = data['registered_courses'][i]['name'];
                option.setAttribute('value', data['registered_courses'][i]['id']);
                document.getElementById('select_course').appendChild(option);
            }
        }
    }
}

function show_select_assignments(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        document.getElementById('select_assignment').innerHTML = "";
        if(Object.keys(data['assignments']).length <= 0){
            let option = document.createElement('option');
            option.innerText = "No Assignment Found";
            document.getElementById('select_assignment').appendChild(option);
        }
        else {
            let option = document.createElement('option');
            option.innerText = "Select Assignment";
            document.getElementById('select_assignment').appendChild(option);

            for(let i = 1; i <= Object.keys(data['assignments']).length; i++){
                let option = document.createElement('option');
                option.innerText = data['assignments'][i]['name'];
                option.setAttribute('value', data['assignments'][i]['id']);
                document.getElementById('select_assignment').appendChild(option);
            }
        }
    }
}

function assignment_submitted(response){
    //alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        alert('successful');
    }
}

function show_profile_data(response){
    alert(response.responseText);
    const data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        document.getElementById('reg-no').value = data['username'];
        document.getElementById('first-name').value = data['firstname'];
        document.getElementById('last-name').value = data['lastname'];
        document.getElementById('middle-name').value = data['middlename'];
        document.getElementById('email').value = data['email'];
        document.getElementById('first-name').value = data['firstname'];
    }

}