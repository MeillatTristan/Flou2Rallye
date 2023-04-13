let collection;
let buttonAdd;
let span;

window.onload = () => {
    let inputsIcons = document.getElementsByClassName('inputIcon');
    let containerAvantage = document.getElementsByClassName('containerAvantage');
    console.log(containerAvantage)
    for (let i = 0; i < containerAvantage.length; i++) {
        const avantage = containerAvantage[i];
        let btnSupprs = avantage.getElementsByClassName('btn-danger');
        btnSupprs[0].addEventListener('click', function () {
            console.log(btnSupprs[0])
            btnSupprs[0].parentElement.parentElement.parentElement.remove();
        })
    }


    for (let input of inputsIcons) {
        let inputValue = input.value;
        icon = document.createElement('span');
        icon.innerHTML = inputValue;
        input.parentElement.appendChild(icon)
        input.parentElement.insertBefore(document.createElement('br'),input)
    }

    collection = document.getElementById('avantages');
    span = document.getElementById('spanAvantage');

    buttonAdd = document.createElement("button");
    buttonAdd.className = "ajout-avantage btn btn-secondary";
    buttonAdd.innerText = "ajouter un avantage";
    buttonAdd.type = "button";

    let newButton = span.append(buttonAdd);

    collection.dataset.index = collection.getElementsByTagName('input').length;

    buttonAdd.addEventListener('click', () => {
        addButton(collection, newButton);
    })
}


function addButton(collection, newButton) {
    let prototype = collection.dataset.prototype;
    let index = collection.dataset.index;

    prototype = prototype.replace(/__name__/g, index);

    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");
    newForm.className = "avantageGroupInput"

    let buttonSuppr = document.createElement("button");
    buttonSuppr.type = "button";
    buttonSuppr.className = "btn btn-danger";
    buttonSuppr.id = "delete-avantage-" + index;
    buttonSuppr.innerText = "Supprimer cet avantage"

    newForm.append(buttonSuppr);
    collection.dataset.index++;

    let buttonAjout = collection.querySelector(".ajout-avantage");
    span.insertBefore(newForm, buttonAjout);

    buttonSuppr.addEventListener('click', function () {
        this.previousElementSibling.parentElement.remove();
    })

    let inputIcon = document.getElementById("outil_avantages_" + index + "_icon");
    inputIcon.addEventListener('input', () =>{
        let inputValue = inputIcon.value;
        icon = document.createElement('span');
        icon.innerHTML = inputValue;
        inputIcon.parentElement.appendChild(icon);
        
    })
    inputIcon.parentElement.insertBefore(document.createElement('br'), inputIcon);
    
    
}
