Toegevoegd:

- Dir assets in de includes dir
- Daarin staan de dirs css, en js ..
- ..met daarin een styles.css en docReady.js bestand



Aangepast:

- petition.twig
- planet4-gpnl-blocks.php


In het laatste bestand staat de call naar het css en ja bestand alsook de afhandeling van de ajax call na een submit.
De code staat er allemaal al in maar is nog niet getest.

In de functie petition_form_process() wordt de POST submit data afgevangen en doorgestuurd naar de curl functie. Aan het einde van de functie staat 'return $response' maar waarschijnlijk is die niet nodig.

Documentatie had ik van 'https://wordpress.stackexchange.com/questions/185148/custom-form-with-ajax' of deze 'http://natko.com/wordpress-ajax-login-without-a-plugin-the-right-way/'.



Werking:

Via het docReady.js bestand wordt er een ajax call gedaan naar Wordpress. In wordpress wordt de data afgevangen via admin-ajax.php (staat in wp-admin uit mijn hoofd). Het kan zijn dat hij nog niet goed wordt doorverwezen in planet4-gpnl-blocks.php


TEST literatuur en marketingcode, resp. 'ENTST' en '09996'. Paul heeft een testpetitie hiervoor aangemaakt en kan kijken of de data goed doorkomt.


Hoop dat het lukt!