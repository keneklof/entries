function changeEntryFee (id){
var paid = document.getElementById(id);

if(paid.classList.contains("btn-danger")){
 paid.classList.remove("btn-danger");
 paid.classList.add("btn-success");
 paid.innerHTML = "Maksettu";
} else if (paid.classList.contains("btn-success")){
 paid.classList.remove("btn-success");
 paid.classList.add("btn-danger");
 paid.innerHTML = "Ei maksettu";
}
}

