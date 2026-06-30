<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Thermal Printer (ESC/POS)
    |--------------------------------------------------------------------------
    |
    | Konfigurasi printer thermal via mike42/escpos-php.
    |
    | Mode yang didukung:
    |   - 'share'   : via Windows share name (butuh sharing diaktifkan)
    |   - 'usb'     : raw bytes langsung ke port USB (USB001, USB002, ...) - TANPA share
    |   - 'com'     : tulis ke COM port (COM3, COM4, ...) untuk printer serial
    |   - 'network' : via TCP ke IP:port (port raw umumnya 9100)
    |   - 'file'    : tulis ke file path (Linux: /dev/usb/lp0)
    |
    | Untuk mode 'usb', cek nama port di:
    |   Control Panel > Devices and Printers > Printer Properties > tab Ports
    | Port yang tercentang (mis. USB001) adalah nilai 'name' yang dimasukkan.
    |
    */

    'mode' => env('THERMAL_PRINTER_MODE', 'share'),

    'name' => env('THERMAL_PRINTER_NAME', ''),

    'width' => (int) env('THERMAL_PRINTER_WIDTH', 32),

    // Header & footer tiket (gunakan \n untuk baris baru)
    'header' => "MAJU CARE\nSERVICE CENTER\nJL. Pahlawan No. 38-40\nKota Madiun",

    'footer' => "Terima kasih\natas kunjungan anda",

];
