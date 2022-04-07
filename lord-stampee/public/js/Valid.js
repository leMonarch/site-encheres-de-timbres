export default class Valid {

    constructor(elForm){

        this._elForm = elForm;

        this._elInputKeyword = elForm.querySelector('[data-js-keyword]');

        this._elPrixMin = elForm.querySelector('[data-js-prix-min]');
        this._elPrixMinContainer = elForm.querySelector(['[data-js-prix-min-value]']);
    
        this._elPrixMax = elForm.querySelector('[data-js-prix-max]');
        this._elPrixMaxContainer = elForm.querySelector(['[data-js-prix-max-value]']);
    
        this._elAnneeMin = elForm.querySelector('[data-js-annee-min]');
        this._elAnneeMinContainer = elForm.querySelector(['[data-js-annee-min-value]']);
    
        this._elAnneeMax = elForm.querySelector('[data-js-annee-max]');
        this._elAnneeMaxContainer = elForm.querySelector(['[data-js-annee-max-value]']);
    
        this._elInputRadioEnchereCategorie = elForm.querySelectorAll('input[name="enchere_categorie"]');


        //Labels
        this._keywordLabel = elForm.querySelector('[data-js-keyword-label]');
        this._categorieLabel = elForm.querySelector('[data-js-categorie-label]');
        this._prixLabel = elForm.querySelector('[data-js-prix-label]');
        this._anneeLabel = elForm.querySelector('[data-js-annee-label]');

        //Label checkboxes
        this._conditionLabel = elForm.querySelector('[data-js-condition-label]');
        this._certificationLabel = elForm.querySelector('[data-js-certification-label]');

        //Checkboxes
        //Conditions
        this._mentheCheckbox = elForm.querySelector('[data-js-menthe-checkbox]');
        this._utiliseCheckbox = elForm.querySelector('[data-js-utilise-checkbox]');
        this._inutiliseCheckbox = elForm.querySelector('[data-js-inutilise-checkbox]');
        //Certifications
        this._lordCheckbox = elForm.querySelector('[data-js-lord-checkbox]');
        this._bestCheckbox = elForm.querySelector('[data-js-best-checkbox]');


        this.isValid = true,
        this.isChecked = false;
        this.checkboxCondition = false;
        this.checkboxCertification = false;

        this.init();
        
    }

    init = () => {
        
    }

    validForm() {

        /* Input 'Nouvelle tâche' */
        if (this._elInputKeyword.value == '') {
            this._keywordLabel.classList.add('error');
            this.isValid = false;
        } else {
            if (this._keywordLabel.classList.contains('error')) this._keywordLabel.classList.remove('error');
        }

        /**Les sliders */
        if(this._elPrixMin.value > this._elPrixMax.value){
            this._prixLabel.classList.add('error');
            this._prixLabel.innerHTML = 'Le prix minimum doit être inférieur au prix maximum.';
            this.isValid = false;
        } else {
            this._prixLabel.innerHTML = '';
        }

        /**Les sliders */
        if(this._elAnneeMin.value > this._elAnneeMax.value){
            this._anneeLabel.classList.add('error');
            this._anneeLabel.innerHTML = 'L\'année minimum doit être inférieur à l\'année maximum.';
            this.isValid = false;
        } else {
            this._anneeLabel.innerHTML = '';
        }

        /* Inputs Radio */
        for (let i = 0, l = this._elInputRadioEnchereCategorie.length; i < l; i++) {
            if (this._elInputRadioEnchereCategorie[i].checked) this.isChecked = true;
        }

        /**On ajoute la classe error qui colorie en rouge les endroits obligatoires non remplis sinon on l'enleve */
        if (!this.isChecked) {
            this._categorieLabel.classList.add('error');
            this.isValid = false;
        } else {
            if (this._categorieLabel.classList.contains('error')) this._categorieLabel.classList.remove('error');
        }

        if (this._mentheCheckbox.checked) this.checkboxCondition = true;
        if (this._utiliseCheckbox.checked) this.checkboxCondition = true;
        if (this._inutiliseCheckbox.checked) this.checkboxCondition = true;

        /**On ajoute la classe error qui colorie en rouge les endroits obligatoires non remplis sinon on l'enleve */
        if (!this.checkboxCondition) {
            this._conditionLabel.classList.add('error');
            this._conditionLabel.innerHTML = 'Entrez au moins une condition.';
            this.isValid = false;
        } else {
            this._conditionLabel.innerHTML = '';
        }

        if (this._lordCheckbox.checked) this.checkboxCertification = true;
        if (this._bestCheckbox.checked) this.checkboxCertification = true;

        /**On ajoute la classe error qui colorie en rouge les endroits obligatoires non remplis sinon on l'enleve */
        if (!this.checkboxCertification) {
            this._certificationLabel.classList.add('error');
            this._certificationLabel.innerHTML = 'Entrez au moins une certification.';
            this.isValid = false;
        } else {
            this._certificationLabel.innerHTML = '';
        }
        
       
    }

    get estValid(){
        this.validForm();
        return this.isValid;
    }
    

}
