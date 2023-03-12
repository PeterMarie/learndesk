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

function showSignUp() {
  document.getElementById("login-form").style.display = "none";
  document.getElementById("signup-form").style.display = "block";
}

function showLogin() {
  document.getElementById("login-form").style.display = "block";
  document.getElementById("signup-form").style.display = "none";
}

// Get form elements

// Listen for signin form submit
document.getElementById("signin_form").addEventListener("submit", async (e) => {
  e.preventDefault();

  const reg_no = document.getElementById("login-regno").value;
  const password = document.getElementById("login-password").value;
  if((reg_no== "") || (password == "")){
  
      } else {
        let formdata = new FormData();
        formdata.append('reg_no', reg_no);
        formdata.append('password', password);
        formdata.append('task', 'signin');
        ajaxpost('loginproxy.php', signed_in, formdata);
      }
});

function signed_in(response){
  const data = JSON.parse(response.responseText);
  if(data['status'] == "success"){
      switch (data['role']) {
        case 'student':
          window.location.replace('student/dashboard.php');
          break;

        case 'instructor':
          window.location.replace('instructor/dashboard.php');
          break;
      
        default:
          break;
      }
  } else {
    alert(data['error']);
  }
}
