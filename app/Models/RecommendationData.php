<?php

namespace App\Models;


use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationData extends Model
{
    use HasFactory;
    protected $table = 'recommendation_data';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'target',
        'has_bought',
        'has_wishlisted',
        'has_reviewed',
        'user_rating',
        'gender',
        'score_in_app',
        'price',
        'stock',
        'calories',
        'protein',
        'carbs',
        'fats',
        'fiber',
        'sugar',
        'category_Detox',
        'category_EnergyDrinks',
        'category_HealthySnacks',
        'category_OrganicFoods',
        'category_Supplements',
        'category_Vitamins',
        'category_WeightLoss',
        'allergen_eggs',
        'allergen_lactose',
        'allergen_none',
        'allergen_nuts',
        'allergen_gluten',
        'allergen_soy',
        'allergen_fish',
        'cluster_0',
        'cluster_1',
        'cluster_2',
        'cluster_3',
        'health_score',
        'total_units_sold',
        'wishlist_count',
        'average_rating',
        'review_count',
        'avg_likes_per_review'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Uuid::uuid();
            }
        });
    }
}
