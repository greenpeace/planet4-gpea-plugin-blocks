"use strict";$(document).ready(function(){function e(){for(var t,o=[],n=decodeURIComponent(window.location.href.split("#")[0]).slice(window.location.href.indexOf("?")+1).split("&"),e=0;e<n.length;e++)t=n[e].split("="),o.push(t[0]),o[t[0]]=t[1];return o}var t=e().clangct;null!=t&&$.ajax({url:"/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-landing.js?clangct="+t,dataType:"script"});var o={suggested_frequency:e().per,marketingcode:e().mcode,literatuurcode:e().lcode,drplus:e().drplus,min_amount:e().min,suggested_amount:e().pref,override_amount:e().over};$.each(o,function(t,o){if(void 0!==o)switch(t){case"suggested_frequency":switch(o){case"E":formconfig.allow_frequency_override="false",formconfig.suggested_frequency=["E","Eenmalig"];break;case"M":formconfig.allow_frequency_override="false",formconfig.suggested_frequency=["M","Maandelijks"];break;case"F":formconfig.allow_frequency_override="false",formconfig.suggested_frequency=["M","maandelijks voor 12 maanden"];break;default:formconfig.suggested_frequency=["M","Maandelijks"]}break;case"marketingcode":if("E"===formconfig.suggested_frequency[0]){formconfig.marketingcode_oneoff=o}else{formconfig.marketingcode_recurring=o}break;case"drplus":"true"===o&&("E"===formconfig.suggested_frequency[0]?(formconfig.oneoff_amount1=formconfig.drplus_amount1,formconfig.oneoff_amount2=formconfig.drplus_amount2,formconfig.oneoff_amount3=formconfig.drplus_amount3,formconfig.oneoff_suggested_amount=formconfig.drplus_amount2):(formconfig.recurring_amount1=formconfig.drplus_amount1,formconfig.recurring_amount2=formconfig.drplus_amount2,formconfig.recurring_amount3=formconfig.drplus_amount3,formconfig.recurring_suggested_amount=formconfig.drplus_amount2));break;case"min_amount":(formconfig.min_amount=o)>Math.min(formconfig.oneoff_amount1,formconfig.recurring_amount1)&&(formconfig.min_amount=Math.min(formconfig.oneoff_amount1,formconfig.recurring_amount1));break;case"literatuurcode":formconfig.literatuurcode=o;break;case"suggested_amount":var n="oneoff_amount"+o,e="recurring_amount"+o;formconfig.recurring_suggested_amount=formconfig[e],formconfig.oneoff_suggested_amount=formconfig[n];break;case"override_amount":"E"===formconfig.suggested_frequency[0]?(formconfig.oneoff_amount1=o,formconfig.oneoff_suggested_amount=o):(formconfig.recurring_amount1=o,formconfig.recurring_suggested_amount=o)}}),"F"===formconfig.suggested_frequency[0]&&(formconfig.allow_frequency_override="false",formconfig.suggested_frequency=["M","maandelijks voor 12 maanden"]),Vue.use(window.vuelidate.default);var n=window.validators,i=n.required,a=n.between,r=n.minLength,l=n.maxLength,s=n.email,u=n.numeric,p=n.alphaNum,c=n.requiredUnless;Vue.use(VueFormWizard),Vue.config.devtools=!0,Vue.component("step1",{template:'\n        <div>\n          <div class="form-group" v-bind:class="{ \'has-error\': $v.machtigingType }">\n            <label for="machtigingType" v-if="formconfig.allow_frequency_override == \'true\'">Ja ik steun Greenpeace:</label>\n            <label for="machtigingType" v-else>\n            \tJa ik steun Greenpeace <strong>{{ formconfig.suggested_frequency[1] }}</strong>:\n            </label>\n            \n            <select id="machtigingType" class="form-control" v-model.trim="machtigingType" @input="$v.machtigingType.$touch()" v-show="formconfig.allow_frequency_override == \'true\'" v-on:change="changePeriodic">\n              <option value="E">Eenmalig</option>\n              <option value="M">Maandelijks</option>\n            </select>\n            <span class="help-block" v-if="$v.machtigingType.$error && !$v.machtigingType.required">Periodiek is verplicht</span>\n          </div>\n          \n\t\t<fieldset>\n\t\t<legend class="sr-only">Bedrag</legend>\n\t\t <div class="form-row">\n            <div class="form-group col-md-12" v-bind:class="{ \'has-error\': $v.bedrag.$error }">\n              <label for="amountList">Ik geef:</label>\n              <div id="amountList" class="radio-list" role="radiogroup">\n                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="transaction-amount" id="bedrag1" role="radio" v-bind:value="amount1">\n                <label class="form-check-label form-control left" for="bedrag1">&euro;{{ amount1 }}</label>\n\n                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="transaction-amount" id="bedrag2" role="radio" v-bind:value="amount2" checked="checked" tabindex="0">\n                <label class="form-check-label form-control" for="bedrag2">&euro;{{ amount2 }}</label>\n\n                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="transaction-amount" id="bedrag3" role="radio" v-bind:value="amount3">\n                <label class="form-check-label form-control" for="bedrag3">&euro;{{ amount3 }}</label>\n              </div>\n            </div>\n\n            <div class="form-group col-md-12" v-bind:class="{ \'has-error\': $v.bedrag.$error }">\n              <label for="customAmount">Ander bedrag:</label>\n              <div class="input-group">\n                <div class="input-group-prepend">\n                  <div class="input-group-text">&euro;</div>\n                </div>\n                <input id="customAmount" class="form-control" v-model.trim="bedrag" @input="$v.bedrag.$touch()" name="transaction-amount">\n                <span class="help-block" v-if="$v.bedrag.$error && !$v.bedrag.required">Bedrag is verplicht</span>\n                <span class="help-block" v-if="$v.bedrag.$error && $v.bedrag.required && !$v.bedrag.numeric">Bedrag moet een nummer zijn</span>\n                <span class="help-block" v-if="$v.bedrag.$error && $v.bedrag.required && $v.bedrag.numeric && !$v.bedrag.between">Het minimale donatiebedrag is {{ formconfig.min_amount }} euro</span>\n              </div>\n            </div>\n          </div>\n\t\t</fieldset>\n         \n\t  \n\t  <fieldset v-if="machtigingType ===\'E\'">\n\t  <legend class="sr-only">Betalingsmethode</legend>\n\t\t   <div class="form-row">\n\t\t\t<div class="form-group col-md-12" v-bind:class="{ \'has-error\': $v.betaling.$error }">\n\t\t\t  <label for="paymentMethods">Betalingswijze:</label>\n\t\t\t  <div id="paymentMethods" class="radio-list" role="radiogroup">\n\t\t\t\t<input class="form-check-input" v-model.trim="betaling" type="radio" name="ideal" id="ideal" value="ID" checked="checked" tabindex="0" role="radio"\n\t\t\t\tv-on:click="donationformVue.validateStep(\'step1\');">\n\t\t\t\t<label class="form-check-label form-control left" for="ideal">iDeal</label>\n\t\t\t\t<input class="form-check-input" v-model.trim="betaling" type="radio" name="machtiging" id="machtiging" value="EM" role="radio" v-on:click="donationformVue.validateStep(\'step1\');">\n\t\t\t\t<label class="form-check-label form-control" for="machtiging">Eenmalige machtiging</label>\n\t\t\t  </div>\n\t\t\t</div> \n\t\t  </div>\n\t  </fieldset>\n          \n           </div>',data:function(){return{machtigingType:formconfig.suggested_frequency[0],amount1:"M"===formconfig.suggested_frequency[0]?formconfig.recurring_amount1:formconfig.oneoff_amount1,amount2:"M"===formconfig.suggested_frequency[0]?formconfig.recurring_amount2:formconfig.oneoff_amount2,amount3:"M"===formconfig.suggested_frequency[0]?formconfig.recurring_amount3:formconfig.oneoff_amount3,bedrag:"M"===formconfig.suggested_frequency[0]?formconfig.recurring_suggested_amount:formconfig.oneoff_suggested_amount,betaling:"M"===formconfig.suggested_frequency[0]?"EM":"ID"}},validations:{machtigingType:{required:i},bedrag:{required:i,numeric:u,between:a(formconfig.min_amount,999)},betaling:{required:i},form:["machtigingType","bedrag","betaling"]},methods:{validate:function(){this.$v.form.$reset(),this.$v.form.$touch();var t=!this.$v.form.$invalid;return this.$emit("on-validate",this.$data,t),t&&dataLayer.push({event:"virtualPageViewDonatie",virtualPageviewStep:"Stap 1",virtuelPageviewName:"Donatie"}),t},changePeriodic:function(){this.$data.amount1="M"===this.$data.machtigingType?formconfig.recurring_amount1:formconfig.oneoff_amount1,this.$data.amount2="M"===this.$data.machtigingType?formconfig.recurring_amount2:formconfig.oneoff_amount2,this.$data.amount3="M"===this.$data.machtigingType?formconfig.recurring_amount3:formconfig.oneoff_amount3,this.$data.bedrag="M"===this.$data.machtigingType?formconfig.recurring_suggested_amount:formconfig.oneoff_suggested_amount,this.$data.min_amount="M"===this.$data.machtigingType?formconfig.recurring_min_amount:formconfig.oneoff_min_amount,this.$data.betaling="M"===this.$data.machtigingType?"EM":"ID",this.validate()}}}),Vue.component("step2",{template:'\n        <div>\n          <div class="form-group">\n            <div class="input-group" v-bind:class="{ \'has-error\': $v.geslacht.$error }">\n\n              <div class="input-group-prepend">\n                  <span class="input-group-text" id="inputGroupPrepend">Aanhef</span>\n              </div>\n\n              <label for="prefix" class="sr-only">Aanhef:</label>\n              <select id="prefix" class="form-control" v-model.trim="geslacht" @input="$v.geslacht.$touch()" name="honorific-prefix" tabindex="0">\n                <option value="V">Mevrouw</option>\n                <option value="M">Meneer</option>\n                <option value="O">Anders</option>\n              </select>\n\n              <span class="help-block" v-if="$v.geslacht.$error && !$v.geslacht.required">Geslacht is verplicht</span>\n            </div>\n          </div>\n\n          <div class="form-row">\n            <div class="form-group col-md-8" v-bind:class="{ \'has-error\': $v.voornaam.$error }">\n               <label class="sr-only" for="given-name">Voornaam</label>\n              <input class="form-control" v-model.trim="voornaam" @input="$v.voornaam.$touch()" placeholder="Voornaam*" name="given-name" id="given-name">\n               <span class="help-block" v-if="$v.voornaam.$error && !$v.voornaam.required">Voornaam is verplicht</span>\n            </div>\n\n            <div class="form-group col-md-4" v-bind:class="{ \'has-error\': $v.initialen.$error }">\n              <label class="sr-only" for="initials">Initialen</label>\n              <input class="form-control" v-model.trim="initialen" @input="$v.initialen.$touch()" placeholder="Initialen" name="initials" id="initials">\n               <span class="help-block" v-if="$v.initialen.$error && !$v.initialen.required">Initialen zijn verplicht</span>\n            </div>\n          </div>\n\n          <div class="form-row">\n            <div class="form-group col-md-4">\n               <label class="sr-only" for="middle-name">Tussenvoegsel</label>\n              <input class="form-control" v-model.trim="tussenvoegsel" @input="$v.tussenvoegsel.$touch()" placeholder="Tussenvoegsel" id="middle-name">\n            </div>\n\n            <div class="form-group col-md-8" v-bind:class="{ \'has-error\': $v.achternaam.$error }">\n               <label class="sr-only" for="surname">Achternaam</label>\n              <input class="form-control" v-model.trim="achternaam" @input="$v.achternaam.$touch()" placeholder="Achternaam*" name="surname" id="surname">\n               <span class="help-block" v-if="$v.achternaam.$error && !$v.achternaam.required">Achternaam is verplicht</span>\n            </div>\n          </div>\n\n          <div class="form-row">\n            <div class="form-group col-md-12" v-bind:class="{ \'has-error\': $v.email.$error }">\n               <label class="sr-only" for="email">Email</label>\n              <input class="form-control" v-model.trim="email" @input="$v.email.$touch()" placeholder="Email*" name="email" id="email">\n              <span class="help-block" v-if="$v.email.$error && !$v.email.required">Email is verplicht</span>\n              <span class="help-block" v-if="$v.email.$error && !$v.email.email">Dit is geen valide e-mail adres</span>\n            </div>\n          </div>\n\n          <div class="form-row">\n            <div class="form-group col-md-12" v-bind:class="{ \'has-error\': $v.telefoonnummer.$error }">\n               <label class="sr-only" for="tel">Telefoonnummer</label>\n              <input class="form-control" v-model.trim="telefoonnummer" @input="$v.telefoonnummer.$touch()" placeholder="Telefoonnummer" name="tel" id="tel">\n               \x3c!--<span class="help-block" v-if="$v.telefoonnummer.$error && !$v.telefoonnummer.required">Telefoonnummer is verplicht</span>--\x3e\n               <span class="help-block" v-if="$v.telefoonnummer.$error && !$v.telefoonnummer.numeric">Telefoonnummer moet een nummer zijn</span>\n               <span class="help-block" v-if="$v.telefoonnummer.$error && $v.telefoonnummer.numeric && !$v.telefoonnummer.between">Telefoonnummer moet uit 10 cijfers bestaan</span>\n            </div>\n          </div>\n\n          <div class="form-group" v-bind:class="{ \'has-error\': $v.rekeningnummer.$error }" v-if="!ideal">\n            <div class="input-group">\n              <div class="input-group-prepend">\n                <span class="input-group-text" id="inputGroupPrepend">IBAN</span>\n              </div>\n              <label class="sr-only" for="bankaccount">IBAN:</label>\n              <input class="form-control" v-model.trim="rekeningnummer" @input="$v.rekeningnummer.$touch()" placeholder="*" id="bankaccount">\n              <span class="help-block" v-if="$v.rekeningnummer.$error && !$v.rekeningnummer.required">Rekeningnummer is verplicht</span>\n              <span class="help-block" v-if="$v.rekeningnummer.$error && $v.rekeningnummer.required && !$v.rekeningnummer.alphaNum">Rekeningnummer mag alleen letters en cijfers bevatten</span>\n            </div>\n          </div>\n        </div>',data:function(){return{initialen:"",voornaam:"",tussenvoegsel:"",achternaam:"",geslacht:"",email:"",telefoonnummer:"",rekeningnummer:""}},validations:{initialen:{},voornaam:{required:i},tussenvoegsel:{},achternaam:{required:i},geslacht:{},email:{required:i,email:s},telefoonnummer:{numeric:u,minLength:r(10),maxLength:l(10)},rekeningnummer:{required:c(function(){return this.ideal}),alphaNum:p},form:["initialen","voornaam","tussenvoegsel","achternaam","geslacht","email","telefoonnummer","rekeningnummer"]},methods:{validate:function(){this.$v.form.$reset(),this.$v.form.$touch();var t=!this.$v.form.$invalid;return this.$emit("on-validate",this.$data,t),t&&dataLayer.push({event:"virtualPageViewDonatie",virtualPageviewStep:"Stap 2",virtuelPageviewName:"Gegevens"}),t}},props:["ideal"]}),Vue.component("step3",{template:'\n        <div>\n          <div class="form-row">\n            <div class="form-group col-md-5" v-bind:class="{ \'has-error\': $v.postcode.$error }">\n              <label class="sr-only" for="postal-code">Postcode</label>\n              <input class="form-control" v-model.trim="postcode" @input="$v.postcode.$touch()" placeholder="Postcode*" name="postal-code" id="postal-code">\n               <span class="help-block" v-if="$v.postcode.$error && !$v.postcode.required">Postcode is verplicht</span>\n               <span class="help-block" v-if="$v.postcode.$error && $v.postcode.required && !$v.postcode.alphaNum">Postcode mag alleen letters en cijfers bevatten, geen spaties</span>\n               <span class="help-block" v-if="$v.postcode.$error && $v.postcode.required && $v.postcode.alphaNum && !$v.postcode.between">Postcode moet in 0000AA formaat</span>\n            </div>\n\n            <div class="form-group col-md-4" v-bind:class="{ \'has-error\': $v.huisnummer.$error }">\n              <label class="sr-only" for="housenumber">Huisnummer</label>\n              <input class="form-control" v-on:blur="fetchAddress()" v-model.trim="huisnummer" @input="$v.huisnummer.$touch()" placeholder="Huisnummer*" id="housenumber">\n               <span class="help-block" v-if="$v.huisnummer.$error && !$v.huisnummer.required">Huisnummer is verplicht</span>\n               <span class="help-block" v-if="$v.huisnummer.$error && $v.huisnummer.required && !$v.huisnummer.numeric">Huisnummer moet een nummer zijn</span>\n            </div>\n\n            <div class="form-group col-md-3" v-bind:class="{ \'has-error\': $v.huisnummertoevoeging.$error }">\n              <label class="sr-only" for="housenumberaddition">Toevoeging</label>\n              <input class="form-control" v-model.trim="huisnummertoevoeging" @input="$v.huisnummertoevoeging.$touch()" placeholder="Toevoeging" id="housenumberaddition">\n            </div>\n          </div>\n\n          <div class="form-group">\n            <label class="sr-only" for="street">Straatnaam</label>\n            <input class="form-control" v-model.trim="straat" placeholder="Straat" id="street">\n          </div>\n\n          <div class="form-group">\n             <label class="sr-only" for="city">Woonplaats</label>\n            <input class="form-control" v-model.trim="woonplaats" placeholder="Plaats" id="city">\n          </div>\n\n          <div class="form-group" v-bind:class="{ \'has-error\': $v.landcode.$error }">\n             <label class="sr-only" for="country-name">Land</label>\n            <select class="form-control" v-model.trim="landcode" @input="$v.landcode.$touch()" id="country-name" name="country-name">\n        \t\t\t<option value="  "> Selecteer een land</option>\n        \t\t\t<option value="AF">AFGHANISTAN</option>\n        \t\t\t<option value="AL">ALBANIE</option>\n        \t\t\t<option value="DZ">ALGERIJE</option>\n        \t\t\t<option value="AD">ANDORRA</option>\n        \t\t\t<option value="AO">ANGOLA</option>\n        \t\t\t<option value="AG">ANTIGUA EN BARBUDA</option>\n        \t\t\t<option value="AR">ARGENTINIE</option>\n        \t\t\t<option value="AM">ARMENIE</option>\n        \t\t\t<option value="AB">ARUBA</option>\n        \t\t\t<option value="SH">ASCENSION</option>\n        \t\t\t<option value="AU">AUSTRALIE</option>\n        \t\t\t<option value="AZ">AZERBEIDZJAN</option>\n        \t\t\t<option value="BH">BAHREIN</option>\n        \t\t\t<option value="BD">BANGLADESH</option>\n        \t\t\t<option value="BB">BARBADOS</option>\n        \t\t\t<option value="BY">BELARUS</option>\n        \t\t\t<option value="BE">BELGIE</option>\n        \t\t\t<option value="BZ">BELIZE</option>\n        \t\t\t<option value="BJ">BENIN</option>\n        \t\t\t<option value="BM">BERMUDA</option>\n        \t\t\t<option value="BT">BHUTAN</option>\n        \t\t\t<option value="BO">BOLIVIA</option>\n        \t\t\t<option value="BA">BOSNIE-HERZEGOWINA</option>\n        \t\t\t<option value="BW">BOTSWANA</option>\n        \t\t\t<option value="BR">BRAZILIE</option>\n        \t\t\t<option value="VG">BRITSE MAAGDENEILANDEN</option>\n        \t\t\t<option value="BN">BRUNEI</option>\n        \t\t\t<option value="BG">BULGARIJE</option>\n        \t\t\t<option value="BF">BURKINA FASO</option>\n        \t\t\t<option value="BI">BURUNDI</option>\n        \t\t\t<option value="KH">CAMBODJA</option>\n        \t\t\t<option value="CA">CANADA</option>\n        \t\t\t<option value="KY">CAYMANEILANDEN</option>\n        \t\t\t<option value="CF">CENTRAALAFRIKAANSE REP.</option>\n        \t\t\t<option value="CL">CHILI</option>\n        \t\t\t<option value="CX">CHRISTMASEILAND</option>\n        \t\t\t<option value="CO">COLOMBIA</option>\n        \t\t\t<option value="CG">CONGO</option>\n        \t\t\t<option value="CR">COSTA RICA</option>\n        \t\t\t<option value="CU">CUBA</option>\n        \t\t\t<option value="CY">CYPRUS</option>\n        \t\t\t<option value="BS">DE BAHAMA\'S</option>\n        \t\t\t<option value="KM">DE COMOREN</option>\n        \t\t\t<option value="PH">DE FILIPIJNEN</option>\n        \t\t\t<option value="MV">DE MALADIVEN</option>\n        \t\t\t<option value="DK">DENEMARKEN</option>\n        \t\t\t<option value="ZZ">DIVERSEN</option>\n        \t\t\t<option value="DJ">DJIBOUTI</option>\n        \t\t\t<option value="DM">DOMINICA</option>\n        \t\t\t<option value="DO">DOMINICAANSE REPUBLIEK</option>\n        \t\t\t<option value="DE">DUITSLAND</option>\n        \t\t\t<option value="EC">ECUADOR</option>\n        \t\t\t<option value="EG">EGYPTE</option>\n        \t\t\t<option value="SV">EL SALVADOR</option>\n        \t\t\t<option value="CQ">EQUATORIAAL GUINEA</option>\n        \t\t\t<option value="ER">ERITREA</option>\n        \t\t\t<option value="EE">ESTLAND</option>\n        \t\t\t<option value="ET">ETHIOPIE</option>\n        \t\t\t<option value="FK">FALKLANDEILANDEN</option>\n        \t\t\t<option value="FO">FAROE EILANDEN</option>\n        \t\t\t<option value="FJ">FIDJI-EILANDEN</option>\n        \t\t\t<option value="FL">FILIPIJNEN</option>\n        \t\t\t<option value="FI">FINLAND</option>\n        \t\t\t<option value="FR">FRANKRIJK</option>\n        \t\t\t<option value="GF">FRANS-GUYANA</option>\n        \t\t\t<option value="PF">FRANS-POLUNESIE</option>\n        \t\t\t<option value="TF">FRANSE ZUIDELIJKE EN Z-POOLGEB</option>\n        \t\t\t<option value="GA">GABON</option>\n        \t\t\t<option value="GM">GAMBIA</option>\n        \t\t\t<option value="GE">GEORGIE</option>\n        \t\t\t<option value="GH">GHANA</option>\n        \t\t\t<option value="GI">GIBRALTAR</option>\n        \t\t\t<option value="GD">GRENADA</option>\n        \t\t\t<option value="GR">GRIEKENLAND</option>\n        \t\t\t<option value="GL">GROENLAND</option>\n        \t\t\t<option value="GB">GROOT-BRITTANNIE</option>\n        \t\t\t<option value="GP">GUADELOUPE</option>\n        \t\t\t<option value="GT">GUATEMALA</option>\n        \t\t\t<option value="GN">GUINEE</option>\n        \t\t\t<option value="GW">GUINEE BISSAU</option>\n        \t\t\t<option value="GY">GUYANA</option>\n        \t\t\t<option value="HT">HAITI</option>\n        \t\t\t<option value="HN">HONDURAS</option>\n        \t\t\t<option value="HU">HONGARIJE</option>\n        \t\t\t<option value="HK">HONGKONG</option>\n        \t\t\t<option value="IE">IERLAND</option>\n        \t\t\t<option value="IS">IJSLAND</option>\n        \t\t\t<option value="IN">INDIA</option>\n        \t\t\t<option value="ID">INDONESIE</option>\n        \t\t\t<option value="IQ">IRAK</option>\n        \t\t\t<option value="IR">IRAN</option>\n        \t\t\t<option value="IL">ISRAEL</option>\n        \t\t\t<option value="IT">ITALIE</option>\n        \t\t\t<option value="CI">IVOORKUST</option>\n        \t\t\t<option value="JM">JAMAICA</option>\n        \t\t\t<option value="JP">JAPAN</option>\n        \t\t\t<option value="YE">JEMEN</option>\n        \t\t\t<option value="YU">JOEGOSLAVIE (KLEIN)</option>\n        \t\t\t<option value="JO">JORDANIE</option>\n        \t\t\t<option value="CV">KAAP VERDIE</option>\n        \t\t\t<option value="CM">KAMEROEN</option>\n        \t\t\t<option value="KZ">KAZACHSTAN</option>\n        \t\t\t<option value="KE">KENYA</option>\n        \t\t\t<option value="KI">KIRIBATI</option>\n        \t\t\t<option value="KW">KOEWEIT</option>\n        \t\t\t<option value="KR">KOREA</option>\n        \t\t\t<option value="HR">KROATIE</option>\n        \t\t\t<option value="KG">KYRGYZSTAN</option>\n        \t\t\t<option value="LA">LAOS</option>\n        \t\t\t<option value="LV">LETLAND</option>\n        \t\t\t<option value="LB">LIBANON</option>\n        \t\t\t<option value="LR">LIBERIA</option>\n        \t\t\t<option value="LY">LIBIE</option>\n        \t\t\t<option value="LI">LIECHTENSTEIN</option>\n        \t\t\t<option value="LT">LITOUWEN</option>\n        \t\t\t<option value="LU">LUXEMBURG</option>\n        \t\t\t<option value="MA">Macedonië</option>\n        \t\t\t<option value="MG">MADAGASKAR</option>\n        \t\t\t<option value="MW">MALAWI</option>\n        \t\t\t<option value="MY">MALEISIE</option>\n        \t\t\t<option value="ML">MALI</option>\n        \t\t\t<option value="MT">MALTA</option>\n        \t\t\t<option value="MN">MAROKKO</option>\n        \t\t\t<option value="MH">MARSHALLEILANDEN</option>\n        \t\t\t<option value="MQ">MARTINIQUE</option>\n        \t\t\t<option value="MR">MAURITANIE</option>\n        \t\t\t<option value="MU">MAURITIUS</option>\n        \t\t\t<option value="MX">MEXICO</option>\n        \t\t\t<option value="FM">MICRONESIA</option>\n        \t\t\t<option value="MD">MOLDAVIA</option>\n        \t\t\t<option value="MC">MONACO</option>\n        \t\t\t<option value="MO">MOZAMBIQUE</option>\n        \t\t\t<option value="MM">MYANMAR</option>\n        \t\t\t<option value="NA">NAMIBIE</option>\n        \t\t\t<option value="NR">NAURU</option>\n        \t\t\t<option value="NL">NEDERLAND</option>\n        \t\t\t<option value="AN">NEDERLANDSE ANTILLEN</option>\n        \t\t\t<option value="NP">NEPAL</option>\n        \t\t\t<option value="NI">NICARAGUA</option>\n        \t\t\t<option value="NZ">NIEUW-ZEELAND</option>\n        \t\t\t<option value="NG">NIGER</option>\n        \t\t\t<option value="NE">NIGERIA</option>\n        \t\t\t<option value="NO">NOORWEGEN</option>\n        \t\t\t<option value="UA">OEKRAINE</option>\n        \t\t\t<option value="UZ">OEZBEKISTAN</option>\n        \t\t\t<option value="OM">OMAN</option>\n        \t\t\t<option value="AT">OOSTENRIJK</option>\n        \t\t\t<option value="PK">PAKISTAN</option>\n        \t\t\t<option value="PA">PANAMA</option>\n        \t\t\t<option value="PG">PAPOEA-NIEUW-GUINEA</option>\n        \t\t\t<option value="PY">PARAGUAY</option>\n        \t\t\t<option value="PE">PERU</option>\n        \t\t\t<option value="PL">POLEN</option>\n        \t\t\t<option value="PT">PORTUGAL</option>\n        \t\t\t<option value="QA">QUATAR</option>\n        \t\t\t<option value="RO">ROEMENIE</option>\n        \t\t\t<option value="RW">RUANDA</option>\n        \t\t\t<option value="RU">RUSSISCHE FEDERATIE</option>\n        \t\t\t<option value="LC">SAINT LUCIA</option>\n        \t\t\t<option value="SM">SAN MARINO</option>\n        \t\t\t<option value="SA">SAUDI-ARABIE</option>\n        \t\t\t<option value="SN">SENEGAL</option>\n        \t\t\t<option value="SC">SEYCHELLEN</option>\n        \t\t\t<option value="SL">SIERRA LEONE</option>\n        \t\t\t<option value="SG">SINGAPORE</option>\n        \t\t\t<option value="VC">SINT VINCENT EN DE GRENADINEN</option>\n        \t\t\t<option value="SK">SLOWAKIJE</option>\n        \t\t\t<option value="SD">SOEDAN</option>\n        \t\t\t<option value="SB">SOLOMONEILANDEN</option>\n        \t\t\t<option value="ES">SPANJE</option>\n        \t\t\t<option value="LK">SRI LANKA</option>\n        \t\t\t<option value="SR">SURINAME</option>\n        \t\t\t<option value="SZ">SWAZILAND</option>\n        \t\t\t<option value="SY">SYRIE</option>\n        \t\t\t<option value="TJ">TADZJIKISTAN</option>\n        \t\t\t<option value="TA">TAIWAN</option>\n        \t\t\t<option value="TZ">TANZANIA</option>\n        \t\t\t<option value="TH">THAILAND</option>\n        \t\t\t<option value="TG">TOGO</option>\n        \t\t\t<option value="TO">TONGA</option>\n        \t\t\t<option value="TT">TRINIDAD EN TOBAGO</option>\n        \t\t\t<option value="TD">TSJAAD</option>\n        \t\t\t<option value="CZ">TSJECHIE</option>\n        \t\t\t<option value="TN">TUNESIE</option>\n        \t\t\t<option value="TR">TURKIJE</option>\n        \t\t\t<option value="TM">TURKMENISTAN</option>\n        \t\t\t<option value="TV">TUVALU</option>\n        \t\t\t<option value="UG">UGANDA</option>\n        \t\t\t<option value="UY">URUGUAY</option>\n        \t\t\t<option value="VA">VATICAANSE STAAT</option>\n        \t\t\t<option value="VE">VENEZUELA</option>\n        \t\t\t<option value="AE">VERENIGDE ARABISCHE EMIRATEN</option>\n        \t\t\t<option value="US">VERENIGDE STATEN VAN AMERIKA</option>\n        \t\t\t<option value="VN">VIETNAM</option>\n        \t\t\t<option value="CN">VOLKSREPUBLIEK CHINA</option>\n        \t\t\t<option value="KP">VOLKSREPUBLIEK KOREA</option>\n        \t\t\t<option value="WS">WEST-SOMOA</option>\n        \t\t\t<option value="ZR">ZAIRE</option>\n        \t\t\t<option value="ZM">ZAMBIA</option>\n        \t\t\t<option value="ZW">ZIMBABWE</option>\n        \t\t\t<option value="ZA">ZUID AFRIKA</option>\n        \t\t\t<option value="ZJ">ZUID JEMEN</option>\n        \t\t\t<option value="SE">ZWEDEN</option>\n        \t\t\t<option value="CH">ZWITSERLAND</option>\n            </select>\n             <span class="help-block" v-if="$v.landcode.$error && !$v.landcode.required">Land is verplicht</span>\n          </div>\n          <div class="machtiging_info">\n            Ik machtig hierbij Greenpeace \n            <template v-if="frequency === \'M\'">tot wederopzegging</template> \n            <template v-if="frequency === \'E\'">éénmalig</template> \n            <template v-if="frequency === \'F\'">12 maanden</template> \n            bovengenoemd bedrag van mijn rekening af te schrijven. <br/><br/>\n          </div>\n        </div>',data:function(){return{straat:"",postcode:"",huisnummer:"",huisnummertoevoeging:"",woonplaats:"",landcode:"NL"}},validations:{straat:{required:i},postcode:{minLength:r(6),maxLength:l(6),required:i,alphaNum:p},huisnummer:{required:i,numeric:u},huisnummertoevoeging:{maxLength:l(8)},woonplaats:{required:i},landcode:{required:i},form:["straat","postcode","huisnummer","huisnummertoevoeging","woonplaats","landcode"]},methods:{validate:function(){this.$v.form.$reset(),this.$v.form.$touch();var t=!this.$v.form.$invalid;return this.$emit("on-validate",this.$data,t),t&&dataLayer.push({event:"virtualPageViewDonatie",virtualPageviewStep:"Stap 3",virtuelPageviewName:"Adres"}),t},fetchAddress:function(){var t=document.getElementById("postal-code"),o=document.getElementById("housenumber"),n=t.value,e=o.value;Vue.http.interceptors.push(function(t,o){t.headers.set("x-api-key","P7TdlkQG4k4ppvVyAXmdD4TR9v5fW4YT8qv4TzOY"),t.headers.set("Accept","application/hal+json"),o()}),this.$http.get("https://api.postcodeapi.nu/v2/addresses/?postcode="+n+"&number="+e).then(function(t){var o=t.body._embedded.addresses[0].street,n=t.body._embedded.addresses[0].city.label;this.populateFields(o,n)},function(){})},populateFields:function(t,o){var n=document.getElementById("street"),e=document.getElementById("city");n.setAttribute("disabled","disabled"),e.setAttribute("disabled","disabled"),this.straat=t,this.woonplaats=o}},props:["frequency"]}),donationformVue=new Vue({el:"#app",data:{finalModel:{marketingcode:"M"===formconfig.suggested_frequency[0]?formconfig.marketingcode_recurring:formconfig.marketingcode_oneoff,literatuurcode:formconfig.literatuurcode,guid:"",betaling:"M"===formconfig.suggested_frequency[0]?"EM":"ID"},result:{msg:"",hasError:!1},idealData:{initials:"",firstname:"",middlename:"",lastname:"",gender:"",birthday:"",street:"",housenumber:"",housenumberAddition:"",postcode:"",city:"",email:"",phonenumber:"",description:"",amount:0,comment:"",issuersBank:"",clientIp:"",clientUserAgent:"",returnUrlSuccess:formconfig.returnpage,returnUrlCancel:formconfig.errorpage,returnUrlError:formconfig.errorpage,returnUrlReject:formconfig.errorpage,marketingCode:formconfig.marketingcode_oneoff,literatureCode:formconfig.literatuurcode,guid:null,countryId:null,accountNumber:null,subscriptionCode:null,subscriptionEndDate:null,subscriptionMonths:null}},methods:{onComplete:function(){var t=$("#app input"),o=$("#app button");$(".wizard-footer-right .wizard-btn").text(""),$(".wizard-footer-right .wizard-btn").addClass("loader"),this.disableFormElements(t),this.disableFormElements(o),$(".wizard-nav > li > a").addClass("disabled"),"ID"===this.finalModel.betaling?this.submitiDeal():this.submit()},onSucces:function(){var t=$("#Adres4");t.addClass("card"),t.empty(),t.append('<div class="card-body donation-card"></div>');var o=$(".donation-card");o.append('<h2 class="card-title">'+formconfig.thanktitle+"</h2>"),o.append('<p class="card-text">'+formconfig.thankdescription+"</p>"),$(".wizard-footer-right .wizard-btn").removeClass("loader"),$(".wizard-footer-right .wizard-btn").text("Afgerond"),dataLayer.push({event:"virtualPageViewDonatie",virtualPageviewStep:"Bedankt",virtuelPageviewName:"Bedankt"}),gtm_products=[],gtm_products.push({name:"machtiging",sku:this.finalModel.machtigingType,category:"donatie",price:this.finalModel.bedrag,quantity:1}),dataLayer.push({event:"trackTrans",transactionId:donationformVue.getGTMTransactionId(),transactionAffiliation:"",transactionTotal:this.finalModel.bedrag,transactionTax:"",transactionShipping:"",transactionPaymentType:"machtiging",transactionCurrency:"EUR",transactionPromoCode:"",transactionProducts:gtm_products});var n=e().clangct;null!=n&&$.ajax({url:"/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-conversion.js?clangct="+n,dataType:"script"})},onFailure:function(){var t=$("#Adres4");t.addClass("card"),t.empty(),t.append('<div class="card-body donation-card"></div>');var o=$(".donation-card");o.append('<h2 class="card-title">Sorry..</h2>'),o.append('<p class="card-text">Helaas gaat er iets mis met de donatieverwerking. Er wordt geen geld afgeschreven, probeer het later nog eens.</p>'),$(".wizard-footer-right .wizard-btn").removeClass("loader"),$(".wizard-footer-right .wizard-btn").text("Afgerond")},submit:function(){this.result.msg="",this.result.hasError=!1,this.finalModel.marketingcode="M"===this.finalModel.machtigingType?formconfig.marketingcode_recurring:formconfig.marketingcode_oneoff,$.ajax({method:"POST",url:"https://www.mygreenpeace.nl/GPN.RegistrerenApi/machtiging/register",data:JSON.stringify(this.finalModel),contentType:"application/json; charset=utf-8",dataType:"json",success:function(){donationformVue.onSucces()},error:function(){donationformVue.onFailure()}})},submitiDeal:function(){var t=e().clangct;null!=t&&$.ajax({url:"/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-conversion.js?clangct="+t,dataType:"script"}),this.result.msg="",this.result.hasError=!1,this.idealData.initials=this.finalModel.initialen,this.idealData.firstname=this.finalModel.voornaam,this.idealData.middlename=this.finalModel.tussenvoegsel,this.idealData.lastname=this.finalModel.achternaam,this.idealData.gender=this.finalModel.geslacht,this.idealData.email=this.finalModel.email,this.idealData.phonenumber=this.finalModel.telefoonnummer,this.idealData.description="Eenmalige donatie Greenpeace tnv "+this.finalModel.voornaam+" "+this.finalModel.achternaam,this.idealData.amount=this.finalModel.bedrag,$.ajax({method:"POST",url:"https://www.mygreenpeace.nl/GPN.RegistrerenApi/payment/ideal",data:JSON.stringify(this.idealData),contentType:"application/json; charset=utf-8",dataType:"json",success:function(t){window.location.href=t.transaction.redirectUrl},error:function(){donationformVue.onFailure()}})},disableFormElements:function(t){t.each(function(){this.setAttribute("disabled","true")})},validateStep:function(t){return this.$refs[t].validate()},mergePartialModels:function(t,o){o&&(this.finalModel=Object.assign({},this.finalModel,t),this.isiDeal(),this.$forceUpdate())},isiDeal:function(){return void 0!==this.$refs.step1?"ID"===this.$refs.step1._data.betaling:"ID"===this.finalModel.betaling},getFrequency:function(){return"F"===o.suggested_frequency?"F":void 0!==this.$refs.step1?this.$refs.step1._data.machtigingType:""},getGTMTransactionId:function(){return"_"+[this.finalModel.marketingcode]+"_"+Math.random().toString(36).substr(2,9)}}})});
//# sourceMappingURL=maps/donationform.js.map
