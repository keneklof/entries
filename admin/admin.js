function changeEntryFee(id) {
    var paid = document.getElementById(id);

    if (paid.classList.contains("btn-danger")) {
        /*--Performs the AJAX request--*/
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "update_entry_fee.php?pilot-link-id=" + id + "&paid=0", true);
        xmlhttp.send();
        paid.classList.remove("btn-danger");
        paid.classList.add("btn-success");
        paid.classList.add("animate__flash");
        setTimeout(function () {
            paid.classList.remove("animate__flash")
        }, 1000)



    } else if (paid.classList.contains("btn-success")) {
        /*--Performs the AJAX request--*/
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "update_entry_fee.php?pilot-link-id=" + id + "&paid=1", true);
        xmlhttp.send();
        paid.classList.remove("btn-success");
        paid.classList.add("btn-danger");
        paid.classList.add("animate__flash");
        setTimeout(function () {
            paid.classList.remove("animate__flash")
        }, 1000)
    }
}

