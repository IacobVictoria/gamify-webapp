<?php

namespace App\Enums;

enum OrderStatus: string
{
        case Created = 'Created';              // Comanda este creată
        case Pending = 'Pending';       // În curs de realizare
        case Placed = 'Placed';                // Plasată
        case Processing = 'Processing';        // Procesată (pentru autorizare plată)
        case Authorized = 'Authorized';        // Autorizată (plată aprobată)
        case Expedited = 'Expedited';          // Expediată
        case Delivered = 'Delivered';          // Primită
        case Canceled = 'Canceled';            // Neautorizată sau anulată
}
