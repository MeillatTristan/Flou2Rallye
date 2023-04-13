    function myFunction(myTBODY,myInput) {
    var input, filter, tbody, tr, a, i, txtValue;
    input = document.getElementById(myInput);
    filter = input.value.toUpperCase();
    tbody = document.getElementById(myTBODY);
    tr = tbody.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
    a = tr[i];
    txtValue = a.textContent || a.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
    tr[i].style.display = "";
} else {
    tr[i].style.display = "none";
}
}
}






