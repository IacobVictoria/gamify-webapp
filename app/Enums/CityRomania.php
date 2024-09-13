<?php

namespace App\Enums;

enum CityRomania: string
{
    case Bucharest = 'Bucharest';
    case ClujNapoca = 'Cluj-Napoca';
    case Timisoara = 'Timișoara';
    case Iasi = 'Iași';
    case Constanta = 'Constanța';
    case Craiova = 'Craiova';
    case Brasov = 'Brașov';
    case Galati = 'Galați';
    case Ploiesti = 'Ploiești';
    case Oradea = 'Oradea';
    case Arad = 'Arad';
    case Sibiu = 'Sibiu';
    case BaiaMare = 'Baia Mare';
    case Buzau = 'Buzău';
    case Suceava = 'Suceava';
    case TarguMures = 'Târgu Mureș';
    case RamnicuValcea = 'Râmnicu Vâlcea';
    case AlbaIulia = 'Alba Iulia';
    case Targoviste = 'Târgoviște';
    case Botosani = 'Botoșani';

    public static function getAllCities(): array
    {
        return array_map(fn($location) => ['value' => $location->value, 'label' => $location->value], self::cases());
    }
}
