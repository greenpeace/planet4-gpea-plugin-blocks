var donationformVue = {};
var url_vars = {};

// REFACTOR IE11 doesn't support UrlSearchParams, so custom UrlParam function.
// 	Consider polyfilling it now? or wait until we drop IE11 support and switch then?
function getUrlVars(){
  var vars = [],
    hash;
  var uri = decodeURIComponent(window.location.href.split('#')[0]);
  var hashes = uri.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++){
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}

$(document).ready(function() {
  let clangct=getUrlVars()['clangct'];

  if(clangct != undefined){
    $.ajax({
      url: '/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-landing.js?clangct='+clangct,
      dataType: 'script',
    });
  }

  url_vars = {
    'suggested_frequency' : getUrlVars()['per'],
    'marketingcode'       : getUrlVars()['mcode'],
    'literatuurcode'      : getUrlVars()['lcode'],
    'drplus'              : getUrlVars()['drplus'],
    'min_amount'          : getUrlVars()['min'],
    'suggested_amount'    : getUrlVars()['pref'],
    'override_amount'     : getUrlVars()['over']
  };

  $.each(url_vars, function(key, value){
    if (value !== undefined){
      switch (key) {
      case 'suggested_frequency':
        switch (value) {
        case 'E':
          formconfig.allow_frequency_override = 'false';
          formconfig.suggested_frequency = ['E', 'Eenmalig'];
          break;
        case 'M':
          formconfig.allow_frequency_override = 'false';
          formconfig.suggested_frequency = ['M', 'Maandelijks'];
          break;
        case 'F':
          formconfig.allow_frequency_override = 'false';
          formconfig.suggested_frequency = ['M', 'maandelijks voor 12 maanden'];
          break;
        default:
          formconfig.suggested_frequency = ['M', 'Maandelijks'];
          break;
        }
        break;
      case 'marketingcode':
        if (formconfig.suggested_frequency[0] === 'E') {
          let url_tmp = 'marketingcode_oneoff';
          formconfig[url_tmp] = value;
        } else {
          let url_tmp = 'marketingcode_recurring';
          formconfig[url_tmp] = value;
        }
        break;
      case 'drplus':
        if (value === 'true') {
          if (formconfig.suggested_frequency[0] === 'E') {
            formconfig.oneoff_amount1    = formconfig.drplus_amount1;
            formconfig.oneoff_amount2    = formconfig.drplus_amount2;
            formconfig.oneoff_amount3    = formconfig.drplus_amount3;
            formconfig.oneoff_suggested_amount = formconfig.drplus_amount2;
          } else {
            formconfig.recurring_amount1 = formconfig.drplus_amount1;
            formconfig.recurring_amount2 = formconfig.drplus_amount2;
            formconfig.recurring_amount3 = formconfig.drplus_amount3;
            formconfig.recurring_suggested_amount = formconfig.drplus_amount2;
          }
        }
        break;
      case 'min_amount':
        // if min_amount < lowest_amount => lowest_amount == min_amount
        formconfig.min_amount = value;
        if (value > Math.min(formconfig.oneoff_amount1, formconfig.recurring_amount1)){
          formconfig.min_amount = Math.min(formconfig.oneoff_amount1, formconfig.recurring_amount1);
        }
        break;
      case 'literatuurcode':
        formconfig.literatuurcode = value;
        break;
      case 'suggested_amount':
        var oneoff = 'oneoff_amount' + value;
        var recurring = 'recurring_amount' + value;
        formconfig.recurring_suggested_amount = formconfig[recurring];
        formconfig.oneoff_suggested_amount = formconfig[oneoff];
        break;
      case 'override_amount':
        if (formconfig.suggested_frequency[0] === 'E') {
          formconfig.oneoff_amount1    = value;
          formconfig.oneoff_suggested_amount = value;
        } else {
          formconfig.recurring_amount1 = value;
          formconfig.recurring_suggested_amount = value;
        }
        break;
      }
    }
  });

  if (formconfig.suggested_frequency[0] === 'F'){
    formconfig.allow_frequency_override = 'false';
    formconfig.suggested_frequency = ['M', 'maandelijks voor 12 maanden'];
  }

  Vue.use(window.vuelidate.default);
  const {
    required,
    between,
    minLength,
    maxLength,
    email,
    numeric,
    alphaNum,
    requiredUnless
  } = window.validators;
  Vue.use(VueFormWizard);

  Vue.config.devtools = true;


  Vue.component('step1', {
    template: `
        <div>
          <fieldset >
            <legend class="sr-only">Periodiek van de donatie</legend>
                <div class="form-group" v-bind:class="{ 'has-error': $v.machtigingType }">
                    <label for="machtigingType" v-if="formconfig.allow_frequency_override == 'true'">Ja ik steun Greenpeace:</label>
                    <label for="machtigingType" v-else>
                        Ja ik steun Greenpeace <strong>{{ formconfig.suggested_frequency[1] }}</strong>:
                    </label>
        
                    <div id="machtigingType" class="radio-list" role="radiogroup">
        
                        <input class="form-check-input" v-model.trim="machtigingType" type="radio" name="eenmalig" id="eenmalig" value="E" role="radio"       v-on:change="changePeriodic">
                        <label class="form-check-label form-control ml-0" for="eenmalig">Eenmalig</label>
        
                        <input class="form-check-input" v-model.trim="machtigingType" type="radio" name="maandelijks" id="maandelijks" value="M" role="radio" v-on:change="changePeriodic">
                        <label class="form-check-label form-control" for="maandelijks">Maandelijks</label>
                    </div>
        
        
                </div>
        </fieldset>
          
		<fieldset>
		<legend class="sr-only">Bedrag</legend>
            <div class="form-group" v-bind:class="{ 'has-error': $v.bedrag.$error }">
              <label for="amountList">Ik geef:</label>
              <div id="amountList" class="radio-list" role="radiogroup">
                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="transaction-amount" id="bedrag1" role="radio" v-bind:value="amount1">
                <label class="form-check-label form-control ml-0" for="bedrag1">EUR {{ amount1 }}</label>

                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="transaction-amount" id="bedrag2" role="radio" v-bind:value="amount2" checked="checked" tabindex="0">
                <label class="form-check-label form-control" for="bedrag2">EUR {{ amount2 }}</label>

                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="transaction-amount" id="bedrag3" role="radio" v-bind:value="amount3">
                <label class="form-check-label form-control" for="bedrag3">EUR {{ amount3 }}</label>
              </div>
              <div class="amount__popular">Meest gekozen</div>
            </div>

            <div class="form-group" v-bind:class="{ 'has-error': $v.bedrag.$error }">
              <label for="customAmount" class="form-control form-check-label col-4 ml-0" v-on:click="toggleCustomamount">Ander bedrag:</label>
              <div class="input-group col-8" id="input__customAmount">
                <div class="input-group-prepend">
                  <div class="input-group-text">EUR</div>
                </div>
                
                <input type="number" id="customAmount" class="form-control" v-model.trim="bedrag" @input="$v.bedrag.$touch()" name="transaction-amount">
                <span class="help-block" v-if="$v.bedrag.$error && !$v.bedrag.required">Bedrag is verplicht</span>
                <span class="help-block" v-if="$v.bedrag.$error && $v.bedrag.required && !$v.bedrag.numeric">Bedrag moet een nummer zijn</span>
                <span class="help-block" v-if="$v.bedrag.$error && $v.bedrag.required && $v.bedrag.numeric && !$v.bedrag.between">Het minimale donatiebedrag is {{ formconfig.min_amount }} euro</span>
              </div>
            </div>
		</fieldset>
	  
	  <fieldset v-if="machtigingType ==='E'">
	  <legend class="sr-only">Betalingsmethode</legend>
			<div class="form-group" v-bind:class="{ 'has-error': $v.betaling.$error }">
			  <label for="paymentMethods">Betalingswijze:</label>
			  <div id="paymentMethods" class="radio-list" role="radiogroup">
				<input class="form-check-input" v-model.trim="betaling" type="radio" name="ideal" id="ideal" value="ID" checked="checked" tabindex="0" role="radio"
				v-on:click="donationformVue.validateStep('step1');">
				<label class="form-check-label form-control ml-0" for="ideal">iDeal</label>
				<input class="form-check-input" v-model.trim="betaling" type="radio" name="machtiging" id="machtiging" value="EM" role="radio" v-on:click="donationformVue.validateStep('step1');">
				<label class="form-check-label form-control" for="machtiging">Eenmalige machtiging</label>
			  </div>
			</div> 
	  </fieldset>
          
           </div>`,
    data() {
      return {
        machtigingType:  formconfig.suggested_frequency[0],
        amount1:        (formconfig.suggested_frequency[0] === 'M') ? formconfig.recurring_amount1          : formconfig.oneoff_amount1,
        amount2:        (formconfig.suggested_frequency[0] === 'M') ? formconfig.recurring_amount2          : formconfig.oneoff_amount2,
        amount3:        (formconfig.suggested_frequency[0] === 'M') ? formconfig.recurring_amount3          : formconfig.oneoff_amount3,
        bedrag:         (formconfig.suggested_frequency[0] === 'M') ? formconfig.recurring_suggested_amount : formconfig.oneoff_suggested_amount,
        betaling:       (formconfig.suggested_frequency[0] === 'M') ? 'EM' : 'ID',
        formconfig:      formconfig,
      };
    },
    validations: {
      machtigingType: {
        required
      },
      bedrag: {
        required,
        numeric,
        between: between(formconfig.min_amount, 100000)
      },
      betaling: {
        required
      },
      form: ['machtigingType', 'bedrag', 'betaling' ]
    },
    mounted:function(){
      this.toggleCustomamount();
    },
    methods: {
      validate() {
        this.$v.form.$reset();
        this.$v.form.$touch();
        var isValid = !this.$v.form.$invalid;
        this.$emit('on-validate', this.$data, isValid);
        if(isValid){
          // Push step to tag manager
          dataLayer.push({
            'event': 'virtualPageViewDonatie',
            'virtualPageviewStep': 'Stap 1', //Vul hier de stap in. E.g. Stap 1, Stap 2, Stap 3, Bedankt
            'virtuelPageviewName': 'Donatie' // Vul hier de stapnaam in. E.g. Donatie, gegevens, adres, Bedankt
          });
        }
        return isValid;
      },
      changePeriodic() {
        this.$data.amount1    = (this.$data.machtigingType === 'M') ? formconfig.recurring_amount1          : formconfig.oneoff_amount1 ;
        this.$data.amount2    = (this.$data.machtigingType === 'M') ? formconfig.recurring_amount2          : formconfig.oneoff_amount2 ;
        this.$data.amount3    = (this.$data.machtigingType === 'M') ? formconfig.recurring_amount3          : formconfig.oneoff_amount3 ;
        this.$data.bedrag     = (this.$data.machtigingType === 'M') ? formconfig.recurring_suggested_amount : formconfig.oneoff_suggested_amount ;
        this.$data.min_amount = (this.$data.machtigingType === 'M') ? formconfig.recurring_min_amount       : formconfig.oneoff_min_amount ;
        this.$data.betaling   = (this.$data.machtigingType === 'M') ? 'EM' : 'ID';
        this.validate();
      },
      toggleCustomamount() {
        $('#input__customAmount').toggle();
      }
    }
  });

  Vue.component('step2', {
    template: `
        <div>
          <div class="form-group">
            <div class="input-group" v-bind:class="{ 'has-error': $v.geslacht.$error }">

              <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend">Aanhef</span>
              </div>

              <label for="prefix" class="sr-only">Aanhef:</label>
              <select id="prefix" class="form-control" v-model.trim="geslacht" @input="$v.geslacht.$touch()" name="honorific-prefix" tabindex="0">
                <option value="V">Mevrouw</option>
                <option value="M">Meneer</option>
                <option value="O">Anders</option>
              </select>

              <span class="help-block" v-if="$v.geslacht.$error && !$v.geslacht.required">Geslacht is verplicht</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-8" v-bind:class="{ 'has-error': $v.voornaam.$error }">
               <label class="sr-only" for="given-name">Voornaam</label>
              <input class="form-control" v-model.trim="voornaam" @input="$v.voornaam.$touch()" placeholder="Voornaam*" name="given-name" id="given-name">
               <span class="help-block" v-if="$v.voornaam.$error && !$v.voornaam.required">Voornaam is verplicht</span>
            </div>

            <div class="form-group col-md-4" v-bind:class="{ 'has-error': $v.initialen.$error }">
              <label class="sr-only" for="initials">Initialen</label>
              <input class="form-control" v-model.trim="initialen" @input="$v.initialen.$touch()" placeholder="Initialen" name="initials" id="initials">
               <span class="help-block" v-if="$v.initialen.$error && !$v.initialen.required">Initialen zijn verplicht</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
               <label class="sr-only" for="middle-name">Tussenvoegsel</label>
              <input class="form-control" v-model.trim="tussenvoegsel" @input="$v.tussenvoegsel.$touch()" placeholder="Tussenvoegsel" id="middle-name">
            </div>

            <div class="form-group col-md-8" v-bind:class="{ 'has-error': $v.achternaam.$error }">
               <label class="sr-only" for="surname">Achternaam</label>
              <input class="form-control" v-model.trim="achternaam" @input="$v.achternaam.$touch()" placeholder="Achternaam*" name="surname" id="surname">
               <span class="help-block" v-if="$v.achternaam.$error && !$v.achternaam.required">Achternaam is verplicht</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12" v-bind:class="{ 'has-error': $v.email.$error }">
               <label class="sr-only" for="email">Email</label>
              <input class="form-control" v-model.trim="email" @input="$v.email.$touch()" placeholder="Email*" name="email" id="email">
              <span class="help-block" v-if="$v.email.$error && !$v.email.required">Email is verplicht</span>
              <span class="help-block" v-if="$v.email.$error && !$v.email.email">Dit is geen valide e-mail adres</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12" v-bind:class="{ 'has-error': $v.telefoonnummer.$error }">
               <label class="sr-only" for="tel">Telefoonnummer</label>
              <input class="form-control" v-model.trim="telefoonnummer" @input="$v.telefoonnummer.$touch()" placeholder="Telefoonnummer" name="tel" id="tel">
               <!--<span class="help-block" v-if="$v.telefoonnummer.$error && !$v.telefoonnummer.required">Telefoonnummer is verplicht</span>-->
               <span class="help-block" v-if="$v.telefoonnummer.$error && !$v.telefoonnummer.numeric">Telefoonnummer moet een nummer zijn</span>
               <span class="help-block" v-if="$v.telefoonnummer.$error && $v.telefoonnummer.numeric && !$v.telefoonnummer.between">Telefoonnummer moet uit 10 cijfers bestaan</span>
            </div>
          </div>

          <div class="form-group" v-bind:class="{ 'has-error': $v.rekeningnummer.$error }" v-if="!ideal">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">IBAN</span>
              </div>
              <label class="sr-only" for="bankaccount">IBAN:</label>
              <input class="form-control" v-model.trim="rekeningnummer" @input="$v.rekeningnummer.$touch()" placeholder="*" id="bankaccount">
              <span class="help-block" v-if="$v.rekeningnummer.$error && !$v.rekeningnummer.required">Rekeningnummer is verplicht</span>
              <span class="help-block" v-if="$v.rekeningnummer.$error && $v.rekeningnummer.required && !$v.rekeningnummer.alphaNum">Rekeningnummer mag alleen letters en cijfers bevatten</span>
            </div>
          </div>
        </div>`,
    data() {
      return {
        initialen: '',
        voornaam: '',
        tussenvoegsel: '',
        achternaam: '',
        geslacht: '',
        email: '',
        telefoonnummer: '',
        rekeningnummer: '',
      };
    },
    validations: {
      initialen: {},
      voornaam: {
        required
      },
      tussenvoegsel: {},
      achternaam: {
        required
      },
      geslacht: {},
      email: {
        required,
        email
      },
      telefoonnummer: {
        numeric,
        minLength: minLength(10),
        maxLength: maxLength(10)
      },
      rekeningnummer: {
        required: requiredUnless(function() { return this.ideal; }),
        alphaNum
      },
      form: ['initialen', 'voornaam', 'tussenvoegsel', 'achternaam', 'geslacht', 'email', 'telefoonnummer', 'rekeningnummer']
    },
    methods: {
      validate() {
        this.$v.form.$reset();
        this.$v.form.$touch();
        var isValid = !this.$v.form.$invalid;
        this.$emit('on-validate', this.$data, isValid);
        if (isValid) {
          // Push step to tag manager
          dataLayer.push({
            'event': 'virtualPageViewDonatie',
            'virtualPageviewStep': 'Stap 2', //Vul hier de stap in. E.g. Stap 1, Stap 2, Stap 3, Bedankt
            'virtuelPageviewName': 'Gegevens' // Vul hier de stapnaam in. E.g. Donatie, gegevens, adres, Bedankt
          });
        }
        return isValid;
      }
    },
    props: ['ideal']

  });

  Vue.component('step3', {
    template: `
        <div>
          <div class="form-row">
            <div class="form-group col-md-5" v-bind:class="{ 'has-error': $v.postcode.$error }">
              <label class="sr-only" for="postal-code">Postcode</label>
              <input class="form-control" v-model.trim="postcode" @input="$v.postcode.$touch()" placeholder="Postcode*" name="postal-code" id="postal-code">
               <span class="help-block" v-if="$v.postcode.$error && !$v.postcode.required">Postcode is verplicht</span>
               <span class="help-block" v-if="$v.postcode.$error && $v.postcode.required && !$v.postcode.alphaNum">Postcode mag alleen letters en cijfers bevatten, geen spaties</span>
               <span class="help-block" v-if="$v.postcode.$error && $v.postcode.required && $v.postcode.alphaNum && !$v.postcode.between">Postcode moet in 0000AA formaat</span>
            </div>

            <div class="form-group col-md-4" v-bind:class="{ 'has-error': $v.huisnummer.$error }">
              <label class="sr-only" for="housenumber">Huisnummer</label>
              <input class="form-control" v-on:blur="fetchAddress()" v-model.trim="huisnummer" @input="$v.huisnummer.$touch()" placeholder="Huisnummer*" id="housenumber">
               <span class="help-block" v-if="$v.huisnummer.$error && !$v.huisnummer.required">Huisnummer is verplicht</span>
               <span class="help-block" v-if="$v.huisnummer.$error && $v.huisnummer.required && !$v.huisnummer.numeric">Huisnummer moet een nummer zijn</span>
            </div>

            <div class="form-group col-md-3" v-bind:class="{ 'has-error': $v.huisnummertoevoeging.$error }">
              <label class="sr-only" for="housenumberaddition">Toevoeging</label>
              <input class="form-control" v-model.trim="huisnummertoevoeging" @input="$v.huisnummertoevoeging.$touch()" placeholder="Toevoeging" id="housenumberaddition">
            </div>
          </div>

          <div class="form-group">
            <label class="sr-only" for="street">Straatnaam</label>
            <input class="form-control" v-model.trim="straat" placeholder="Straat" id="street">
          </div>

          <div class="form-group">
             <label class="sr-only" for="city">Woonplaats</label>
            <input class="form-control" v-model.trim="woonplaats" placeholder="Plaats" id="city">
          </div>

          <div class="form-group" v-bind:class="{ 'has-error': $v.landcode.$error }">
             <label class="sr-only" for="country-name">Land</label>
            <select class="form-control" v-model.trim="landcode" @input="$v.landcode.$touch()" id="country-name" name="country-name">
        			<option value="  "> Selecteer een land</option>
        			<option value="AF">AFGHANISTAN</option>
        			<option value="AL">ALBANIE</option>
        			<option value="DZ">ALGERIJE</option>
        			<option value="AD">ANDORRA</option>
        			<option value="AO">ANGOLA</option>
        			<option value="AG">ANTIGUA EN BARBUDA</option>
        			<option value="AR">ARGENTINIE</option>
        			<option value="AM">ARMENIE</option>
        			<option value="AB">ARUBA</option>
        			<option value="SH">ASCENSION</option>
        			<option value="AU">AUSTRALIE</option>
        			<option value="AZ">AZERBEIDZJAN</option>
        			<option value="BH">BAHREIN</option>
        			<option value="BD">BANGLADESH</option>
        			<option value="BB">BARBADOS</option>
        			<option value="BY">BELARUS</option>
        			<option value="BE">BELGIE</option>
        			<option value="BZ">BELIZE</option>
        			<option value="BJ">BENIN</option>
        			<option value="BM">BERMUDA</option>
        			<option value="BT">BHUTAN</option>
        			<option value="BO">BOLIVIA</option>
        			<option value="BA">BOSNIE-HERZEGOWINA</option>
        			<option value="BW">BOTSWANA</option>
        			<option value="BR">BRAZILIE</option>
        			<option value="VG">BRITSE MAAGDENEILANDEN</option>
        			<option value="BN">BRUNEI</option>
        			<option value="BG">BULGARIJE</option>
        			<option value="BF">BURKINA FASO</option>
        			<option value="BI">BURUNDI</option>
        			<option value="KH">CAMBODJA</option>
        			<option value="CA">CANADA</option>
        			<option value="KY">CAYMANEILANDEN</option>
        			<option value="CF">CENTRAALAFRIKAANSE REP.</option>
        			<option value="CL">CHILI</option>
        			<option value="CX">CHRISTMASEILAND</option>
        			<option value="CO">COLOMBIA</option>
        			<option value="CG">CONGO</option>
        			<option value="CR">COSTA RICA</option>
        			<option value="CU">CUBA</option>
        			<option value="CY">CYPRUS</option>
        			<option value="BS">DE BAHAMA'S</option>
        			<option value="KM">DE COMOREN</option>
        			<option value="PH">DE FILIPIJNEN</option>
        			<option value="MV">DE MALADIVEN</option>
        			<option value="DK">DENEMARKEN</option>
        			<option value="ZZ">DIVERSEN</option>
        			<option value="DJ">DJIBOUTI</option>
        			<option value="DM">DOMINICA</option>
        			<option value="DO">DOMINICAANSE REPUBLIEK</option>
        			<option value="DE">DUITSLAND</option>
        			<option value="EC">ECUADOR</option>
        			<option value="EG">EGYPTE</option>
        			<option value="SV">EL SALVADOR</option>
        			<option value="CQ">EQUATORIAAL GUINEA</option>
        			<option value="ER">ERITREA</option>
        			<option value="EE">ESTLAND</option>
        			<option value="ET">ETHIOPIE</option>
        			<option value="FK">FALKLANDEILANDEN</option>
        			<option value="FO">FAROE EILANDEN</option>
        			<option value="FJ">FIDJI-EILANDEN</option>
        			<option value="FL">FILIPIJNEN</option>
        			<option value="FI">FINLAND</option>
        			<option value="FR">FRANKRIJK</option>
        			<option value="GF">FRANS-GUYANA</option>
        			<option value="PF">FRANS-POLUNESIE</option>
        			<option value="TF">FRANSE ZUIDELIJKE EN Z-POOLGEB</option>
        			<option value="GA">GABON</option>
        			<option value="GM">GAMBIA</option>
        			<option value="GE">GEORGIE</option>
        			<option value="GH">GHANA</option>
        			<option value="GI">GIBRALTAR</option>
        			<option value="GD">GRENADA</option>
        			<option value="GR">GRIEKENLAND</option>
        			<option value="GL">GROENLAND</option>
        			<option value="GB">GROOT-BRITTANNIE</option>
        			<option value="GP">GUADELOUPE</option>
        			<option value="GT">GUATEMALA</option>
        			<option value="GN">GUINEE</option>
        			<option value="GW">GUINEE BISSAU</option>
        			<option value="GY">GUYANA</option>
        			<option value="HT">HAITI</option>
        			<option value="HN">HONDURAS</option>
        			<option value="HU">HONGARIJE</option>
        			<option value="HK">HONGKONG</option>
        			<option value="IE">IERLAND</option>
        			<option value="IS">IJSLAND</option>
        			<option value="IN">INDIA</option>
        			<option value="ID">INDONESIE</option>
        			<option value="IQ">IRAK</option>
        			<option value="IR">IRAN</option>
        			<option value="IL">ISRAEL</option>
        			<option value="IT">ITALIE</option>
        			<option value="CI">IVOORKUST</option>
        			<option value="JM">JAMAICA</option>
        			<option value="JP">JAPAN</option>
        			<option value="YE">JEMEN</option>
        			<option value="YU">JOEGOSLAVIE (KLEIN)</option>
        			<option value="JO">JORDANIE</option>
        			<option value="CV">KAAP VERDIE</option>
        			<option value="CM">KAMEROEN</option>
        			<option value="KZ">KAZACHSTAN</option>
        			<option value="KE">KENYA</option>
        			<option value="KI">KIRIBATI</option>
        			<option value="KW">KOEWEIT</option>
        			<option value="KR">KOREA</option>
        			<option value="HR">KROATIE</option>
        			<option value="KG">KYRGYZSTAN</option>
        			<option value="LA">LAOS</option>
        			<option value="LV">LETLAND</option>
        			<option value="LB">LIBANON</option>
        			<option value="LR">LIBERIA</option>
        			<option value="LY">LIBIE</option>
        			<option value="LI">LIECHTENSTEIN</option>
        			<option value="LT">LITOUWEN</option>
        			<option value="LU">LUXEMBURG</option>
        			<option value="MA">Macedonië</option>
        			<option value="MG">MADAGASKAR</option>
        			<option value="MW">MALAWI</option>
        			<option value="MY">MALEISIE</option>
        			<option value="ML">MALI</option>
        			<option value="MT">MALTA</option>
        			<option value="MN">MAROKKO</option>
        			<option value="MH">MARSHALLEILANDEN</option>
        			<option value="MQ">MARTINIQUE</option>
        			<option value="MR">MAURITANIE</option>
        			<option value="MU">MAURITIUS</option>
        			<option value="MX">MEXICO</option>
        			<option value="FM">MICRONESIA</option>
        			<option value="MD">MOLDAVIA</option>
        			<option value="MC">MONACO</option>
        			<option value="MO">MOZAMBIQUE</option>
        			<option value="MM">MYANMAR</option>
        			<option value="NA">NAMIBIE</option>
        			<option value="NR">NAURU</option>
        			<option value="NL">NEDERLAND</option>
        			<option value="AN">NEDERLANDSE ANTILLEN</option>
        			<option value="NP">NEPAL</option>
        			<option value="NI">NICARAGUA</option>
        			<option value="NZ">NIEUW-ZEELAND</option>
        			<option value="NG">NIGER</option>
        			<option value="NE">NIGERIA</option>
        			<option value="NO">NOORWEGEN</option>
        			<option value="UA">OEKRAINE</option>
        			<option value="UZ">OEZBEKISTAN</option>
        			<option value="OM">OMAN</option>
        			<option value="AT">OOSTENRIJK</option>
        			<option value="PK">PAKISTAN</option>
        			<option value="PA">PANAMA</option>
        			<option value="PG">PAPOEA-NIEUW-GUINEA</option>
        			<option value="PY">PARAGUAY</option>
        			<option value="PE">PERU</option>
        			<option value="PL">POLEN</option>
        			<option value="PT">PORTUGAL</option>
        			<option value="QA">QUATAR</option>
        			<option value="RO">ROEMENIE</option>
        			<option value="RW">RUANDA</option>
        			<option value="RU">RUSSISCHE FEDERATIE</option>
        			<option value="LC">SAINT LUCIA</option>
        			<option value="SM">SAN MARINO</option>
        			<option value="SA">SAUDI-ARABIE</option>
        			<option value="SN">SENEGAL</option>
        			<option value="SC">SEYCHELLEN</option>
        			<option value="SL">SIERRA LEONE</option>
        			<option value="SG">SINGAPORE</option>
        			<option value="VC">SINT VINCENT EN DE GRENADINEN</option>
        			<option value="SK">SLOWAKIJE</option>
        			<option value="SD">SOEDAN</option>
        			<option value="SB">SOLOMONEILANDEN</option>
        			<option value="ES">SPANJE</option>
        			<option value="LK">SRI LANKA</option>
        			<option value="SR">SURINAME</option>
        			<option value="SZ">SWAZILAND</option>
        			<option value="SY">SYRIE</option>
        			<option value="TJ">TADZJIKISTAN</option>
        			<option value="TA">TAIWAN</option>
        			<option value="TZ">TANZANIA</option>
        			<option value="TH">THAILAND</option>
        			<option value="TG">TOGO</option>
        			<option value="TO">TONGA</option>
        			<option value="TT">TRINIDAD EN TOBAGO</option>
        			<option value="TD">TSJAAD</option>
        			<option value="CZ">TSJECHIE</option>
        			<option value="TN">TUNESIE</option>
        			<option value="TR">TURKIJE</option>
        			<option value="TM">TURKMENISTAN</option>
        			<option value="TV">TUVALU</option>
        			<option value="UG">UGANDA</option>
        			<option value="UY">URUGUAY</option>
        			<option value="VA">VATICAANSE STAAT</option>
        			<option value="VE">VENEZUELA</option>
        			<option value="AE">VERENIGDE ARABISCHE EMIRATEN</option>
        			<option value="US">VERENIGDE STATEN VAN AMERIKA</option>
        			<option value="VN">VIETNAM</option>
        			<option value="CN">VOLKSREPUBLIEK CHINA</option>
        			<option value="KP">VOLKSREPUBLIEK KOREA</option>
        			<option value="WS">WEST-SOMOA</option>
        			<option value="ZR">ZAIRE</option>
        			<option value="ZM">ZAMBIA</option>
        			<option value="ZW">ZIMBABWE</option>
        			<option value="ZA">ZUID AFRIKA</option>
        			<option value="ZJ">ZUID JEMEN</option>
        			<option value="SE">ZWEDEN</option>
        			<option value="CH">ZWITSERLAND</option>
            </select>
             <span class="help-block" v-if="$v.landcode.$error && !$v.landcode.required">Land is verplicht</span>
          </div>
          <small>
            Ik machtig hierbij Greenpeace 
            <template v-if="frequency === 'M'">tot wederopzegging</template> 
            <template v-if="frequency === 'E'">éénmalig</template> 
            <template v-if="frequency === 'F'">12 maanden</template> 
            bovengenoemd bedrag van mijn rekening af te schrijven. <br/><br/>
          </small>
        </div>`,
    data() {
      return {
        straat: '',
        postcode: '',
        huisnummer: '',
        huisnummertoevoeging: '',
        woonplaats: '',
        landcode: 'NL'
      };
    },
    validations: {
      straat: {
        required
      },
      postcode: {
        minLength: minLength(6),
        maxLength: maxLength(6),
        required,
        alphaNum
      },
      huisnummer: {
        required,
        numeric
      },
      huisnummertoevoeging: {
        maxLength: maxLength(8)
      },
      woonplaats: {
        required
      },
      landcode: {
        required
      },
      form: ['straat', 'postcode', 'huisnummer', 'huisnummertoevoeging', 'woonplaats', 'landcode']
    },
    methods: {
      validate() {
        this.$v.form.$reset();
        this.$v.form.$touch();
        var isValid = !this.$v.form.$invalid;
        this.$emit('on-validate', this.$data, isValid);
        if(isValid){
          // Push step to tag manager
          dataLayer.push({
            'event': 'virtualPageViewDonatie',
            'virtualPageviewStep': 'Stap 3', //Vul hier de stap in. E.g. Stap 1, Stap 2, Stap 3, Bedankt
            'virtuelPageviewName': 'Adres' // Vul hier de stapnaam in. E.g. Donatie, gegevens, adres, Bedankt
          });
        }
        return isValid;
      },

      fetchAddress: function() {
        var zipcodeInput = document.getElementById('postal-code');
        var houseNoInput = document.getElementById('housenumber');
        var zipcodeValue = zipcodeInput.value;
        var houseNoValue = houseNoInput.value;

        Vue.http.interceptors.push((request, next) => {
          request.headers.set('x-api-key', 'P7TdlkQG4k4ppvVyAXmdD4TR9v5fW4YT8qv4TzOY');
          request.headers.set('Accept', 'application/hal+json');
          next();
        });

        this.$http.get('https://api.postcodeapi.nu/v2/addresses/?postcode='+ zipcodeValue +'&number=' + houseNoValue +'')
          .then(function (response) {
            let street = response.body._embedded.addresses[0].street;
            let city = response.body._embedded.addresses[0].city.label;

            this.populateFields(street, city);
          }, function () {

          });
      },

      populateFields: function(street, city) {
        var streetInput = document.getElementById('street');
        var cityInput = document.getElementById('city');

        streetInput.setAttribute('disabled', 'disabled');
        cityInput.setAttribute('disabled', 'disabled');

        this.straat = street;
        this.woonplaats = city;
      }
    },
    props: ['frequency'],
  });


  donationformVue = new Vue({
    el: '#app',
    data: {
      finalModel: {
        marketingcode: (formconfig.suggested_frequency[0] === 'M') ? formconfig.marketingcode_recurring : formconfig.marketingcode_oneoff ,
        literatuurcode: formconfig.literatuurcode,
        guid: '',
        betaling: (formconfig.suggested_frequency[0] === 'M') ? 'EM' : 'ID'
      },
      result: {
        msg: '',
        hasError: false
      },
      idealData: {
        initials: '',
        firstname: '',
        middlename: '',
        lastname: '',
        gender: '',
        birthday: '',
        street: '',
        housenumber: '',
        housenumberAddition: '',
        postcode: '',
        city: '',
        email: '',
        phonenumber: '',
        description: '',
        amount: 0,
        comment: '',
        issuersBank: '',
        clientIp: '',
        clientUserAgent: '',
        returnUrlSuccess: formconfig.returnpage,
        returnUrlCancel: formconfig.errorpage,
        returnUrlError: formconfig.errorpage,
        returnUrlReject: formconfig.errorpage,
        marketingCode: formconfig.marketingcode_oneoff,
        literatureCode: formconfig.literatuurcode,
        guid: null,
        countryId: null,
        accountNumber: null,
        subscriptionCode: null,
        subscriptionEndDate: null,
        subscriptionMonths: null
      },
      console: console,
    },
    methods: {
      onComplete: function() {
        var inputs = $('#app input');
        var buttons = $('#app button');
        $('.wizard-footer-right .wizard-btn').text('');
        $('.wizard-footer-right .wizard-btn').addClass('loader');
        this.disableFormElements(inputs);
        this.disableFormElements(buttons);
        $('.wizard-nav > li > a').addClass('disabled');
        if (this.finalModel.betaling === 'ID'){
          this.submitiDeal();
        }
        else{
          this.submit();
        }
      },

      onSucces: function() {
        // console.log(this.finalModel);
        var formBody = $('#Adres4');
        formBody.addClass('card');
        formBody.empty();
        formBody.append('<div class="card-body donation-card"></div>');
        var cardBody = $('.donation-card');
        cardBody.append('<h2 class="card-title">'+formconfig.thanktitle+'</h2>');
        cardBody.append('<p class="card-text">'+formconfig.thankdescription+'</p>');
        $('.wizard-footer-right .wizard-btn').removeClass('loader');
        $('.wizard-footer-right .wizard-btn').text('Afgerond');
        // Push step to tag manager
        dataLayer.push({
          'event': 'virtualPageViewDonatie',
          'virtualPageviewStep': 'Bedankt', //Vul hier de stap in. E.g. Stap 1, Stap 2, Stap 3, Bedankt
          'virtuelPageviewName': 'Bedankt' // Vul hier de stapnaam in. E.g. Donatie, gegevens, adres, Bedankt
        });
        /** Google Tag Manager E-commerce */

        // Build product array
        let gtm_products = [];

        gtm_products.push({
          'name': 'machtiging',
          'sku': this.finalModel.machtigingType,
          'category': 'donatie',
          'price': this.finalModel.bedrag,
          'quantity': 1
        });
        // Optional repeat for each additional product to fill gtm_products array


        /** Build an event send to the Datalayer, which needs to trigger the E-commerce transaction in the GTM backend
         *  Additional datalayer items are send to the datalayer and processed by the GTM as an transaction
         */
        // TODO make transactionId configurable
        dataLayer.push({
          'event': 'trackTrans',
          'transactionId': donationformVue.getGTMTransactionId(),
          'transactionAffiliation': '',
          'transactionTotal': this.finalModel.bedrag,
          'transactionTax': '',
          'transactionShipping': '',
          'transactionPaymentType': 'machtiging',
          'transactionCurrency': 'EUR',
          'transactionPromoCode': '',
          'transactionProducts': gtm_products
        });
        /** End Google Tag Manager E-commerce */
        let clangct=getUrlVars()['clangct'];
        if(clangct != undefined){
          $.ajax({
            url: '/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-conversion.js?clangct='+clangct,
            dataType: 'script',
          });
        }
      },

      onFailure: function() {
        var formBody = $('#Adres4');
        formBody.addClass('card');
        formBody.empty();
        formBody.append('<div class="card-body donation-card"></div>');
        var cardBody = $('.donation-card');
        cardBody.append('<h2 class="card-title">Sorry..</h2>');
        cardBody.append('<p class="card-text">Helaas gaat er iets mis met de donatieverwerking. Er wordt geen geld afgeschreven, probeer het later nog eens.</p>');
        $('.wizard-footer-right .wizard-btn').removeClass('loader');
        $('.wizard-footer-right .wizard-btn').text('Afgerond');
      },

      submit: function () {

        this.result.msg = '';
        this.result.hasError = false;
        this.finalModel.marketingcode = (this.finalModel.machtigingType === 'M') ? formconfig.marketingcode_recurring : formconfig.marketingcode_oneoff;
        $.ajax({
          method: 'POST',
          url: 'https://www.mygreenpeace.nl/GPN.RegistrerenApi/machtiging/register',
          data: JSON.stringify(this.finalModel),
          contentType: 'application/json; charset=utf-8',
          dataType: 'json',
          success: function() {
            donationformVue.onSucces();
          },
          error: function() {
            donationformVue.onFailure();
          }
        });
      },

      submitiDeal: function () {
        let clangct=getUrlVars()['clangct'];
        if(clangct != undefined){
          $.ajax({
            url: '/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-conversion.js?clangct='+clangct,
            dataType: 'script',
          });
        }
        this.result.msg = '';
        this.result.hasError = false;
        this.idealData.initials = this.finalModel.initialen;
        this.idealData.firstname = this.finalModel.voornaam;
        this.idealData.middlename = this.finalModel.tussenvoegsel;
        this.idealData.lastname = this.finalModel.achternaam;
        this.idealData.gender = this.finalModel.geslacht;
        this.idealData.email = this.finalModel.email;
        this.idealData.phonenumber = this.finalModel.telefoonnummer;
        this.idealData.description = 'Eenmalige donatie Greenpeace tnv ' + this.finalModel.voornaam + ' ' + this.finalModel.achternaam;
        this.idealData.amount = this.finalModel.bedrag;
        $.ajax({
          method: 'POST',
          url: 'https://www.mygreenpeace.nl/GPN.RegistrerenApi/payment/ideal',
          data: JSON.stringify(this.idealData),
          contentType: 'application/json; charset=utf-8',
          dataType: 'json',
          success: function(result) {
            window.location.href = result.transaction.redirectUrl;
          },
          error: function() {
            donationformVue.onFailure();
          }
        });
      },

      disableFormElements: function (elements) {
        elements.each( function() {
          this.setAttribute('disabled', 'true');
        });
      },

      validateStep(name) {
        var refToValidate = this.$refs[name];
        return refToValidate.validate();
      },

      mergePartialModels(model, isValid) {
        if (isValid) {
          // merging each step model into the final model
          this.finalModel = Object.assign({}, this.finalModel, model);
          this.isiDeal();
          this.$forceUpdate();
        }
      },
      isiDeal() {
        if (typeof this.$refs.step1 != 'undefined'){
          return this.$refs.step1._data.betaling === 'ID';
        }
        else{
          return this.finalModel.betaling === 'ID';
        }
      },

      getFrequency() {
        if (url_vars.suggested_frequency === 'F') {
          return 'F';
        }
        if (typeof this.$refs.step1 !== 'undefined'){
          return this.$refs.step1._data.machtigingType;
        }
        else{
          return '';
        }
      },

      getGTMTransactionId() {
        // Math.random should be unique because of its seeding algorithm.
        // Convert it to base 36 (numbers + letters), and grab the first 9 characters
        // after the decimal.
        return '_' + [this.finalModel.marketingcode] + '_' + Math.random().toString(36).substr(2, 9);
      }
    }
  });
});

