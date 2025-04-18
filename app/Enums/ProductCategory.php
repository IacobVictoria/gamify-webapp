<?php

namespace App\Enums;

enum ProductCategory: string
{
    case Supplements = 'Supplements';         
    case Vitamins = 'Vitamins';                 
    case HealthySnacks = 'HealthySnacks';      
    case OrganicFoods = 'OrganicFoods';    
    case WeightLoss = 'WeightLoss';         
    case Detox = 'Detox';                      
    case EnergyDrinks = 'EnergyDrinks';        
}