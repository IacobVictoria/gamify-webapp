<?php

namespace App\Services;
use App\Enums\UserQuizDifficulty;
use App\Events\ObtainBadge;
use App\Interfaces\BadgeServiceInterface;
use App\Models\Badge;
use App\Models\HangmanSession;
use App\Models\Review;
use App\Models\User;
use App\Models\UserBadge;
use Carbon\Carbon;
use Faker\Provider\Uuid;

class BadgeService implements BadgeServiceInterface
{
    protected $userScoreService, $notificationService;

    function __construct(UserScoreService $service, NotificationService $notificationService)
    {
        $this->userScoreService = $service;
        $this->notificationService = $notificationService;
    }
//reviewer
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
//commenter
    public function commenterBadges(?User $user)
    {
        $this->awardActiveCommenterBadge($user);
    }
//shopping
    public function shoopingBadges(?User $user)
    {
        $this->awardActiveShoppingBadge($user);
        $this->awardMonthlyShoppingBadge($user);
    }
//quiz
    public function quizBadges(?User $user)
    {
        $this->awardQuizPerfectScore($user);
        $this->awardQuizNoviceBadge($user);
        $this->awardQuizEnthusiastBadge($user);
        $this->awardQuizExplorerBadge($user);
    }
//event
    public function eventBadges(?User $user)
    {
        if (!$user) {
            return;
        }
        $this->awardFirstEventParticipationBadge($user);

        $this->awardThreeEventsParticipationBadge($user);
    }
//quiz-leaderboard
    public function quizLeaderboardBadges(?User $user)
    {
        if (!$user) {
            return;
        }
        $this->firstTimeFirstRankQuizTop($user);
        $this->awardEachRankInQuizTop($user);
        $this->awardSecondTimeAsFirstQuizTop($user);
    }
    //hangman
    public function hangmanGameBadges(?User $user)
    {
        if (!$user) {
            return;
        }
        $this->dailyPlayerHangmanGameFor7Days($user);
        $this->game10PlayedHangman($user);
        $this->firstGamePlayedHangman($user);
        $this->perfectGuesserHangmanGame($user);
        $this->nearPerfectGuesserHangmanGame($user);
    }

    public function awardTopReviewerBadge(User $user)
    {
        if ($user->reviews()->count() >= 2 && !$user->badges()->where('name', 'Top Reviewer')->exists()) {
            $this->assignBadge($user, 'Top Reviewer');
        }
    }

    public function awardActiveCommenterBadge(?User $user)
    {
        $badge = Badge::where('name', 'Active Commenter')->first();

        $commentCount = $user->reviewComments()->count();

        if ($commentCount >= 2 && !$user->badges()->where('name', 'Active Commenter')->exists()) {
            $this->assignBadge($user, 'Active Commenter');
        }
    }

    public function awardTrustedCommenterBadge(?User $user)
    {
        $badge = Badge::where('name', 'Trusted Commenter')->first();

        $commentCount = $user->commentLikes()->count();

        if ($commentCount >= 10 && !$user->badges()->where('name', 'Trusted Commenter')->exists()) {
            $this->assignBadge($user, 'Trusted Commenter');
        }
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
    }

    public function awardTrustedReviewerBadge(User $user)
    {
        // daca review urile lui au mai mult de 10 de like uri
        $badge = Badge::where('name', 'Trusted Reviewer')->first();

        if ($user->reviewlikes()->count() > 10 && !$user->badges()->where('name', 'Trusted Reviewer')->exists()) {
            $this->assignBadge($user, 'Trusted Reviewer');
        }
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
    }

    public function awardActiveShoppingBadge(User $user)
    {
        $badge = Badge::where('name', 'Active Shopper')->first();

        if ($user->orders()->count() > 10 && !$user->badges()->where('name', 'Active Shopper')->exists()) {
            $this->assignBadge($user, 'Active Shopper');
        }
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

    }

    public function assignBadge(User $user, string $badgeName)
    {
        $badge = Badge::where('name', $badgeName)->first();

        $user->badges()->attach($badge, ['id' => Uuid::uuid(), 'awarded_at' => now()]);

        $this->userScoreService->addScore($user, $badge->score);

        broadcast(new ObtainBadge($user, $badge, $this->notificationService));

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



    }

    public function awardQuizNoviceBadge(User $user)
    {
        $badge = Badge::where('name', 'Quiz Novice')->first();
        //daca a terminat vreun quiz
        $completedQuizzes = $user->quizResults()->where('is_locked', true)->count();

        if ($completedQuizzes === 1 && !$user->badges()->where('name', 'Quiz Novice')->exists()) {
            $this->assignBadge($user, 'Quiz Novice');
        }


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


    }
    public function awardFirstEventParticipationBadge(User $user)
    {
        $badge = Badge::where('name', 'First Event Participation')->first();

        $confirmedParticipationCount = $user->participants()->where('confirmed', true)->count();

        if ($confirmedParticipationCount === 1 && !$user->badges()->where('name', 'First Event Participation')->exists()) {
            $this->assignBadge($user, 'First Event Participation');
        }
    }

    public function awardThreeEventsParticipationBadge(User $user)
    {
        $badge = Badge::where('name', 'Three Events Participation')->first();

        // Obține numărul de participări confirmate ale utilizatorului
        $confirmedParticipationCount = $user->participants()->where('confirmed', true)->count();

        // Verifică dacă utilizatorul are exact 3 participări confirmate și nu are deja insigna de "Three Events Participation"
        if ($confirmedParticipationCount === 3 && !$user->badges()->where('name', 'Three Events Participation')->exists()) {
            $this->assignBadge($user, 'Three Events Participation');
        }

    }

    public function awardSecondTimeAsFirstQuizTop(User $user)
    {
        $rankCount = $user->quizLeaderboardHistory()->where('rank', 1)->count();

        if ($rankCount === 2 && !$user->badges()->where('name', 'Second Time First Rank in Quiz Top')->exists()) {
            // Award the badge
            $this->assignBadge($user, 'Second Time First Rank in Quiz Top');
        }

    }

    public function awardEachRankInQuizTop(User $user)
    {
        $maxRank = 3;
        $userRanks = $user->quizLeaderboardHistory()->pluck('rank')->unique();

        // Check if the user has reached all ranks
        if ($userRanks->count() === $maxRank && !$user->badges()->where('name', 'Each Rank in Quiz Top')->exists()) {
            $this->assignBadge($user, 'Each Rank in Quiz Top');
        }

    }

    public function firstTimeFirstRankQuizTop(User $user)
    {
        // Check if the user has ever been first in any quiz leaderboard
        $firstTime = $user->quizLeaderboardHistory()->where('rank', 1)->count() === 1;

        if ($firstTime && !$user->badges()->where('name', 'First Rank in Quiz Top')->exists()) {
            $this->assignBadge($user, 'First Rank in Quiz Top');
        }

    }

    public function perfectGuesserHangmanGame(User $user)
    {
        $mistakes = HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->get()
            ->every(function ($session) use ($user) {
                return $session->creator_id === $user->id
                    ? $session->mistakes_creator === 0
                    : $session->mistakes_opponent === 0;
            });

        if ($mistakes && !$user->badges()->where('name', 'Perfect Guesser')->exists()) {
            $this->assignBadge($user, 'Perfect Guesser');
        }
    }

    public function nearPerfectGuesserHangmanGame(User $user)
    {
        $mistakes = HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->get()
            ->every(function ($session) use ($user) {
                return $session->creator_id === $user->id
                    ? $session->mistakes_creator <= 1
                    : $session->mistakes_opponent <= 1;
            });

        if ($mistakes && !$user->badges()->where('name', 'Near-Perfect Guesser')->exists()) {
            $this->assignBadge($user, 'Near-Perfect Guesser');
        }
    }

    public function firstGamePlayedHangman(User $user)
    {
        if (
            HangmanSession::where('creator_id', $user->id)
                ->orWhere('opponent_id', $user->id)
                ->exists() &&
            !$user->badges()->where('name', 'First Game Played')->exists()
        ) {
            $this->assignBadge($user, 'First Game Played');
        }
    }

    public function game10PlayedHangman(User $user)
    {
        $count = HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->count();

        if ($count >= 10 && !$user->badges()->where('name', '10 Games Played')->exists()) {
            $this->assignBadge($user, '10 Games Played');
        }
    }

    public function dailyPlayerHangmanGameFor7Days(User $user)
    {
        $sessionsDates = HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->pluck('created_at')
            ->map(function ($date) {
                return $date->toDateString();
            })
            ->unique()->sort()->values();

        $consecutiveDays = 0;
        $previousDate = null;
        foreach ($sessionsDates as $date) {
            if ($date && Carbon::parse($date)->diffInDays($previousDate) == 1) {
                $consecutiveDays++;
            } else {
                $consecutiveDays = 1;
            }
            $previousDate = Carbon::parse($date);

            if ($consecutiveDays === 7) {
                if (!$user->badges()->where('name', 'Daily Player')->exists()) {
                    $this->assignBadge($user, 'Daily Player');
                }
                return;
            }
        }


    }

}