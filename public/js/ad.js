//Affiche un formulaire d'ajout d'image lorsque je click sur le boutton "add_images"
$('#add-image').click(function(){
    //Je récupère le numéros des futur champs que je vais créer
    const index = +$('#wiggets-counter').val();
    //Je récupère le prototype des entrée passée par la classe CollectionType et je remplace __name__ par la const index
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
    //j'inject le code au sains de la div
    $('#ad_images').append(tmpl);

    $('#wiggets-counter').val(index + 1);

    //Je gère le bouton supprimer
    handelDeleteButtons();
});
function handelDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    })
}
//Fonction qui évite les doublon lors de l'ajout d'image en récupérant le nommbres d'image et en l'ajoutant a la valeur du champs caché
function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#wiggets-counter').val(count);
}

updateCounter();

//Je gère le bouton supprimer
handelDeleteButtons();