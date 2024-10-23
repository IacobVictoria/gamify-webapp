<?php

namespace App\Enums;

enum ProductCategory: string
{
    case Supplements = 'Supplements';           // Suplimente alimentare
    case Vitamins = 'Vitamins';                 // Vitamine și minerale
    case ProteinPowders = 'ProteinPowders';     // Pudre proteice
    case SportsEquipment = 'SportsEquipment';   // Echipament sportiv
    case FitnessWear = 'FitnessWear';           // Îmbrăcăminte fitness
    case HealthySnacks = 'HealthySnacks';       // Gustări sănătoase
    case OrganicFoods = 'OrganicFoods';         // Alimente organice
    case WeightLoss = 'WeightLoss';             // Produse pentru slăbit
    case Detox = 'Detox';                       // Produse pentru detoxifiere
    case MealKits = 'MealKits';                 // Kituri de mese sănătoase
    case EnergyDrinks = 'EnergyDrinks';         // Băuturi energizante
    case WaterBottles = 'WaterBottles';         // Sticle de apă reutilizabile
    case YogaAccessories = 'YogaAccessories';   // Accesorii pentru yoga
    case FitnessGadgets = 'FitnessGadgets';     // Gadgets pentru fitness (smartwatch-uri, monitoare de activitate)
    case TrainingSupplements = 'TrainingSupplements'; // Suplimente pentru antrenament
    case SportsNutrition = 'SportsNutrition';   // Nutriție sportivă
}