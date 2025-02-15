<?php

return array (
    'import-errors' => [
        'timezone-not-exist' => "The timezone identifier does not found.",
    ],

    'import-item-data-item-hours' => "Hours",
    'import-item-data-item-image-hour-exceptions' => "Exceptional Hours",

    'import-item-data-item-hours-help' => "The hour format (one per line) should be day-of-week_hh:mm_hh:mm, for example, 1_08:00_23:00 means open on Monday, and starts at 8 o'clock and close at 23 o'clock.",
    'import-item-data-item-image-hour-exceptions-help' => "The hour exceptions format (one per line) should be yyyy-mm-dd_hh:mm_hh:mm or yyyy-mm-dd, for example, 2022-03-10_13:00_16:00 means opens on 2022-03-10 from 13 o'clock to 16 o'clock. or, 2022-12-25 means close all day on 2022-12-25",

    'timezone-import-help' => "The timezone is predefined identifiers in PHP.",
    'timezone-php' => "Php timezone",
    'show-hours-import-help' => "The show hours option is set by 1 (show hours) or 2 (not show hours).",
    'hours-import-help' => "The hour option should be day-of-week_hh:mm_hh:mm, for example, 1_08:00_23:00 means open on Monday, and starts at 8 o'clock and close at 23 o'clock. Please separate each hour with whitespace.",
    'exceptional-hours-help' => "The exceptional hours option should be yyyy-mm-dd_hh:mm_hh:mm or yyyy-mm-dd, for example, 2022-03-10_13:00_16:00 means opens on 2022-03-10 from 13 o'clock to 16 o'clock. or, 2022-12-25 means close all day on 2022-12-25. Please separate each exceptional hour with whitespace.",
    'download-sample-import-csv' => "Download a sample csv file",
);
