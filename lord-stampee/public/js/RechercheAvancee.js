


export default class RechercheAvancee {
    constructor(elFormAvancee) {

        //Le form de recherche avancee
        this._el = elFormAvancee;

        //Le input des mots clés
        this._elKeyword = this._el.querySelector('[data-js-keyword]');

        //Les deux radiobuttons
        this._elInputCategorie = this._el.querySelectorAll('input[name="enchere_categorie"]');

        //Les sliders
        this._elPrixMin = this._el.querySelector('[data-js-prix-min]');
        this._elPrixMax = this._el.querySelector('[data-js-prix-max]');
        this._elAnneeMin = this._el.querySelector('[data-js-annee-min]');
        this._elAnneeMax = this._el.querySelector('[data-js-annee-max]');

        //Checkboxes
        //Conditions
        this._mentheCheckbox = this._el.querySelector('[data-js-menthe-checkbox]');
        this._utiliseCheckbox = this._el.querySelector('[data-js-utilise-checkbox]');
        this._inutiliseCheckbox = this._el.querySelector('[data-js-inutilise-checkbox]');
        //Certifications
        this._lordCheckbox = this._el.querySelector('[data-js-lord-checkbox]');
        this._bestCheckbox = this._el.querySelector('[data-js-best-checkbox]');

        //Recherche container et recherche template
        this._elToDoList = document.querySelector('[data-js-tasks]');
        this._elTemplate = document.querySelector('[data-js-task-template]');

        this.init();
    }


    init() {
        this.rechercheAvancee();
    }


    rechercheAvancee() {
        let menthe = '', utilise = '', inutilise = '', lord = '', best = '';
        if(this._mentheCheckbox.checked) menthe = 'Menthe';
        if(this._utiliseCheckbox.checked) utilise = 'Utilisé';
        if(this._inutiliseCheckbox.checked) inutilise = 'Inutilisé';
        if(this._lordCheckbox.checked) lord = 'Lord Stampee';
        if(this._bestCheckbox.checked) best = 'Best Seller';


        //Le input du mot-cle
        this._elKeywordName = this._elKeyword.name;

        let categorie = '';
        /**Affecter l'importance a la variable importance parmi le choix des bountons radios du form */
        for (let i = 0, l = this._elInputCategorie.length; i < l; i++) {
            if (this._elInputCategorie[i].checked) categorie = this._elInputCategorie[i].value;
        }

        let infosrecherche = {
            'keyword' : this._elKeyword.value,
            'categorie' : categorie,
            'prix_min' : this._elPrixMin.value,
            'prix_max' : this._elPrixMax.value,
            'annee_min' : this._elAnneeMin.value,
            'annee_max' : this._elAnneeMax.value,
            'menthe' : menthe,
            'utilise' : utilise,
            'inutilise' : inutilise,
            'lord' : lord,
            'best' : best
        }
        /**definition de l'entete */
        let myInit = { 
            method: 'post',
            headers: {
             'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            /**On passe le nom de la colonne à trier ASC dans le SQL*/
          
        };
        /**appelle au serveur avec l'entete */
        // fetch('/lord-stampee/App/Controllers/asynch/requetesAsynch.php', myInit)
        fetch(`/lord-stampee/App/Controllers/Home.php?action=rechercheavancee&timbre_nom=${infosrecherche.keyword}&timbre_couleur=${infosrecherche.keyword}
                    &enchere_categorie=${infosrecherche.categorie}
                    &enchere_prix_actuel_min=${infosrecherche.prix_min}
                    &enchere_prix_actuel_max=${infosrecherche.prix_max}&timbre_annee_min=${infosrecherche.annee_min}
                    &timbre_annee_max=${infosrecherche.annee_max}&condition_nom_menthe=${infosrecherche.menthe}
                    &condition_nom_utilise=${infosrecherche.utilise}&condition_nom_inutilise=${infosrecherche.inutilise}
                    &certification_nom_lord=${infosrecherche.lord}&certification_nom_best=${infosrecherche.best}
                    &pays_nom=${infosrecherche.keyword}&gagnant_nom=${infosrecherche.keyword}
                    &Users_user_firstname=${infosrecherche.keyword}`, myInit)
        .then(function(response){
            /**On recoit tous les tâches trier en json */
            console.log(response.json())
            if(response.ok) return response.json();
            else throw new Error('Erreur');
        }.bind(this))
        .then(function(data){

            console.log(data)
            // this._elToDoList.innerHTML = ''; //vider le html
            // if(data.length == 0){
            //     /**On mentionne si il n'y a aucune tache */
            //     this._elToDoList.innerHTML = '<p>Aucune taches</p>';
            // } else {
            //      /** On fait une double boucle sur le tableau qui regroupe les taches en format json */
            //     for( const index in data){
            //         let elCloneTaskTemplate = this._elTemplate.cloneNode(true);
            //         for( const prop in data[index]){
            //             /**Remplace les expressions par le data de la chaque taches dans un ordre trie */
            //             let regExp = new RegExp('{{' + prop + '}}', 'g');
            //             elCloneTaskTemplate.innerHTML = elCloneTaskTemplate.innerHTML.replaceAll(regExp, data[index][prop] );
            //         }
            //         let newTask = document.importNode(elCloneTaskTemplate.content, true);
            //         this._elToDoList.appendChild(newTask);
            //         /**Il faut les instancier egalement */
            //         new Task(this._elToDoList.lastElementChild);
            //     }
                

            // }
        }.bind(this))
        .catch(function(error) {
            console.log(`Il y a eu un problème avec l'opération fetch: ${error.message}`);
        }.bind(this));

    }
}