import("https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js")
$('.custom-file-input').on('change',function (e) {
    var inputFile = e.currentTarget;

    $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name);
});


function myFunctionRea() {
    var liste, texte;
    liste = document.getElementById("realisation_category");
    texte = liste.options[liste.selectedIndex].text;

    const attr = document.createAttribute("hidden");
    const elementImageSite = document.getElementById("imageSiteWeb")
    const elementCollection = document.getElementById("collection")



    if (texte ==='Site internet') {
        elementImageSite.removeAttribute('hidden');
        elementCollection.setAttributeNode(attr);

    } else if (texte ==='Choisissez une Categorie') {
        elementCollection.setAttributeNode(attr);

        elementImageSite.setAttributeNode(attr);

    } else {
        elementCollection.removeAttribute('hidden');
        elementImageSite.setAttributeNode(attr);

    }

}