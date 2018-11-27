Vue.use(window.vuelidate.default)
const {
    required,
    between,
    minLength,
    maxLength,
    email,
    numeric,
    alphaNum,
    requiredIf
} = window.validators
Vue.use(VueFormWizard)

  Vue.component('step1', {
    template: `
        <div>
          <div class="form-group" v-bind:class="{ 'has-error': $v.machtigingType }">
            <label v-if="formconfig.allow_frequency_override == 'true'">Ja ik steun Greenpeace:</label>
            <label v-else>
            	Ja ik steun Greenpeace <strong>{{ formconfig.suggested_frequency[1] }}</strong>:
            </label>
            
            <select class="form-control" v-model.trim="machtigingType" @input="$v.machtigingType.$touch()" v-show="formconfig.allow_frequency_override == 'true'">
              <option value="E">Eenmalig</option>
              <option value="M">Maandelijks</option>
            </select>
            <span class="help-block" v-if="$v.machtigingType.$error && !$v.machtigingType.required">Periodiek is verplicht</span>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12" v-bind:class="{ 'has-error': $v.bedrag.$error }">
              <label>Met een bedrag van:</label>
              <div class="radio-list">
                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="bedrag1" id="bedrag1" v-bind:value="formconfig.amount1">
                <label class="form-check-label form-control left" for="bedrag1">&euro;{{ formconfig.amount1 }}</label>

                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="bedrag2" id="bedrag2" v-bind:value="formconfig.amount2" checked="checked">
                <label class="form-check-label form-control" for="bedrag2">&euro;{{ formconfig.amount2 }}</label>

                <input class="form-check-input" v-model.trim="bedrag" type="radio" name="bedrag3" id="bedrag3" v-bind:value="formconfig.amount3">
                <label class="form-check-label form-control" for="bedrag3">&euro;{{ formconfig.amount3 }}</label>
              </div>
            </div>

            <div class="form-group col-md-12" v-bind:class="{ 'has-error': $v.bedrag.$error }">
              <label>Ander bedrag:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">&euro;</div>
                </div>
                <input class="form-control" v-model.trim="bedrag" @input="$v.bedrag.$touch()">
                <span class="help-block" v-if="$v.bedrag.$error && !$v.bedrag.required">Bedrag is verplicht</span>
                <span class="help-block" v-if="$v.bedrag.$error && $v.bedrag.required && !$v.bedrag.numeric">Bedrag moet een nummer zijn</span>
                <span class="help-block" v-if="$v.bedrag.$error && $v.bedrag.required && $v.bedrag.numeric && !$v.bedrag.between">Het minimale donatiebedrag is {{ formconfig.min_amount }} euro</span>
              </div>
            </div>
          </div>
        </div>`,
    data() {
      return {
        machtigingType: formconfig.suggested_frequency[0],
        bedrag: formconfig.suggested_amount
      }
    },
    validations: {
      machtigingType: {
        required
      },
      bedrag: {
        required,
        numeric,
        between: between(formconfig.min_amount, 999)
      },
      form: ['machtigingType', 'bedrag' ]
    },
    methods: {
      validate() {
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
        return isValid
      }
    }
  })

  Vue.component('step2', {
    template: `
        <div>
          <div class="form-group">
            <div class="input-group" v-bind:class="{ 'has-error': $v.geslacht.$error }">

              <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend">Aanhef:</span>
                </div>

              <!-- label>Land</label-->
              <select class="form-control" v-model.trim="geslacht" @input="$v.geslacht.$touch()">
                <option value="V">Mevrouw</option>
                <option value="M">Meneer</option>
                <option value="O">Beste</option>
              </select>

              <!-- label>Geslacht</label-->
              <!-- div class="radio-list">
                <input class="form-check-input" v-model.trim="geslacht" type="radio" name="inlineRadioOptions" id="geslacht3" value="O">
                <label class="form-check-label form-control geslacht" for="geslacht3">Beste,</label>

                <input class="form-check-input" v-model.trim="geslacht" type="radio" name="inlineRadioOptions" id="geslacht1" value="M">
                <label class="form-check-label form-control left geslacht" for="geslacht1">Mijnheer,</label>

                <input class="form-check-input" v-model.trim="geslacht" type="radio" name="inlineRadioOptions" id="geslacht2" value="V">
                <label class="form-check-label form-control geslacht" for="geslacht2">Mevrouw,</label>
              </div-->

              <span class="help-block" v-if="$v.geslacht.$error && !$v.geslacht.required">Geslacht is verplicht</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-8" v-bind:class="{ 'has-error': $v.voornaam.$error }">
              <!-- label>Voornaam</label-->
              <input class="form-control" v-model.trim="voornaam" @input="$v.voornaam.$touch()" placeholder="Voornaam*">
               <span class="help-block" v-if="$v.voornaam.$error && !$v.voornaam.required">Voornaam is verplicht</span>
            </div>

            <div class="form-group col-md-4" v-bind:class="{ 'has-error': $v.initialen.$error }">
              <!-- label>Initialen</label-->
              <input class="form-control" v-model.trim="initialen" @input="$v.initialen.$touch()" placeholder="Initialen">
               <span class="help-block" v-if="$v.initialen.$error && !$v.initialen.required">Initialen zijn verplicht</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <!-- label>Tussenvoegsel</label-->
              <input class="form-control" v-model.trim="tussenvoegsel" @input="$v.tussenvoegsel.$touch()" placeholder="Tussenv.">
            </div>

            <div class="form-group col-md-8" v-bind:class="{ 'has-error': $v.achternaam.$error }">
              <!-- label>Achternaam</label-->
              <input class="form-control" v-model.trim="achternaam" @input="$v.achternaam.$touch()" placeholder="Achternaam*">
               <span class="help-block" v-if="$v.achternaam.$error && !$v.achternaam.required">Achternaam is verplicht</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12" v-bind:class="{ 'has-error': $v.email.$error }">
              <!-- label>Email</label-->
              <input class="form-control" v-model.trim="email" @input="$v.email.$touch()" placeholder="E-mail*">
              <span class="help-block" v-if="$v.email.$error && !$v.email.required">E-mail is verplicht</span>
              <span class="help-block" v-if="$v.email.$error && !$v.email.email">Dit is geen valide e-mail adres</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12" v-bind:class="{ 'has-error': $v.telefoonnummer.$error }">
              <!-- label>Telefoonnummer</label-->
              <input class="form-control" v-model.trim="telefoonnummer" @input="$v.telefoonnummer.$touch()" placeholder="Tel. nr.">
               <span class="help-block" v-if="$v.telefoonnummer.$error && !$v.telefoonnummer.required">Telefoonnummer is verplicht</span>
               <span class="help-block" v-if="$v.telefoonnummer.$error && $v.telefoonnummer.required && !$v.telefoonnummer.numeric">Telefoonnummer moet een nummer zijn</span>
               <span class="help-block" v-if="$v.telefoonnummer.$error && $v.telefoonnummer.required && $v.telefoonnummer.numeric && !$v.telefoonnummer.between">Telefoonnummer moet uit 10 cijfers bestaan</span>
            </div>
          </div>

          <div class="form-group" v-bind:class="{ 'has-error': $v.rekeningnummer.$error }">
            <!-- label>rekeningnummer</label-->
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">IBAN:</span>
              </div>
              <input class="form-control" v-model.trim="rekeningnummer" @input="$v.rekeningnummer.$touch()" placeholder="*">
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
        rekeningnummer: ''
      }
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
        required,
        alphaNum
      },
      form: ['initialen', 'voornaam', 'tussenvoegsel', 'achternaam', 'geslacht', 'email', 'telefoonnummer', 'rekeningnummer']
    },
    methods: {
      validate() {
        this.$v.form.$touch();
        var isValid = !this.$v.form.$invalid;
        this.$emit('on-validate', this.$data, isValid);
        if(isValid){
          // Push step to tag manager
          dataLayer.push({
          'event': 'virtualPageViewDonatie',
          'virtualPageviewStep': 'Stap 2', //Vul hier de stap in. E.g. Stap 1, Stap 2, Stap 3, Bedankt
          'virtuelPageviewName': 'Gegevens' // Vul hier de stapnaam in. E.g. Donatie, gegevens, adres, Bedankt
          });
        }
        return isValid
      }
    }
  })

  Vue.component('step3', {
    template: `
        <div>
          <div class="form-row">
            <div class="form-group col-md-5" v-bind:class="{ 'has-error': $v.postcode.$error }">
              <!-- label>Postcode</label-->
              <input id="postcode" class="form-control" v-model.trim="postcode" @input="$v.postcode.$touch()" placeholder="Postcode*">
               <span class="help-block" v-if="$v.postcode.$error && !$v.postcode.required">Postcode is verplicht</span>
               <span class="help-block" v-if="$v.postcode.$error && $v.postcode.required && !$v.postcode.alphaNum">Postcode mag alleen letters en cijfers bevatten, geen spaties</span>
               <span class="help-block" v-if="$v.postcode.$error && $v.postcode.required && $v.postcode.alphaNum && !$v.postcode.between">Postcode moet in 0000AA formaat</span>
            </div>

            <div class="form-group col-md-4" v-bind:class="{ 'has-error': $v.huisnummer.$error }">
              <!-- label>Huisnummer</label-->
              <input id="huisnummer" class="form-control" v-on:blur="fetchAddress()" v-model.trim="huisnummer" @input="$v.huisnummer.$touch()" placeholder="Huisnr.*">
               <span class="help-block" v-if="$v.huisnummer.$error && !$v.huisnummer.required">Huisnummer is verplicht</span>
               <span class="help-block" v-if="$v.huisnummer.$error && $v.huisnummer.required && !$v.huisnummer.numeric">Huisnummer moet een nummer zijn</span>
            </div>

            <div class="form-group col-md-3" v-bind:class="{ 'has-error': $v.huisnummertoevoeging.$error }">
              <!-- label>Toevoeging</label-->
              <input class="form-control" v-model.trim="huisnummertoevoeging" @input="$v.huisnummertoevoeging.$touch()" placeholder="Toev.">
            </div>
          </div>

          <div class="form-group">
            <!-- label>Straatnaam</label-->
            <input id="straatnaam" class="form-control" v-model.trim="straat" placeholder="Straat">
          </div>

          <div class="form-group">
            <!-- label>Woonplaats</label-->
            <input id="woonplaats" class="form-control" v-model.trim="woonplaats" placeholder="Plaats">
          </div>

          <div class="form-group" v-bind:class="{ 'has-error': $v.landcode.$error }">
            <!-- label>Land</label-->
            <select class="form-control" v-model.trim="landcode" @input="$v.landcode.$touch()">
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
          <div class="machtiging_info">
            Ik machtig hierbij Greenpeace tot wederopzegging (of éénmalig indien hierboven gekozen) bovengenoemd bedrag van mijn rekening af te schrijven.<br/><br/>
            Greenpeace beschermt je gegevens en geeft ze niet aan derden voor commerciële doeleinden. Lees ook ons <a href="/privacy" target="_blank">privacy-beleid</a>.<br/><br/>
          <label><a href="#privacyModal">Over jouw privacy</a></label><br/>
            <div id="privacyModal" class="modalDialog">
              <a href="#close" title="Close" class="close">X</a>
              <p>Greenpeace informeert en betrekt jou als supporter natuurlijk heel graag bij onze doelen. Hiervoor vragen we jou om jouw persoonsgegevens met ons te delen.<br/><br/>Greenpeace gebruikt je (persoons)gegevens om uitvoering te geven aan je donatie en om je op de hoogte te houden van haar activiteiten. Daarnaast kan Greenpeace jouw (persoons)gegevens gebruiken voor marketingdoeleinden per telefoon, post en email. Zie www.greenpeace.nl/privacy voor meer informatie over hoe Greenpeace met jouw gegevens omgaat.</p>
              <p>Vul je je adresgegevens in? Dan kan Greenpeace je per post op de hoogte houden van haar werk. Je ontvangt dan bijvoorbeeld periodiek ons geweldige magazine! Vul je je telefoonnummer in? Dan kan Greenpeace jou telefonisch benaderen voor giftverzoeken of updates rondom lopende campagnes.</p>
              <p>Wil je geen informatie meer van Greenpeace ontvangen? Neem dan kosteloos contact op met ons Supporter Care team: 0800 422 33 44 of ga naar greenpeace.nl</p>
            </div>
          <label><a href="#sepaModal"SEPA machtiging</a></label>
            <div id="sepaModal" class="modalDialog">
              <a href="#close" title="Close" class="close">X</a>
              <p>Door ondertekening van dit formulier geef je toestemming aan Greenpeace om eenmalig of doorlopend incasso-opdrachten naar jouw bank te sturen wegens je donateurschap aan Greenpeace zodat een bedrag van je rekening afgeschreven kan worden en aan jouw bank om eenmalig of doorlopend een bedrag van je rekening af te schrijven overeenkomstig de opdracht van Greenpeace. Als je het niet eens bent met deze afschrijving kun je deze laten terugboeken. Neem hiervoor 1binnen acht weken na afschrijving contact op met jouw bank.<br/>Vraag je bank naar de voorwaarden.</p>
              </div>
          </div>
        </div>`,
    data() {
      return {
        straat: '',
        postcode: '',
        huisnummer: '',
        huisnummertoevoeging: '',
        woonplaats: '',
        landcode: 'NL'
      }
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
        return isValid
      },

      fetchAddress: function() {
        var zipcodeInput = document.getElementById('postcode');
        var houseNoInput = document.getElementById('huisnummer');
        var zipcodeValue = zipcodeInput.value;
        var houseNoValue = houseNoInput.value;

        Vue.http.interceptors.push((request, next) => {
          request.headers.set('x-api-key', 'P7TdlkQG4k4ppvVyAXmdD4TR9v5fW4YT8qv4TzOY')
          request.headers.set('Accept', 'application/hal+json')
          next()
        })

        this.$http.get("https://api.postcodeapi.nu/v2/addresses/?postcode="+ zipcodeValue +"&number=" + houseNoValue +"")
        .then(function (response) {
            let street = response.body._embedded.addresses[0].street;
            let city = response.body._embedded.addresses[0].city.label;

            this.populateFields(street, city);
        }, function (error) {
            console.log(response.body);
        });
      },

      populateFields: function(street, city) {
        var streetInput = document.getElementById('straatnaam');
        var cityInput = document.getElementById('woonplaats');

        streetInput.setAttribute('disabled', 'disabled');
        cityInput.setAttribute('disabled', 'disabled');

        this.straat = street;
        this.woonplaats = city;
      }
    },
  })
  donationformVue = new Vue({
    el: '#app',
    data: {
      finalModel: {
        marketingcode: formconfig.marketingcode,
        literatuurcode: formconfig.literatuurcode,
        guid: ''
      },
      result: {
          msg: '',
          hasError: false
      },
    },
    methods: {
      onComplete: function() {
        this.submit();
      },

      onSucces: function(result) {
          // console.log(result.msg);
          console.log(this.finalModel);
          var formBody = $("#Adres4");
          formBody.addClass('card');
          formBody.empty();
          formBody.append('<div class="card-body donation-card"></div>')
          var cardBody = $('.donation-card');
          cardBody.append('<h2 class="card-title">{{ formconfig.thanktitle }}</h2>');
          cardBody.append('<p class="card-text">{{ formconfig.thankdescription}}</p>');
          buttons = $('#app button');
          buttons.each(function() {
              button = $(this);
              button.hide();
          });
        // Push step to tag manager
        dataLayer.push({
        'event': 'virtualPageViewDonatie',
        'virtualPageviewStep': 'Bedankt', //Vul hier de stap in. E.g. Stap 1, Stap 2, Stap 3, Bedankt
        'virtuelPageviewName': 'Bedankt' // Vul hier de stapnaam in. E.g. Donatie, gegevens, adres, Bedankt
        });
        /** Google Tag Manager E-commerce */

        // Build product array
          gtm_products = [];

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
      dataLayer.push({
      'event': 'trackTrans',
      'transactionId': '000111',
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
      },

      onFailure: function(result) {
        alert('Helaas gaat er iets mis met de donatieverwerking. Er wordt geen geld afgeschreven, probeer het later nog eens. '+result.msg);
      },

      submit: function () {
          inputs = $('#app input');
          buttons = $('#app button');
          this.disableFormElements(inputs);
          this.disableFormElements(buttons);
          this.result.msg = '';
          this.result.hasError = false;
          this.$http.post("https://www.mygreenpeace.nl/GPN.RegistrerenApi.Test/machtiging/register", this.finalModel)
          .then(function (response) {
              this.result.msg = response.bodyText;
              this.result.hasError = false;
              this.onSucces(this.result);
          }, function (error) {
              this.result.msg = error.bodyText;
              this.result.hasError = true;
              this.onFailure(this.result);
          });
      },

      disableFormElements: function (elements) {
          elements.each( function() {
              this.setAttribute('disabled', 'true');
          })
      },

      validateStep(name) {
        var refToValidate = this.$refs[name];
        return refToValidate.validate();
      },

      mergePartialModels(model, isValid) {
        if (isValid) {
          // merging each step model into the final model
          this.finalModel = Object.assign({}, this.finalModel, model)
        }
      }
    }
  })

function iDealToggle() {
   if ($('#frequency option:selected')[0].value === "E"){
	   $('.wizard-footer-right > span > button').attr('onClick', 'removeIdealBtn();');
	   $('.wizard-footer-right > span > button').text("Eenmalige machtiging");
	   $('.wizard-footer-right').prepend('<span id="iDealBtn" role="button" tabindex="0"><button tabindex="-1" type="button" class="wizard-btn" style="background-color: rgb(238, 86, 45); border-color: rgb(238, 86, 45); color: white;" onclick="idealTransaction()">iDeal</button></span>');
	   checkExist = setInterval(function() {
		   if ($('.wizard-footer-left > span > button').length) {
			   $('.wizard-footer-left > span > button').attr('onClick', 'resetNextBtn();');
			   clearInterval(checkExist);
		   }
	   }, 50);
   }
   else{
	   $('.wizard-footer-right > span > button').text("Volgende");
	   removeIdealBtn();
   }

}
function resetNextBtn() {
	donationformVue.ideal = false;
	removeIdealBtn();
	$('.wizard-footer-right > span > button').removeAttr('onclick');
	$('.wizard-footer-right > span > button').text("Volgende");
	checkExist = setInterval(function() {
		if ($('.wizard-footer-left > span > button').length ) {
			$('.wizard-footer-left > span > button').attr('onClick', 'resetNextBtn();');
			clearInterval(checkExist);
		}
	}, 50);
}
function removeIdealBtn() {
    donationformVue.ideal = false;
	$('#iDealBtn').remove();
}

function idealTransaction() {
	donationformVue.ideal = true;
	isValid = donationformVue.validateStep('step2', true);
	if (isValid){
        console.log(donationformVue.finalModel);
    }
}
