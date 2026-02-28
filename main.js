//CHECKBOX LOGIC FOR FLIGHT LOGGERS
function flightLoggerOne() {
  var logger1 = document.getElementById("flight-logger-1");
  var logger2 = document.getElementById("flight-logger-2");
  var checkboxLoggerFiles = document.getElementById("checkbox-logger-files");

  if (logger1.hasAttribute("required")) {
    logger1.removeAttribute("required");
  } else {
    logger1.setAttribute("required", "true");
  } 

  if(checkboxLoggerFiles.checked) {
    logger1.setAttribute("disabled", "true");
    logger2.setAttribute("disabled", "true");
  } else {
    logger1.removeAttribute("disabled");
    logger2.removeAttribute("disabled");
  }
}

function deletePilot(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
            }
        }
        xmlhttp.open("GET", "delete_pilot.php?pilot-link-id="+id, true);
        xmlhttp.send();
}