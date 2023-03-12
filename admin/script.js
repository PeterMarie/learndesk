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

function signed_in(response){
  alert(response.responseText);
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

// Listen for signup form submit
document.getElementById("signup_form").addEventListener("submit", async (e) => {
    e.preventDefault();

    const lastname = document.getElementById("signup-lastname").value;
    const firstname = document.getElementById("signup-firstname").value;
    const middlename = document.getElementById("signup-othername").value;
    const reg_no = document.getElementById("signup-regno").value;
    const email = document.getElementById("signup-email").value;
    const password = document.getElementById("signup-password").value;
    const password2 = document.getElementById("signup-confirmpassword").value;

    if((lastname== "") || (firstname== "") || (reg_no== "") || (email== "") || (password == "")){
      alert('Please fill all necessary fields');
      return;
    }
    if(password.length < 8) {
      alert('Password must be at least 8 characters long');
      return;
    }
    if(password != password2) {
      alert('Passwords do not match');
      return;
    }

    let formdata = new FormData();
    formdata.append('lastname', lastname);
    formdata.append('firstname', firstname);
    formdata.append('middlename', middlename);
    formdata.append('email', email);
    formdata.append('reg_no', reg_no);
    formdata.append('password', password);
    formdata.append('task', 'signup');
    ajaxpost('loginproxy.php', signed_in, formdata);
});
