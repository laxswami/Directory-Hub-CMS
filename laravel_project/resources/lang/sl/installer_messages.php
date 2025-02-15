<?php 
return array (
  'back' => 'Prejšnji',
  'environment' => 
  array (
    'classic' => 
    array (
      'back' => 'Uporabite čarovnika za obrazce',
      'install' => 'Shranite in namestite',
      'save' => 'Shrani .env',
      'templateTitle' => '3. korak | Nastavitve okolja | Klasični urejevalnik',
      'title' => 'Urejevalnik klasičnega okolja',
    ),
    'errors' => 'Datoteke .env ni mogoče shraniti, jo ustvarite ročno.',
    'menu' => 
    array (
      'classic-button' => 'Klasični urejevalnik besedil',
      'desc' => 'Izberite, kako želite konfigurirati datoteko <code>.env</code> za aplikacije.',
      'templateTitle' => '3. korak | Nastavitve okolja',
      'title' => 'Nastavitve okolja',
      'wizard-button' => 'Nastavitev čarovnika za obrazce',
    ),
    'success' => 'Nastavitve datoteke .env so shranjene.',
    'wizard' => 
    array (
      'form' => 
      array (
        'app_debug_label' => 'Razhroščevanje aplikacij',
        'app_debug_label_false' => 'Lažno',
        'app_debug_label_true' => 'Prav',
        'app_environment_label' => 'App Environment',
        'app_environment_label_developement' => 'Razvoj',
        'app_environment_label_local' => 'Lokalno',
        'app_environment_label_other' => 'Drugo',
        'app_environment_label_production' => 'Proizvodnja',
        'app_environment_label_qa' => 'Qa',
        'app_environment_placeholder_other' => 'Vnesite svoje okolje ...',
        'app_log_level_label' => 'Raven dnevnika aplikacij',
        'app_log_level_label_alert' => 'opozorilo',
        'app_log_level_label_critical' => 'kritično',
        'app_log_level_label_debug' => 'odpravljanje napak',
        'app_log_level_label_emergency' => 'v sili',
        'app_log_level_label_error' => 'napaka',
        'app_log_level_label_info' => 'info',
        'app_log_level_label_notice' => 'opaziti',
        'app_log_level_label_warning' => 'Opozorilo',
        'app_name_label' => 'Ime aplikacije',
        'app_name_placeholder' => 'Ime aplikacije',
        'app_tabs' => 
        array (
          'broadcasting_label' => 'Broadcast Driver',
          'broadcasting_placeholder' => 'Broadcast Driver',
          'broadcasting_title' => 'Oddajanje, predpomnjenje, zasedanje in čakalna vrsta',
          'cache_label' => 'Predpomnilnik',
          'cache_placeholder' => 'Predpomnilnik',
          'mail_driver_label' => 'Mail Driver',
          'mail_driver_placeholder' => 'Mail Driver',
          'mail_encryption_label' => 'Šifriranje pošte',
          'mail_encryption_placeholder' => 'Šifriranje pošte',
          'mail_host_label' => 'Gostitelj pošte',
          'mail_host_placeholder' => 'Gostitelj pošte',
          'mail_label' => 'Mail',
          'mail_password_label' => 'Geslo za pošto',
          'mail_password_placeholder' => 'Geslo za pošto',
          'mail_port_label' => 'Mail Port',
          'mail_port_placeholder' => 'Mail Port',
          'mail_username_label' => 'Uporabniško ime za pošto',
          'mail_username_placeholder' => 'Uporabniško ime za pošto',
          'more_info' => 'Več informacij',
          'pusher_app_id_label' => 'ID aplikacije potiskalnika',
          'pusher_app_id_palceholder' => 'ID aplikacije potiskalnika',
          'pusher_app_key_label' => 'Tipka za potiskalnik',
          'pusher_app_key_palceholder' => 'Tipka za potiskalnik',
          'pusher_app_secret_label' => 'Pusher App Secret',
          'pusher_app_secret_palceholder' => 'Pusher App Secret',
          'pusher_label' => 'Potiskalnik',
          'queue_label' => 'Gonilnik čakalne vrste',
          'queue_placeholder' => 'Gonilnik čakalne vrste',
          'redis_host' => 'Redis gostitelj',
          'redis_label' => 'Redis Driver',
          'redis_password' => 'Redis geslo',
          'redis_port' => 'Pristanišče Redis',
          'session_label' => 'Voznik seje',
          'session_placeholder' => 'Voznik seje',
        ),
        'app_url_label' => 'URL aplikacije',
        'app_url_placeholder' => 'URL aplikacije',
        'buttons' => 
        array (
          'install' => 'Namestite',
          'setup_application' => 'Namestitveni program',
          'setup_database' => 'Nastavitev zbirke podatkov',
        ),
        'db_connection_failed' => 'Povezave z bazo podatkov ni bilo mogoče vzpostaviti.',
        'db_connection_label' => 'Povezava z bazo podatkov',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Gostitelj zbirke podatkov',
        'db_host_placeholder' => 'Gostitelj zbirke podatkov',
        'db_name_label' => 'Ime baze podatkov',
        'db_name_placeholder' => 'Ime baze podatkov',
        'db_password_label' => 'Geslo zbirke podatkov',
        'db_password_placeholder' => 'Geslo zbirke podatkov',
        'db_port_label' => 'Vrata baze podatkov',
        'db_port_placeholder' => 'Vrata baze podatkov',
        'db_username_label' => 'Uporabniško ime zbirke podatkov',
        'db_username_placeholder' => 'Uporabniško ime zbirke podatkov',
        'name_required' => 'Ime okolja je obvezno.',
      ),
      'tabs' => 
      array (
        'application' => 'Uporaba',
        'database' => 'Zbirka podatkov',
        'environment' => 'Okolje',
      ),
      'templateTitle' => '3. korak | Nastavitve okolja | Vodeni čarovnik',
      'title' => 'Vodeni čarovnik <code>.env</code>',
    ),
  ),
  'final' => 
  array (
    'console' => 'Izhod konzole aplikacije:',
    'env' => 'Končna datoteka .env:',
    'exit' => 'Za izhod kliknite tukaj',
    'finished' => 'Aplikacija je bila uspešno nameščena.',
    'log' => 'Vnos v dnevnik namestitve:',
    'migration' => 'Izhod iz selitvene konzole:',
    'templateTitle' => 'Namestitev končana',
    'title' => 'Namestitev končana',
  ),
  'finish' => 'Namestite',
  'forms' => 
  array (
    'errorTitle' => 'Pojavile so se naslednje napake:',
  ),
  'install' => 'Namestite',
  'installed' => 
  array (
    'success_log_message' => 'Namestitveni program Laravel je uspešno NAMESTEN',
  ),
  'next' => 'Naslednji korak',
  'permissions' => 
  array (
    'next' => 'Konfiguriranje okolja',
    'templateTitle' => '2. korak | Dovoljenja',
    'title' => 'Dovoljenja',
  ),
  'requirements' => 
  array (
    'next' => 'Preverite dovoljenja',
    'templateTitle' => '1. korak | Zahteve strežnika',
    'title' => 'Zahteve strežnika',
  ),
  'title' => 'Namestitveni program Laravel',
  'updater' => 
  array (
    'final' => 
    array (
      'exit' => 'Za izhod kliknite tukaj',
      'finished' => 'Baza podatkov aplikacije je bila uspešno posodobljena.',
      'title' => 'Dokončano',
    ),
    'log' => 
    array (
      'success_message' => 'Namestitveni program Laravel je bil USPEŠNO POSEBEN',
    ),
    'overview' => 
    array (
      'install_updates' => 'Namestite posodobitve',
      'message' => 'Obstaja 1 posodobitev. | Obstaja :number posodobitev.',
      'title' => 'Pregled',
    ),
    'title' => 'Laravel Updater',
    'welcome' => 
    array (
      'message' => 'Dobrodošli v čarovniku za posodobitve.',
      'title' => 'Dobrodošli v programu Updater',
    ),
  ),
  'welcome' => 
  array (
    'message' => 'Čarovnik za enostavno namestitev in namestitev.',
    'next' => 'Preverite zahteve',
    'templateTitle' => 'Dobrodošli',
    'title' => 'Namestitveni program Laravel',
  ),
);