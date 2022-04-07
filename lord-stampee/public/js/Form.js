import RechercheAvancee from './rechercheAvancee.js';
import Valid from './Valid.js';


export default class Form {
    constructor(elForm) {

        this._elForm = elForm;

        this._elBouton = elForm.querySelector('[data-js-btn]');



        // this.init();
    }

    init = () => {

        this._elBouton.addEventListener('submit', function(e) {
            e.preventDefault();
            let valid = new Valid(this._elForm);
            if(valid.estValid){
                new RechercheAvancee(this._elForm);
            }
        }.bind(this));

        

    }

}