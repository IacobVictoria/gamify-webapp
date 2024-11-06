<?php

namespace App\Services;
use App\Enums\UserQuizDifficulty;
use App\Events\ObtainBadge;
use App\Interfaces\BadgeServiceInterface;
use App\Models\Badge;
use App\Models\Review;
use App\Models\User;
use App\Models\UserBadge;
use Faker\Provider\Uuid;

class BadgeService implements BadgeServiceInterface
{
    protected $userScoreService;

    function __construct(UserScoreService $service)
    {
        $this->userScoreService = $service;
    }

    public function reviewerBadges(?User $user)
    {
        if (!$user) {
            return;
        }

        $this->awardTopReviewerBadge($user);
        $this->awardActiveReviewerBadge($user);
        $this->awardProductExpertBadge($user);
        $this->awardTrustedReviewerBadge($user);
        $this->awardPioneerBadge($user);
    }

    public function commenterBadges(?User $user)
    {
        $this->awardActiveCommenterBadge($user);
    }
    public function shoopingBadges(?User $user)
    {
        $this->awardActiveShoppingBadge($user);
        $this->awardMonthlyShoppingBadge($user);
    }

    public function quizBadges(?User $user)
    {
        $this->awardQuizPerfectScore($user);
        $this->awardQuizNoviceBadge($user);
        $this->awardQuizEnthusiastBadge($user);
        $this->awardQuizExplorerBadge($user);
    }

    public function awardTopReviewerBadge(User $user)
    {
        $badge = Badge::where('name', 'Top Reviewer')->first();

        if ($user->reviews()->count() >= 2 && !$user->badges()->where('name', 'Top Reviewer')->exists()) {
            $this->assignBadge($user, 'Top Reviewer');
        }

        $this->userScoreService->addScore($user, $badge->score);

    }

    public function awardActiveCommenterBadge(?User $user)
    {
        $badge = Badge::where('name', 'Active Commenter')->first();

        $commentCount = $user->reviewComments()->count();

        if ($commentCount >= 2 && !$user->badges()->where('name', 'Active Commenter')->exists()) {
            $this->assignBadge($user, 'Active Commenter');
        }

        $this->userScoreService->addScore($user, $badge->score);
    }

    public function awardTrustedCommenterBadge(?User $user)
    {
        $badge = Badge::where('name', 'Trusted Commenter')->first();

        $commentCount = $user->commentLikes()->count();

        if ($commentCount >= 10 && !$user->badges()->where('name', 'Trusted Commenter')->exists()) {
            $this->assignBadge($user, 'Trusted Commenter');
        }

        $this->userScoreService->addScore($user, $badge->score);
    }

    public function awardProductExpertBadge(User $user)
    {
        //review with more than 100 characters
        $reviews = $user->reviews;

        $reviewsLong = $reviews->filter(function ($review) {
            return strlen($review->description) > 100;
        });
        $badge = Badge::where('name', 'Product Expert')->first();

        if ($reviewsLong->count() > 20 && !$user->badges()->where('name', 'Product Expert')->exists()) {
            $this->assignBadge($user, 'Product Expert');
        }
        $this->userScoreService->addScore($user, $badge->score);
    }

    public function awardTrustedReviewerBadge(User $user)
    {
        // daca review urile lui au mai mult de 10 de like uri
        $badge = Badge::where('name', 'Trusted Reviewer')->first();

        if ($user->reviewlikes()->count() > 10 && !$user->badges()->where('name', 'Trusted Reviewer')->exists()) {
            $this->assignBadge($user, 'Trusted Reviewer');
        }
        $this->userScoreService->addScore($user, $badge->score);
    }

    public function awardActiveReviewerBadge(User $user)
    {
        $badge = Badge::where('name', 'Active Reviewer')->first();

        $reviewCount = $user->reviews()
            ->selectRaw('WEEK(created_at) as review_week, COUNT(*) as review_count')
            ->where('created_at', '>=', now()->subMonths(3))
            ->groupBy('review_week')
            ->havingRaw('review_count >= 1')
            ->count();

        if ($reviewCount >= 5 && !$user->badges()->where('name', 'Active Reviewer')->exists()) {
            $this->assignBadge($user, 'Active Reviewer');
        }

        $this->userScoreService->addScore($user, $badge->score);
    }

    public function awardPioneerBadge(User $user)
    {// de cate ori a dat primul un review
        $reviews = Review::orderBy('created_at', 'asc')->get()->groupBy('product_id');
        $userFirstReviewCount = 0;
        // sa iau primul review din fiecare grup de review uri ale unui produs
        //iar toate primul review de la toate produsele si vad de cate ori apare user->id
        foreach ($reviews as $productReviews) {
            $firstReview = $productReviews->first();
            if ($firstReview->user_id === $user->id) {
                $userFirstReviewCount++;
            }
        }

        $badge = Badge::where('name', 'Pioneer')->first();

        if ($userFirstReviewCount > 5 && !$user->badges()->where('name', 'Pioneer')->exists()) {
            $this->assignBadge($user, 'Pioneer');
        }

        $this->userScoreService->addScore($user, $badge->score);
    }

    public function awardActiveShoppingBadge(User $user)
    {
        $badge = Badge::where('name', 'Active Shopper')->first();

        if ($user->orders()->count() > 10 && !$user->badges()->where('name', 'Active Shopper')->exists()) {
            $this->assignBadge($user, 'Active Shopper');
        }

        $this->userScoreService->addScore($user, $badge->score);
    }

    public function awardMonthlyShoppingBadge(User $user)
    {

        $badge = Badge::where('name', 'Monthly Shopper')->first();
        //  câte luni utilizatorul a avut mai mult de 2 comenzi
        $activeMonthsCount = $user->orders()
            ->selectRaw('COUNT(*) as order_count, MONTH(created_at) as month, YEAR(created_at) as year')
            ->groupBy('year', 'month')  // grupam după an și lună
            ->havingRaw('order_count > 2')
            ->count();


        if ($activeMonthsCount > 0 && !$user->badges()->where('name', 'Monthly Shopper')->exists()) {
            $this->assignBadge($user, 'Monthly Shopper');
        }

        $this->userScoreService->addScore($user, $badge->score);
    }

    public function assignBadge(User $user, string $badgeName)
    {
        $badge = Badge::where('name', $badgeName)->first();

        $user->badges()->attach($badge, ['id' => Uuid::uuid(), 'awarded_at' => now()]);

        broadcast(new ObtainBadge($user,$badge));

    }

    public function awardQuizExplorerBadge(User $user)
    {

        $categories = UserQuizDifficulty::cases();

        $badge = Badge::where('name', 'Quiz Explorer')->first();

        // Verificăm dacă utilizatorul a participat la cel puțin un quiz din fiecare categorie
        $completedQuizzes = $user->quizResults()->get()->pluck('quiz.category')->unique();

        if (count($completedQuizzes) === count($categories) && !$user->badges()->where('name', 'Quiz Explorer')->exists()) {
            $this->assignBadge($user, 'Quiz Explorer');
        }

        
        $this->userScoreService->addScore($user, $badge->score);

    }

    public function awardQuizNoviceBadge(User $user)
    {
        $badge = Badge::where('name', 'Quiz Novice')->first();
        //daca a terminat vreun quiz
        $completedQuizzes = $user->quizResults()->where('is_locked', true)->count();

        if ($completedQuizzes === 1 && !$user->badges()->where('name', 'Quiz Novice')->exists()) {
            $this->assignBadge($user, 'Quiz Novice');
        }

        
        $this->userScoreService->addScore($user, $badge->score);

    }

    public function awardQuizEnthusiastBadge(User $user)
    {
        $badge = Badge::where('name', 'Quiz Enthusiast')->first();

        $quizResults = $user->quizResults()->where('is_locked', true)->get();

        if ($quizResults->count() >= 5) {
            $averageScore = $quizResults->avg('percentage_score');

            // verificăm dacă media este >= 80%
            if ($averageScore >= 80 && !$user->badges()->where('name', 'Quiz Enthusiast')->exists()) {
                $this->assignBadge($user, 'Quiz Enthusiast');
            }
        }

        
        $this->userScoreService->addScore($user, $badge->score);

    }

    public function awardQuizPerfectScore(User $user)
    {
        $badge = Badge::where('name', 'Quiz Perfect Score')->first();
        //Obținerea unui punctaj maxim (de exemplu, 100%) la 3 quiz uri
        $quizResults = $user->quizResults()->where('is_locked', true)->get();

        if ($quizResults->count() >= 3) {
            $averageScore = $quizResults->avg('percentage_score');

            if ($averageScore === 100 && !$user->badges()->where('name', 'Quiz Perfect Score')->exists()) {
                $this->assignBadge($user, 'Quiz Perfect Score');
            }
        }

        
        $this->userScoreService->addScore($user, $badge->score);


    }


}