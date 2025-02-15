<?php 
return array (
  'back' => 'Edellinen',
  'environment' => 
  array (
    'classic' => 
    array (
      'back' => 'Käytä ohjattua lomaketoimintoa',
      'install' => 'Tallenna ja asenna',
      'save' => 'Tallenna .env',
      'templateTitle' => 'Vaihe 3 | Ympäristöasetukset | Klassinen toimittaja',
      'title' => 'Klassinen ympäristöeditori',
    ),
    'errors' => '.Env-tiedostoa ei voi tallentaa, luo se manuaalisesti.',
    'menu' => 
    array (
      'classic-button' => 'Klassinen tekstieditori',
      'desc' => 'Valitse, miten haluat määrittää sovellusten <code>.env</code> tiedoston.',
      'templateTitle' => 'Vaihe 3 | Ympäristöasetukset',
      'title' => 'Ympäristöasetukset',
      'wizard-button' => 'Ohjatun lomaketoiminnon asennus',
    ),
    'success' => '.Env-tiedostosi asetukset on tallennettu.',
    'wizard' => 
    array (
      'form' => 
      array (
        'app_debug_label' => 'Sovelluksen virheenkorjaus',
        'app_debug_label_false' => 'Väärä',
        'app_debug_label_true' => 'Totta',
        'app_environment_label' => 'Sovellusympäristö',
        'app_environment_label_developement' => 'Kehitys',
        'app_environment_label_local' => 'Paikallinen',
        'app_environment_label_other' => 'Muu',
        'app_environment_label_production' => 'Tuotanto',
        'app_environment_label_qa' => 'Qa',
        'app_environment_placeholder_other' => 'Anna ympäristösi ...',
        'app_log_level_label' => 'Sovelluslokitaso',
        'app_log_level_label_alert' => 'hälytys',
        'app_log_level_label_critical' => 'kriittinen',
        'app_log_level_label_debug' => 'virheenkorjaus',
        'app_log_level_label_emergency' => 'hätä',
        'app_log_level_label_error' => 'virhe',
        'app_log_level_label_info' => 'tiedot',
        'app_log_level_label_notice' => 'ilmoitus',
        'app_log_level_label_warning' => 'Varoitus',
        'app_name_label' => 'Sovelluksen nimi',
        'app_name_placeholder' => 'Sovelluksen nimi',
        'app_tabs' => 
        array (
          'broadcasting_label' => 'Lähetetty ohjain',
          'broadcasting_placeholder' => 'Lähetetty ohjain',
          'broadcasting_title' => 'Lähetys, välimuisti, istunto ja jono',
          'cache_label' => 'Välimuistiohjain',
          'cache_placeholder' => 'Välimuistiohjain',
          'mail_driver_label' => 'Postiohjain',
          'mail_driver_placeholder' => 'Postiohjain',
          'mail_encryption_label' => 'Postin salaus',
          'mail_encryption_placeholder' => 'Postin salaus',
          'mail_host_label' => 'Mail Host',
          'mail_host_placeholder' => 'Mail Host',
          'mail_label' => 'Mail',
          'mail_password_label' => 'Sähköpostin salasana',
          'mail_password_placeholder' => 'Sähköpostin salasana',
          'mail_port_label' => 'Mail Port',
          'mail_port_placeholder' => 'Mail Port',
          'mail_username_label' => 'Lähetä käyttäjänimi',
          'mail_username_placeholder' => 'Lähetä käyttäjänimi',
          'more_info' => 'Lisätietoja',
          'pusher_app_id_label' => 'Pusher-sovelluksen tunnus',
          'pusher_app_id_palceholder' => 'Pusher-sovelluksen tunnus',
          'pusher_app_key_label' => 'Työnnä sovelluksen avain',
          'pusher_app_key_palceholder' => 'Työnnä sovelluksen avain',
          'pusher_app_secret_label' => 'Pusher-sovelluksen salaisuus',
          'pusher_app_secret_palceholder' => 'Pusher-sovelluksen salaisuus',
          'pusher_label' => 'Työnnä',
          'queue_label' => 'Jonon ohjain',
          'queue_placeholder' => 'Jonon ohjain',
          'redis_host' => 'Redis-isäntä',
          'redis_label' => 'Redis-kuljettaja',
          'redis_password' => 'Uudelleen salasana',
          'redis_port' => 'Redis Port',
          'session_label' => 'Istunnon kuljettaja',
          'session_placeholder' => 'Istunnon kuljettaja',
        ),
        'app_url_label' => 'Sovelluksen URL-osoite',
        'app_url_placeholder' => 'Sovelluksen URL-osoite',
        'buttons' => 
        array (
          'install' => 'Asentaa',
          'setup_application' => 'Asennusohjelma',
          'setup_database' => 'Asetustietokanta',
        ),
        'db_connection_failed' => 'Yhteys tietokantaan epäonnistui.',
        'db_connection_label' => 'Tietokantayhteys',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Tietokannan isäntä',
        'db_host_placeholder' => 'Tietokannan isäntä',
        'db_name_label' => 'Tietokannan nimi',
        'db_name_placeholder' => 'Tietokannan nimi',
        'db_password_label' => 'Tietokannan salasana',
        'db_password_placeholder' => 'Tietokannan salasana',
        'db_port_label' => 'Tietokantaportti',
        'db_port_placeholder' => 'Tietokantaportti',
        'db_username_label' => 'Tietokannan käyttäjänimi',
        'db_username_placeholder' => 'Tietokannan käyttäjänimi',
        'name_required' => 'Ympäristön nimi vaaditaan.',
      ),
      'tabs' => 
      array (
        'application' => 'Sovellus',
        'database' => 'Tietokanta',
        'environment' => 'Ympäristö',
      ),
      'templateTitle' => 'Vaihe 3 | Ympäristöasetukset | Ohjattu ohjattu toiminto',
      'title' => 'Ohjattu <code>.env</code> ohjattu toiminto',
    ),
  ),
  'final' => 
  array (
    'console' => 'Sovelluskonsolin lähtö:',
    'env' => 'Lopullinen .env-tiedosto:',
    'exit' => 'Napsauta tätä poistuaksesi',
    'finished' => 'Sovellus on asennettu onnistuneesti.',
    'log' => 'Asennuslokilista:',
    'migration' => 'Siirtyminen ja Seed Console -tulos:',
    'templateTitle' => 'Asennus valmis',
    'title' => 'Asennus valmis',
  ),
  'finish' => 'Asentaa',
  'forms' => 
  array (
    'errorTitle' => 'Seuraavia virheitä tapahtui:',
  ),
  'install' => 'Asentaa',
  'installed' => 
  array (
    'success_log_message' => 'Laravel Installer -asennus onnistui',
  ),
  'next' => 'Seuraava askel',
  'permissions' => 
  array (
    'next' => 'Määritä ympäristö',
    'templateTitle' => 'Vaihe 2 | Käyttöoikeudet',
    'title' => 'Käyttöoikeudet',
  ),
  'requirements' => 
  array (
    'next' => 'Tarkista käyttöoikeudet',
    'templateTitle' => 'Vaihe 1 | Palvelinvaatimukset',
    'title' => 'Palvelinvaatimukset',
  ),
  'title' => 'Laravel-asennusohjelma',
  'updater' => 
  array (
    'final' => 
    array (
      'exit' => 'Napsauta tätä poistuaksesi',
      'finished' => 'Sovelluksen tietokanta on päivitetty onnistuneesti.',
      'title' => 'Valmis',
    ),
    'log' => 
    array (
      'success_message' => 'Laravel Installer -päivitys onnistui',
    ),
    'overview' => 
    array (
      'install_updates' => 'Asenna päivitykset',
      'message' => 'Päivityksiä on 1. | Päivityksiä on :number.',
      'title' => 'Yleiskatsaus',
    ),
    'title' => 'Laravel Updater',
    'welcome' => 
    array (
      'message' => 'Tervetuloa ohjattuun päivitystoimintoon.',
      'title' => 'Tervetuloa päivittäjään',
    ),
  ),
  'welcome' => 
  array (
    'message' => 'Helppo asennus ja ohjattu asennus.',
    'next' => 'Tarkista vaatimukset',
    'templateTitle' => 'Tervetuloa',
    'title' => 'Laravel-asennusohjelma',
  ),
);