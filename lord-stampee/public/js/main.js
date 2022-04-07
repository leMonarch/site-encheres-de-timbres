import Form from './Form.js';

(() => {

    let elPrixMin = document.querySelector('[data-js-prix-min]');
    let elPrixMinContainer = document.querySelector(['[data-js-prix-min-value]']);

    let elPrixMax = document.querySelector('[data-js-prix-max]');
    let elPrixMaxContainer = document.querySelector(['[data-js-prix-max-value]']);

    let elAnneeMin = document.querySelector('[data-js-annee-min]');
    let elAnneeMinContainer = document.querySelector(['[data-js-annee-min-value]']);

    let elAnneeMax = document.querySelector('[data-js-annee-max]');
    let elAnneeMaxContainer = document.querySelector(['[data-js-annee-max-value]']);

    elPrixMinContainer.innerHTML = elPrixMin.value;
    elPrixMaxContainer.innerHTML = elPrixMax.value;
    elAnneeMinContainer.innerHTML = elAnneeMin.value;
    elAnneeMaxContainer.innerHTML = elAnneeMax.value;

    elPrixMin.addEventListener('input', function(){
        elPrixMinContainer.innerHTML = elPrixMin.value;
    }.bind(this))
    elPrixMax.addEventListener('input', function(){
        elPrixMaxContainer.innerHTML = elPrixMax.value;
    }.bind(this))
    elAnneeMin.addEventListener('input', function(){
        elAnneeMinContainer.innerHTML = elAnneeMin.value;
    }.bind(this))
    elAnneeMax.addEventListener('input', function(){
        elAnneeMaxContainer.innerHTML = elAnneeMax.value;
    }.bind(this))


    let elForm = document.querySelector('[data-js-form-avance]');

    if (elForm) new Form(elForm); 

})();
