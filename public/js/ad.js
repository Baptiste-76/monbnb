$('#add-image').click(function() {
    /* On récupère le numéro des futurs champs qu'on va créer en récupérant le prochain id disponible grâce à l'input caché (le "+" permet d'obtenir le résultat en Integer et non en String comme le
    forcerait normalement JavaScript) */
    const index = +$('#widgets-counter').val();

    /* On récupère le prototype des nouvelles entrées (= code HTML créé par Symfony grâce à l'option ('allow_add' => true) définie dans le formBuilder du formulaire "AdType")
    Dans le code récupéré, on remplace "__name__" par le numéro de l'image en cours (= index) et on le fait à chaque fois que ce terme apparaît (= g) */
    const template = $('#ad_images').data('prototype').replace(/__name__/g, index);

    // On injecte ce code dans la div
    $('#ad_images').append(template);
    // On ajoute 1 à la valeur de l'input caché (id ++)
    $('#widgets-counter').val(index + 1);

    // On injecte un bouton 'supprimer' pour cette  nouvelle image
    handleDeleteButtons();
})

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function() {
        // "this" représente ici le bouton de suppression sur lequel on vient de cliquer / "dataset" représente tous les attributs "data-XXX" / "target" représente l'attribut que je veux atteindre
        const target = this.dataset.target
        $(target).remove();
    })
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

/* On appelle la fonction qui permet de créer des boutons de suppression pour chaque image dès le chargement de la page afin que lorsqu'on accède à la page pour modifier une annonce déjà existante, on 
puisse supprimer une/des image(s) déjà renseignée(s) */
handleDeleteButtons();
// On appelle aussi la fonction qui permet de tenir compte des images déjà présentes dans l'annonce avant une modification
updateCounter();